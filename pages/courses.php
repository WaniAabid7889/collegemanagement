<?php
     session_start();
     if(isset($_SESSION['username']) && isset($_SESSION['password']))
     {
        require_once('../layout/header.php');
        require_once('../models/course-model.php');
        $courseObj = new courseModel();     
        if (isset($_POST['submit']))
        {
            $id = $_POST['courseId'];
            $name = $_POST['name'];
            $code = $_POST['course_code'];
            $department_id= $_POST['department_id'];
            $credits = $_POST['credits'];
            $description = $_POST['description'];
            $qual_id = $_POST['qualification_id'];
            $fee = $_POST['course_fee'];
    
            if ($id) {  
                $result = $courseObj->updateCourse($id,$name,$code,$department_id,$credits,$description,$qual_id,$fee);
                if ($result) {
                    $_SESSION['toast_message'] = "Course updated successfully!";
                } else {
                    echo "<script>alert('Error updating record.');</script>";
                }
            } else {
                $result = $courseObj->addCourse($name,$code,$department_id,$credits,$description,$qual_id,$fee);
                if ($result) {
                    $_SESSION['toast_message'] = "Course added successfully!";
                } else {
                    echo "<script>alert('Error adding record.');</script>";
                }
            }
        }


        //Get All Students Data
        $data = $courseObj->getAllCourses();

        //get All Department Data
        $departmentData =$courseObj->getAllDepartmentData();

        //get All Qualification Data
        $qualificationData = $courseObj->getAllQualification();

        // Deleting records
        if (isset($_REQUEST['id']) && isset($_REQUEST['delete'])) 
        {
            $id = $_REQUEST['id'];
            $stmt = $studentObj->deleteCourses($id);
            if ($stmt) {
                echo "<script>alert('Record deleted successfully'); window.location='courses.php';</script>";
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
                        <li class="breadcrumb-item"><a style="text-decoration:none"  href="../pages/courses.php">Courses</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Course</li>
                    </ol>
                </nav>
                <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#courseModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Course</button>
            </div>
            <table id="courseTable" class="table table-bordered table-striped">
                <thead> 
                    <tr style="background-color:#00000A; color:white;">
                        <th style="background-color:#00000A; color:white;">Id</th>
                        <th style="background-color:#00000A; color:white;">Name</th>
                        <th style="background-color:#00000A; color:white;">Course Code</th>
                        <th style="background-color:#00000A; color:white;" >Department</th>
                        <!-- <th  style="background-color:#00000A; color:white;">Durations</th> -->
                        <!-- <th style="background-color:#00000A; color:white;">Description</th> -->
                        <th style="background-color:#00000A; color:white;">Qualification</th>
                        <th style="background-color:#00000A; text-align:center; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><a href="../detail-pages/course-details.php?id=<?php echo $item['id'];?>" style="text-decoration:none"><?php echo $item['name']; ?></a></td>
                            <td><?php echo $item['course_code']; ?></td>
                            <td><?php echo $item['dept_name']; ?></td>
                            <!-- <td><?php echo $item['credits']; ?></td> -->
                            <!-- <td><?php echo $item['description']; ?></td> -->
                            <td><?php echo $item['qual_name']; ?></td>
                            <td style="text-align:center;">
                                <a href="javascript:void(0);" class="btn btn-sm text-success update-course" data-id="<?php echo $item['id']; ?>" data-bs-toggle="modal" data-bs-target="#courseModal"> 
                                    <i class="fas fa-edit"></i> </a>
                                    <a href="courses.php?id=<?php echo $item['id']; ?>&delete=1" class="btn btn-sm text-danger delete-course">
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
#courseTable {
    /* border: none;  */
}

#courseTable th, #courseTable td {
    border: none; 
    font-size:12px;
}

input.form-control:valid {
    border-left: 4px solid #28a745;
}
input.form-control:invalid {
    border-left: 4px solid #dc3545; 
}
</style>
<div class="modal fade" id="courseModal" aria-labelledby="courseModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Add Course</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="courseForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="hidden" name="courseId" id="courseId">
                    <input type="hidden" name="existing_image" id="existingImage">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="name" class="form-label">Course Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    placeholder="Enter course name" required 
                                    pattern="[A-Za-z\s]+" 
                                    title="First name should only contain letters and spaces.">
                        </div>
                       
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="course_code" class="form-label">Course Code</label>
                            <input type="type" class="form-control" id="course_code" name="course_code" placeholder="Enter course code" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select id="department_id" name="department_id" class="form-select">
                            <option value="" disabled selected>Select Department</option>
                                <?php foreach($departmentData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['dept_name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="credits" class="form-label">Duration</label>
                            <input type="number" class="form-control" 
                                id="credits" name="credits" placeholder="Enter duration" required>
                        </div>
                       
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="description" class="form-label">Total fee</label>
                            <input type="number" min="0" class="form-control" id="course_fee" name="course_fee"
                                placeholder="Enter course fee" required>
                        </div>
                       
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="qualification" class="form-label">Qualification</label>
                            <select id="qualification_id" name="qualification_id" class="form-select">
                                <option value="" disabled selected>Select Qualification</option>
                                <?php foreach($qualificationData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['qual_name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        
                        
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"
                                placeholder="Enter description" required></textarea>
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
        $('#courseTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true, 
            "pageLength": 10,
            "language": {
                "search": "Search:", 
                "searchPlaceholder": "Search records"
            }
        });

        $('.update-course').on('click', function() {
            var courseId = $(this).data('id');
            console.log(courseId);
            $("#courseId").val(courseId);
            $.ajax({
                url: '../ajex/ajaxcall.php?function=courseById',
                type: 'GET',
                data: { id: courseId },
                success: function(response) {
                    console.log(response);
                    var course = JSON.parse(response);
                    console.log(course);
                    $('#name').val(course.name);
                    $('#course_code').val(course.course_code);
                    // $('#department_id').val(course.dept_name);
                    $('#credits').val(course.credits);
                    $('#description').val(course.description);
                    // $('#qualification_id').val(course.qual_name);   
                    $('#course_fee').val(course.fee);

                    var name = course.qual_name.trim();
                    console.log(name);
                    $('#qualification_id option').each(function() {
                        // console.log('Option value:', $(this).val(), 'Option text:', $(this).text());
                        if ($(this).text().trim() === name) {
                            var qualName = $(this).val();
                            console.log(qualName);
                            $('#qualification_id option[value="' + qualName + '"]').attr("selected", "selected");
                        }
                    });


                    var dept_name = course.dept_name.trim();
                    console.log(name);
                    $('#department_id option').each(function() {
                        // console.log('Option value:', $(this).val(), 'Option text:', $(this).text());
                        if ($(this).text().trim() ===dept_name) {
                            var deptName = $(this).val();
                            console.log(deptName);
                            $('#department_id option[value="' + deptName + '"]').attr("selected", "selected");
                        }
                    });
                }
            });
        });
        
        $('.delete-course').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });     
</script>
<?php
    require_once('../layout/footer.php');
 }else{ 
  header('location:404.php');
}
?>