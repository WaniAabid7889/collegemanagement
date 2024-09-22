<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
$id = $_REQUEST['id'];
require_once('../layout/header.php');
require_once('../models/fee-model.php');
$feeObj = new feeModel();
$students = $feeObj->getFeeById($id);



if(isset($_POST['submit'])){
    $studentId = $students['student_id'];
    $feeId  = $students['id'];
    $payment = $_POST['paid'];
    $date = $_POST['date'];
    $method = $_POST['method'];
    

    $params = array($payment,$date,$method,$studentId,$feeId);


    $rest = $feeObj->updateFeePayment($date,$payment,$method,$feeId);
    if($rest){
        echo "<script>window.location.href = 'fees.php';</script>";
    }
}

?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>

<div class="container mt-5">
    <div class="row bg-dark d-flex text-white" style="padding:5px; justify-content:center">
        <b>Payment</b>
        <a href="../pages/fees.php" class="fa fa-times text-white" style="text-decoration:none; font-size:20px"></a>
    </div>

    <form id="feeForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <input type="hidden" name="feeId" id="feeId" value="">
        
        <div class="row mt-3">
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="student_id" class="form-label">Student</label>
                <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $students['fname']." ".$students['lname']; ?>" disabled>
            </div>

            <div class="col-md-6 col-sm-12 mb-3">
                <label for="student_id" class="form-label">Fee Type</label>
                <input type="text" class="form-control" name="fee_type" id="fee_type" value="<?php echo $students['fee_type']; ?>" disabled>
            </div>
            
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="balance" class="form-label">Balance</label>
                <input type="text" class="form-control" name="balance" id="balance" value="<?php echo $students['balance']; ?>" disabled>
            </div>

            <div class="col-md-6 col-sm-12 mb-3">
                <label for="paid" class="form-label">Payment</label>
                <input type="number" min="0" class="form-control" name="paid" id="paid" placeholder="Enter the payment amount">
            </div>
            <div class="col-md-6 col-sm-12 mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>

            <div class="col-md-6 col-sm-12 mb-3">
                <label for="method" class="form-label">Payment Method</label>
                <select id="method" name="method" class="form-select" required onchange="showPaymentDetails()">
                    <option value="" disabled selected>Select Method</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Online Payment">Online Payment</option>
                    <option value="Check">Check</option>
                    <option value="Others">Others</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Payment Details -->
        <div id="paymentDetails" class="row"></div>

        <button type="submit" name="submit" class="btn btn-primary float-end">Pay</button>
        <button type="button" class="btn btn-secondary float-end mx-2" onclick="clearForm()">Cancel</button>
    </form>
</div>

<script>
    function showPaymentDetails() {
        const method = document.getElementById('method').value;
        const paymentDetails = document.getElementById('paymentDetails');
        paymentDetails.innerHTML = '';

        if (method === 'Credit Card' || method === 'Debit Card') {
            paymentDetails.innerHTML = `
                <div class="col-md-6 col-sm-12 mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" required>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                    <label for="expiry_date" class="form-label">Expiry Date</label>
                    <input type="month" class="form-control" id="expiry_date" name="expiry_date" required>
                </div>
                <div class="col-md-3 col-sm-12 mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>
            `;
        } else if (method === 'Bank Transfer') {
            paymentDetails.innerHTML = `
                <div class="col-md-6 col-sm-12 mb-3">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" required>
                </div>
                <div class="col-md-6 col-sm-12 mb-3">
                    <label for="bank_name" class="form-label">Bank Name</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                </div>
            `;
        } else if (method === 'Check') {
            paymentDetails.innerHTML = `
                <div class="col-md-6 col-sm-12 mb-3">
                    <label for="check_number" class="form-label">Check Number</label>
                    <input type="text" class="form-control" id="check_number" name="check_number" required>
                </div>
            `;
        } else if (method === 'Online Payment') {
            paymentDetails.innerHTML = `
                <div class="col-md-6 col-sm-12 mb-3">
                    <label for="upi_number" class="form-label">UPI Number</label>
                    <input type="text" class="form-control" id="upi_number" name="upi_number" required>
                </div>
            `;
        }else{
            paymentDetails.innerHTML = '';
        }
    }

    function clearForm() {
        document.getElementById('feeForm').reset();
        document.getElementById('paymentDetails').innerHTML = '';
    }
</script>

<?php require_once('../layout/footer.php');
 }else{
      header('location:404.php');
}