<?php
     session_start();
     if(isset($_SESSION['username']) && isset($_SESSION['password']))
     {
        require_once('../layout/header.php');
        require_once('../models/enrollment-model.php');
        
        $enrollObj = new enrollmentModel();
        
        
        if (isset($_POST['submit']))
        {
            $id = $_POST['enrollId'];
            $student_id = $_POST['student_id'];
            $course_id = $_POST['course_id'];
            $enroll_date = $_POST['enroll_date'];
            $status = $_POST['status'];
         
            if ($id) {  
                $result = $enrollObj->updateEnrollment($id,$student_id,$course_id,$enroll_date,$status);
                if ($result) {
                    $_SESSION['toast_message'] = "Enrollment updated successfully!";
                } else {
                    echo "<script>alert('Error updating record.');</script>";
                }
            } else {
                $result = $enrollObj->addEnrollment($student_id,$course_id,$enroll_date,$status);
                if ($result) {
                    $_SESSION['toast_message'] = "Enrollment added successfully!";
                } else {
                    echo "<script>alert('Error adding record.');</script>";
                }
            }
        }


        //Get All Students Data
        $data = $enrollObj->getAllEnrollments();

        //get All Department Data
        $courseData =$enrollObj->getCourses();

        //get All Qualification Data
        $studentData = $enrollObj->getStudents();

        // Deleting records
        if (isset($_REQUEST['id']) && isset($_REQUEST['delete'])) 
        {
            $id = $_REQUEST['id'];
            $stmt = $enrollObj->deleteEnroll($id);
            if ($stmt) {
                echo "<script>alert('Record deleted successfully'); window.location='enrollments.php';</script>";
            } else {
                echo "<script>alert('Error deleting record:');</script>";
            }
        }
?> 


<script>
    <?php 
        if (isset($_SESSION['toast_message']))
        {
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
                        <li class="breadcrumb-item"><a style="text-decoration:none"  href="../layout/home.php">Home</a></li>
                        <li class="breadcrumb-item"><a style="text-decoration:none"  href="../pages/enrollments.php">Enrollments</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Enrollment</li>
                    </ol>
                </nav>
                <!-- <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#enrollModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Enrollment</button> -->
                <a href="../public/students.php" class="fa fa-times text-black" style="text-decoration:none; font-size:20px"></a>
            </div>
            <table id="enrollTable" class="table table-bordered table-striped">
                <thead> 
                    <tr style="background-color:#00000A; color:white;">
                        <th style="background-color:#00000A; color:white;">Id</th>
                        <th style="background-color:#00000A; color:white;">Student Name</th>
                        <th style="background-color:#00000A; color:white;">Course Name</th>
                        <th style="background-color:#00000A; color:white;" >Enrollment Date</th>
                        <th style="background-color:#00000A; color:white;">Status</th>
                        <th style="background-color:#00000A; text-align:center; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['fname']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['enrol_date']; ?></td>
                            <td>
                                <div class="card-col">
                                    <span class="badge badge-<?php echo ($item['status'] == 'Enrolled') ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($item['status']); ?>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <a href="javascript:void(0);" class="btn btn-sm btn-success update-enroll" data-id="<?php echo $item['id']; ?>" data-bs-toggle="modal" data-bs-target="#enrollModal"> 
                                    <i class="fas fa-edit"></i> </a>
                                    <a href="enrollments.php?id=<?php echo $item['id']; ?>&delete=1" class="btn btn-sm btn-danger delete-enroll">
                                        <i class="fas fa-trash"></i>
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
<div class="modal fade" id="enrollModal" aria-labelledby="enrollModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enrollModalLabel">Add Course</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="enrollForm" method="POST" onsubmit="return validateForm()">
                    <input type="hidden" name="enrollId" id="enrollId">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="course" class="form-label">Student</label>
                            <select id="student_id" name="student_id" class="form-select">
                                <option value="" disabled selected>Select Student</option>
                                <?php foreach($studentData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['fname'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="qualification" class="form-label">Course</label>
                            <select id="course_id" name="course_id" class="form-select">
                            <option value="" disabled selected>Select Course</option>
                                <?php foreach($courseData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                       
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="Enrollment" class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" id="enroll_date" name="enroll_date" required>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="" disabled selected>Select status</option>
                                <option value="Enrolled">Active</option>
                                <option value="Unenrolled">Inactive</option>
                            </select>
                        </div>

                    </div>
                    <button type="submit" name="submit" class="btn btn-primary float-end">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#enrollTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true, 
            "pageLength": 10,
            "language": {
                "search": "Search:", 
                "searchPlaceholder": "Search records"
            }
        });

        $('.update-enroll').on('click', function() {
            var enrollId = $(this).data('id');
            console.log(enrollId);
            $("#enrollId").val(enrollId);
            $.ajax({
                url: '../ajex/ajaxcall.php?function=enrollById',
                type: 'GET',
                data: { id: enrollId },
                success: function(response) {
                    console.log(response);
                    var enroll = JSON.parse(response);
                    console.log(enroll);
                    $('#student_id').val(enroll.fname);
                    $('#course_id').val(enroll.name);
                    $('#enroll_date').val(enroll.enroll_date);
                    $('#status option[value="'+enroll.status+'"]').attr("selected", "selected");
                }
            });
        });
        
        $('.delete-enroll').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });     
</script>

<?php require_once('../layout/footer.php');
 } else { 
    header('location:404.php');
}
?>
