<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    require_once('../layout/header.php');
    require_once('../models/fee-model.php');
    require_once('../models/student-model.php');

    $feeObj = new feeModel();
    $studentObj = new studentModel();   
   
    
    // echo $_GET['id'];
    $feeId="";
    if (isset($_POST['submit'])) {
        $id = $_POST['feeId'];
        $fee_type =$_POST['fee_type'];
        $student_id = $_POST['student_id'];
        $date = $_POST['date'];
        $amount = $_POST['amount_due'];
        $paid = $_POST['amount_paid'];
        $status = $_POST['status'];
        $method = $_POST['method'];
        $remark = $_POST['remark'];

        $params = array($id,$student_id,$fee_type,$date,$amount,$paid,$status,$method,$remark);
        if ($id) {
            $result = $feeObj->updateFee($params);
            if ($result) {
                $_SESSION['toast_message'] = "Fee details updated successfully!";
            } else {
                echo "<script>alert('Error updating record.');</script>";
            }
        } else {
            $result = $feeObj->addFee($params);
            if ($result) {
                $_SESSION['toast_message'] = "Fee details added successfully!";
            } else {
                echo "<script>alert('Error adding record.');</script>";
            }
        }
    }

    // Get all fees data
    $data = $feeObj->getAllFees();

    //Get All Student Data
    $students = $studentObj->getAllStudents();


?>

<script>
    <?php 
        if (isset($_SESSION['toast_message'])) {
            $toastMessage = $_SESSION['toast_message'];
            unset($_SESSION['toast_message']);
        }
    ?>
    document.addEventListener('DOMContentLoaded', function() {
        var toastHTML = `
            <div class="toast show position-fixed top-0 end-0 p-3" style="z-index: 11; color: black;">
                <div class="toast-header" style="background-color: #0056b3; color: white;">
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="toast-body">
                    <?php echo $toastMessage; ?>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', toastHTML);
    });
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-6 col-sm-3 mt-3">
            <div class="d-flex justify-content-between align-items-center text-white">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a style="text-decoration:none" href="../layout/home.php">Home</a></li>
                        <li class="breadcrumb-item"><a style="text-decoration:none" href="../pages/fees.php">Fees</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Details</li>
                    </ol>
                </nav>
                <div class="float-end">
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#feeModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Fee</button>
                </div>
            </div>
            <table id="feeTable" class="table table-bordered table-striped">
                <thead> 
                    <tr style="background-color:#00000A; color:white;">
                        <th style="background-color:#00000A; color:white;">Id</th>
                        <th style="background-color:#00000A; color:white;">Student</th>
                        <th style="background-color:#00000A; color:white;">Total Amount</th>
                        <th style="background-color:#00000A; color:white;">Paid</th>
                        <th style="background-color:#00000A; color:white;">Balance</th>
                        <th style="background-color:#00000A; color:white;">Fee Type</th>
                        <th style="background-color:#00000A; color:white;">Date</th>
                        <th style="background-color:#00000A; color:white;">Status</th>
                        <th style="background-color:#00000A; text-align:center; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><a href="../detail-pages/fee-details.php?id=<?php echo $item['id'];?>" style="text-decoration:none"><?php echo $item['fname']; ?></a></td>
                            <td><?php echo $item['amount_due']; ?></td>
                            <td><?php echo $item['amount_paid']; ?></td>
                            <td><?php echo $item['balance']; ?></td>
                            <td><?php echo $item['fee_type']; ?></td>
                            <td><?php echo $item['created_at']; ?></td>
                            <td>
                                <div class="card-col">
                                    <span class="badge badge-<?php echo ($item['payment_status'] == 'Paid') ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($item['payment_status']); ?>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <a href="payment.php?id=<?php echo $item['id']; ?>" class="btn btn-sm text-primary">Pay</a>

                                <a href="javascript:void(0);" class="btn btn-sm text-primary update-fee" data-id="<?php echo $item['id']; ?>" data-bs-toggle="modal" data-bs-target="#feeModal"> 
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="fee-receipt.php?id=<?php echo $item['id']; ?>" class="btn btn-sm text-success">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
input.form-control:valid {
    border-left: 4px solid #28a745;
}
input.form-control:invalid {
    border-left: 4px solid #dc3545; 
}
</style>


<div class="modal fade" id="feeModal" aria-labelledby="feeModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feeModalLabel">Add Fee</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="feeForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="hidden" name="feeId" id="feeId">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="student_id" class="form-label">Student</label>
                            <select id="student_id" name="student_id" class="form-select" required>
                                <option value="" disabled selected>Select Student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo $student['id']; ?>"><?php echo $student['fname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="fee_type" class="form-label">Fee Type</label>
                            <select id="fee_type" name="fee_type" class="form-select" required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Tuition Fee">Tuition Fee</option>
                                <option value="Examination Fee">Examination Fee</option>
                                <option value="Library Fee">Library Fee</option>
                                <option value="Hostel Fee">Hostel Fee</option>
                                <option value="Transport Fee">Transport Fee</option>
                                <option value="Miscellaneous Fee">Miscellaneous Fee</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="amount_due" class="form-label">Due Amount</label>
                            <input type="number" class="form-control" min="0" id="amount_due" name="amount_due" placeholder="Enter Due amount" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="amount_paid" class="form-label">Paid Amount</label>
                            <input type="number" class="form-control" min="0" id="amount_paid" name="amount_paid" placeholder="Enter Paid amount" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="method" class="form-label">Payment Method</label>
                            <select id="method" name="method" class="form-select" required>
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
            
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Paid">Paid</option>
                                <option value="Unpaid">Unpaid</option>
                                <option value="Partially Paid">Partially Paid</option>
                            </select>
                        </div>

                        
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="remark" class="form-label">Remark</label>
                            <textarea class="form-control" id="remark" name="remark" placeholder="Enter remark" required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary float-end">Save Changes</button>
                    <button type="button" class="btn btn-secondary float-end mx-2" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
#feeTable {
    /* border: none;  */
}

#feeTable th, #feeTable td {
    border: none; 
    font-size:12px;
}

</style>


<script>
    $(document).ready(function() {
        $('#feeTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true, 
            "aLengthMenu": [ [8, 10, 12, 15, 20, -1], [8, 10, 12, 15, "All"] ],
            "iDisplayLength": 12,   
            "language": {
                "search": "Search:", 
                "searchPlaceholder": "Search records"
            }
        });

        $('.update-fee').on('click', function() {
            var feeId = $(this).data('id');
            $("#feeId").val(feeId);
            console.log(feeId);
            $.ajax({
                url: '../ajex/ajaxcall.php?function=feeByPd',
                type: 'GET',
                data: { id: feeId },
                success: function(response) {
                    var fee = JSON.parse(response);
                    $("#student_id").val(fee.student_id);
                    $('#fee_type').val(fee.fee_type);
                    $("#amount_paid").val(fee.amount_paid);
                    $('#amount_due').val(fee.amount_due);
                    $()
                    $("#date").val(fee.due_date);
                    $("#status").val(fee.payment_status);
                    $('#remark').val(fee.remarks); 

                    var method = fee.payment_method.trim();
                    console.log(method);
                    $('#method option').each(function() {
                        // console.log('Option value:', $(this).val(), 'Option text:', $(this).text());
                        if ($(this).text().trim() === method) {
                            var methodName = $(this).val();
                            console.log(methodName);
                            $('#method option[value="' + methodName + '"]').attr("selected", "selected");
                        }
                    });
                }
            });
        });
    });

    function validateForm() {
        var amount = document.getElementById('amount').value;
        var date = document.getElementById('date').value;

        if (amount <= 0) {
            alert("Amount must be greater than zero.");
            return false;
        }

        if (!date) {
            alert("Date is required.");
            return false;
        }

        return true;
    }
</script>

<?php require_once('../layout/footer.php');
 }else{
      header('location:404.php');
}
?>
