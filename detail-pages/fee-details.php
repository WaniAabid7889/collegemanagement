<?php
 session_start();
 if(isset($_SESSION['username']) && isset($_SESSION['password']))
 {
    require_once('../layout/header.php');
    require_once('../models/fee-model.php');
    $feeObj = new feeModel();  
    $id = $_GET['id'];
    $data = $feeObj->getFeeById($id);

    $receipt = $feeObj->getReceiptById($id);
   
   
?>

<div class="d-flex justify-content-between align-items-center text-white">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-2 rounded">
            <li class="breadcrumb-item">
                <a href="../layout/home.php" style="text-decoration:none; color:#007bff;">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Fee Details Page</li>
        </ol>
    </nav>
</div>


<div class="card shadow-lg border-0" style="width:80rem; box-shadow: 0 0 5px 0.5px rgba(0, 0, 0, 0.2);">
    <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color:#343a40;">
        <h5 class="card-title mb-0">Fee Details</h5>
        <a href="../pages/fees.php" class="fa fa-times text-white" style="text-decoration:none; font-size:20px"></a>
  </div>
  <div class="card-body">
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Student Name:</h4>
        <p><?php echo htmlspecialchars($data['fname']." ".$data['lname'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Fee Type:</h4>
        <p><?php echo htmlspecialchars($data['fee_type'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Due Date:</h4>
        <p><?php echo htmlspecialchars($data['due_date'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Amount Due:</h4>
        <p><?php echo htmlspecialchars($data['amount_due'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Amount Paid:</h4>
        <p><?php echo htmlspecialchars($data['amount_paid'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Balance:</h4>
        <p><?php echo htmlspecialchars($data['balance'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Payment Status:</h4>
        <p><?php echo htmlspecialchars($data['payment_status'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Payment Method:</h4>
        <p><?php echo htmlspecialchars($data['payment_method'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Receipt Number:</h4>
        <p><?php echo htmlspecialchars($data['receipt_number'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
        <div class="card-col">
            <h4>Remark :</h4>
            <p><?php echo htmlspecialchars($data['remarks'] ?? ""); ?></p>
        </div>
        <div class="card-col">
            <h4>Created At:</h4>
            <p><?php echo htmlspecialchars($data['created_at'] ?? ""); ?></p>
        </div>
        <div class="card-col">
            <h4>Updated At:</h4>
            <p><?php echo htmlspecialchars($data['updated_at'] ?? ""); ?></p>
        </div>
    </div>
  </div>
</div>


<style>
    table {
        font-size: 14px;
        margin: 0 auto;
        width: 100%;
        padding:5px;
    }

    th{
        background:#343a40;
        color:white;
        padding: 0px 5px;
    }
    td {
        border: 1px solid #ddd;
        text-align: left;
        padding: 2px 10px;
        width: 25%;
    }
    tr {
       border-bottom: 1px solid #ddd;
    }

    tr:nth-child(even) {
       background-color: #f2f2f2;
    }
</style>

<div class="card shadow-lg border-0 mt-3" style="width:80rem;">
  <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color:#343a40;">
    <h5 class="card-title mb-0">Receipt Details</h5>
  </div>
  <div class="card-body">
    <div class="card-row mb-3">
      <table>
        <thead>
          <tr>
            <th>Receipt Id</th>
            <th>Student Id</th>
            <th>Fee Id</th>
            <th>Receipt PDF</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($receipt as $item):?>
          <tr>
            <td><?php echo $item['id'] ?? "" ?></td>
            <td><?php echo $item['student_id'] ?? "" ?></td>
            <td><?php echo $item['fee_id'] ?? "" ?></td>
            <td><a href="<?php echo $item['fee_receipt'] ?? "" ?>" style="text-decoration:none">View Receipt</a></td>    
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
    require_once('../layout/footer.php');
 }else{ 
  header('location:404.php');
}
?>