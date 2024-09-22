<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dompdf\Dompdf;

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {  
    require_once('../models/fee-model.php');
    $feeObj = new feeModel();
    $id = $_GET['id'];
    $data = $feeObj->getFeeById($id);

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    require '../dompdf/autoload.inc.php'; 

    function generatePDF($data) {
        $dompdf = new Dompdf();
        $html = "
        <html>
            <head>
                <title>Fee Receipt</title>
                <style>
                    .receipt-wrapper {
                        width: 800px;
                        margin: 40px auto;
                        font-family: Arial, sans-serif;
                    }
                    .receipt-card {
                        background-color: #f9f9f9;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .receipt-header {
                        text-align: center;
                    }
                    .receipt-table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .receipt-table th, .receipt-table td {
                        border: 1px solid #ddd;
                        padding: 10px;
                        text-align: left;
                    }
                    .receipt-table th {
                        background-color: #f0f0f0;
                    }
                </style>
            </head>
            <body>
                <div class='receipt-wrapper'>
                    <div class='receipt-card'>
                        <b>Gmail: <a href='#' style='text-decoration:none'>starcollage@gmail.com</a></b> 
                        <b style='position:relative; left:35%'>Contact: 1800-0900-2001</b>
                        <hr>
                        <div class='receipt-header'>
                            <h2>Fee Receipt</h2>
                        </div>
                        <table class='receipt-table'>
                            <tr>
                                <th colspan='4'>Student Information</th>
                            </tr>
                            <tr>
                                <td><strong>Student Name:</strong></td>
                                <td style='text-align:right'>" . $data['fname'] . " " . $data['lname'] . "</td>
                                <td><strong>Class:</strong></td>
                                <td style='text-align:right'>BCA 1st year regular</td>
                            </tr>
                            <tr>
                                <td><strong>Fee Type:</strong></td>
                                <td style='text-align:right'>" . $data['fee_type'] . "</td>
                                <td><strong>Due Date:</strong></td>
                                <td style='text-align:right'>" . $data['due_date'] . "</td>
                            </tr>
                            <tr>
                                <td><strong>Amount Due:</strong></td>
                                <td style='text-align:right'> " . $data['amount_due'] . "</td>
                                <td><strong>Amount Paid:</strong></td>
                                <td style='text-align:right'> " . $data['amount_paid'] . "</td>
                            </tr>
                            <tr>
                                <td><strong>Balance:</strong></td>
                                <td style='text-align:right'> " . $data['balance'] . "</td>
                                <td><strong>Payment Status:</strong></td>
                                <td style='text-align:right'>" . $data['payment_status'] . "</td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method:</strong></td>
                                <td style='text-align:right'>" . $data['payment_method'] . "</td>
                                <td><strong>Created At:</strong></td>
                                <td style='text-align:right'>" . $data['created_at'] . "</td>
                            </tr>
                            <tr>
                                <td><strong>Remarks:</strong> </td>
                                <td colspan='3' style='text-align:right'>" . $data['remarks'] . "</td>
                            </tr>
                            <tr>
                                <th colspan='4'>Particular</th>
                            </tr>
                            <tr>
                                <td colspan='3' style='text-align:right'><strong>Amount</strong></td>
                                <td> " . $data['amount_due'] . "</td>
                            </tr>
                            <tr>
                                <td colspan='3' style='text-align:right'><strong>Paid Fee</strong></td>
                                <td> " . $data['amount_paid'] . "</td>
                            </tr>
                            <tr>
                                <td colspan='3' style='text-align:right'><strong>Due Fee</strong></td>
                                <td> " . $data['balance'] . "</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </body>
        </html>
    ";

        // Load HTML into Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        // Save the PDF file to the receipts directory
        $filePath = '../receipts/receipt_' . $data['id'] . '.pdf';
        file_put_contents($filePath, $dompdf->output());

        return $filePath;
    }

    if (isset($_POST['send_email'])) {
        $subject = "Fee Receipt for " . $data['fname'] . " " . $data['lname'];
        $body = "
            <h4> Hello, ".$data['fname']." ".$data['lname']."</h4>

            <p>Please find attached fee Slip </p>

            <h5> If you have any question, do not hesitate to contact us.</h5>

            Thank you,<br/>
            Office Incharge <br/> 
            Star College<br/>
        ";

      //   Generate PDF receipt and get file path
        
        $pdfFilePath = generatePDF($data);
         echo $pdfFilePath;
         echo $data['student_id'];
         echo $data['id'];
         
        $stmt = $feeObj->receipt($data['student_id'],$data['id'],$pdfFilePath);
       
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                           
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'waniabid645@gmail.com';                     
            $mail->Password   = 'xrnsliygkxawhvqy';                              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Set the correct sender email address
            $mail->setFrom('waniabid645@gmail.com', 'Star College');
            $mail->addAddress($data['email'], $data['fname'] . " " . $data['lname']);

            // Attach the PDF file
            $mail->addAttachment($pdfFilePath);

            // Set email content
            $mail->isHTML(true);                                 
            $mail->Subject = $subject;
            $mail->Body    = $body;

            // Send the email
            if($mail->send()) {
                echo "<script>alert('Receipt sent to " . $data['email'] . "');</script>";
            } else {
                echo "<script>alert('Failed to send receipt');</script>";
            }
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="receipt-wrapper">
  <div class="receipt-card">
    <b>Gmail: <a href="#" style="text-decoration:none">starcollage@gmail.com</a></b> 
    <b style="position:relative; left:35%">Contact: 1800-0900-2001</b>
    <hr>
    <div class="receipt-header">
        <h2>Fee Receipt</h2>
    </div>
    <table class="receipt-table">
      <tr>
        <th colspan="4">Student Information</th>
      </tr>
      <tr>
        <td><strong>Student Name:</strong></td>
        <td style="text-align:right"><?php echo $data['fname'] . " " . $data['lname']; ?></td>
        <td><strong>Class:</strong></td>
        <td style="text-align:right">BCA 1st year regular</td>
      </tr>
      <tr>
        <td><strong>Fee Type:</strong></td>
        <td style="text-align:right"><?php echo $data['fee_type']; ?></td>
        <td><strong>Due Date:</strong></td>
        <td style="text-align:right"><?php echo $data['due_date']; ?></td>
      </tr>
      <tr>
        <td><strong>Amount Due:</strong></td>
        <td style="text-align:right">₹ <?php echo $data['amount_due']; ?></td>
        <td><strong>Amount Paid:</strong></td>
        <td style="text-align:right">₹ <?php echo $data['amount_paid']; ?></td>
      </tr>
      <tr>
        <td><strong>Balance:</strong></td>
        <td style="text-align:right">₹ <?php echo $data['balance']; ?></td>
        <td><strong>Payment Status:</strong></td>
        <td style="text-align:right"><?php echo $data['payment_status']; ?></td>
      </tr>
      <tr>
        <td><strong>Payment Method:</strong></td>
        <td style="text-align:right"><?php echo $data['payment_method']; ?></td>
        <td><strong>Created At:</strong></td>
        <td style="text-align:right"><?php echo $data['created_at']; ?></td>
      </tr>
      <tr>
        <td><strong>Remarks:</strong> </td>
        <td colspan="3" style="text-align:right"><?php echo $data['remarks']; ?></td>
      </tr>
      <!-- Particular -->
      <tr>
        <th colspan="4">Particular</th>
      </tr>
      <tr>
        <td colspan="3" style="text-align:right"><strong>Amount</strong></td>
        <td>₹ <?php echo $data['amount_due']; ?></td>
      </tr>
      <tr>
        <td colspan="3" style="text-align:right"><strong>Paid Fee</strong></td>
        <td>₹ <?php echo $data['amount_paid']; ?></td>
      </tr>
      <tr>
        <td colspan="3" style="text-align:right"><strong>Due Fee</strong></td>
        <td>₹ <?php echo $data['balance']; ?></td>
      </tr>
    </table>

    <div class="print-button float-end">
      <button onclick="printReceipt()">Print Receipt</button>
    </div>
  </div>
</div>

<form id="emailForm" method="POST" style="display: none;">
  <input type="hidden" name="send_email" value="true">
</form>

<script>
  function printReceipt() {
    window.print();
    document.getElementById("emailForm").submit();  
  }

</script>
</body>
</html>


<style>
  .receipt-wrapper {
    display: flex;
    justify-content: center;
    padding: 20px;
    background-color: #f4f4f4;
  }

  .receipt-card {
    width: 700px;
    height: auto;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
  }

  .receipt-header {
    text-align: center;
    padding-bottom: 10px;
  }

  .receipt-header h2 {
    margin: 0;
  }

  .receipt-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  .receipt-table th, .receipt-table td {
    padding: 10px;
    border: 1px solid #ddd;
  }

  .receipt-table th {
    background-color: #f2f2f2;
    text-align: left;
  }

  .print-button button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .print-button button:hover {
    background-color: #0056b3;
  }
</style>
</body>
</html>

<?php  

}else{
    header('location:404.php');
}
?>