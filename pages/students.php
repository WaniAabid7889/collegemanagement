 <?php
     session_start();
     if(isset($_SESSION['username']) && isset($_SESSION['password']))
     {
        require_once('../layout/header.php');
        require_once('../models/student-model.php');
        require_once('../models/enrollment-model.php');
        $enrollObj = new enrollmentModel();
        $studentObj = new studentModel();     
        $id="";
        if (isset($_POST['submit']))
        {
            $id = $_POST['studentId'];
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $zip_code = $_POST['zip_code'];
            $admission_date = $_POST['admission_date'];
            $academic_session_id = $_POST['academic_session_id'];
            $category = $_POST['category'];
            $country = $_POST['country'];
            $imageName = $_FILES['image']['name'];
            $course_id = $_POST['course_id'];
            $enroll_date = $_POST['enroll_date'];
            $status = $_POST['status'];
            $enrollId = $_POST['enrollId'];

            if ($imageName) {
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
            } else {
                $imageName = $_POST['existing_image']; 
                echo $imageName;
            }
            if ($id) {
                if ($imageName) {
                    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $imageName);
                } else {
                    $imageName = $_POST['existing_image']; 
                }
                $result = $studentObj->updateStudent(
                    $id, $fname, $lname, $dob, $gender, $email, $phone_number,
                    $address, $city, $state, $zip_code, $admission_date, $academic_session_id,
                    $category, $imageName, $country
                );
                
                if ($result) {
                    if($enrollId){
                        $res = $enrollObj->updateEnrollment($enrollId,$id,$course_id,$enroll_date,$status);
                        $_SESSION['toast_message'] = "Student updated successfully!";
                    }else{
                        $rs = $enrollObj->addEnrollment($id,$course_id,$enroll_date,$status);
                        $res = $enrollObj->updateEnrollment($rs,$id,$course_id,$enroll_date,$status);
                        $_SESSION['toast_message'] = "Student updated successfully!";
                    }
                    
                } else {
                    echo "<script>alert('Error updating record.');</script>";
                }
            } else {
             
                $result = $studentObj->addStudent(
                    $fname, $lname, $dob, $gender, $email, $phone_number,
                    $address, $city, $state, $zip_code, $admission_date, $academic_session_id,
                    $category, $imageName, $country); 
                if ($result) {
                    $stId = $result;
                    $res = $enrollObj->addEnrollment($stId,$course_id,$enroll_date,$status);
                    if($res){
                        $_SESSION['toast_message'] = "Student added successfully!";
                    } else {
                        echo "<script>alert('Error adding record.');</script>";
                    }
                } else {
                    echo "<script>alert('Error adding record.');</script>";
                }
            }
        }


        //Get All Students Data
        $data = $studentObj->getAllStudents();


        //get All Sessions Data
        $sessionData =$studentObj->getAllSessionData();

        //get All courses Data
        $courseData =$enrollObj->getCourses();

   
        // Deleting records
        if (isset($_REQUEST['id']) && isset($_REQUEST['delete'])) 
        {
            $id = $_REQUEST['id'];
            $stmt = $studentObj->deleteStudent($id);
            if ($stmt) {
                echo "<script>alert('Record deleted successfully'); window.location='students.php';</script>";
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
                        <li class="breadcrumb-item"><a style="text-decoration:none"  href="../pages/students.php">Students</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                    </ol>
                </nav>
                <div class="float-end">
                    <!-- <button class="btn btn-success btn-sm mb-3"><i class="fa fa-list" aria-hidden="true"></i><a style="text-decoration:none;color:white" href="enrollments.php"> Enrollment</a></button> -->
                    <!-- <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#enrollModal"><i class="fa fa-plus" aria-hidden="true"></i> Enrollment</button> -->
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#studentModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Student</button>
                </div>
            </div>
            <table id="studentTable" class="table table-bordered table-striped">
                <thead> 
                    <tr style="background-color:#00000A; color:white;">
                        <th style="background-color:#00000A; color:white;">Id</th>
                        <th style="background-color:#00000A; color:white;">Name</th>
                        <th style="background-color:#00000A; color:white;">Course</th>
                        <th style="background-color:#00000A; color:white;">Enrollment Date</th>
                        <th style="background-color:#00000A; color:white;">Sessions</th>
                        <th style="background-color:#00000A; color:white;">Status</th>
                        <th style="background-color:#00000A; text-align:center; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><a href="../detail-pages/student-details.php?id=<?php echo $item['id'];?>" style="text-decoration:none"><?php echo $item['fname']; ?></a></td>
                            <td><?php echo $item['course_name']; ?></td>
                            <td><?php echo $item['enrol_date']; ?></td>
                            <td><?php echo $item['session_name']; ?></td>  
                            <td>
                                <div class="card-col">
                                    <span class="badge badge-<?php echo ($item['status'] == 'Enrolled') ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($item['status']); ?>
                                    </span>
                                </div>
                            </td>
                            <!-- <td><img src="../uploads/<?php echo $item['image']; ?>" height="30px" width="75px" /></td> -->
                            <td style="text-align:center;">
                                <a href="javascript:void(0);" class="btn btn-sm update-student text-success" data-id="<?php echo $item['id']; ?>" data-bs-toggle="modal" data-bs-target="#studentModal"> 
                                    <i class="fas fa-edit"></i></a>
                                    <a href="students.php?id=<?php echo $item['id']; ?>&delete=1" class="btn btn-sm text-danger delete-student">
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
#studentTable {
    /* border: none;  */
}

#studentTable th, #studentTable td {
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
<div class="modal fade" id="studentModal" aria-labelledby="studentModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentModalLabel">Add Student</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="studentForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="hidden" name="studentId" id="studentId">
                    <input type="hidden" name="existing_image" id="existingImage">
                    <input type="hidden" name="enrollId" id="enrollId">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="fname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" 
                                    placeholder="Enter first name" required 
                                    pattern="[A-Za-z\s]+" 
                                    title="First name should only contain letters and spaces.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="lname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" 
                                    placeholder="Enter last name" required pattern="[A-Za-z\s]+" 
                                    title="Last name should only contain letters and spaces.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label class="form-label">Gender</label>
                            <div class="d-flex">
                                <div class="form-check me-2">
                                    <input class="form-check-input" type="radio"
                                        name="gender" id="genderMale" value="male" required>
                                        <label class="form-check-label" for="genderMale">Male</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender"
                                        id="genderFemale" value="female" required>
                                        <label class="form-check-label" for="genderFemale">Female</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" 
                                id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone_number"
                                name="phone_number" pattern="\d[0-9]{9}" placeholder="Enter phone number" 
                                required title="Phone number should be 10 digits.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address"
                                placeholder="Enter address" required></textarea>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" 
                                name="city" placeholder="Enter city" required pattern="[A-Za-z\s]+" 
                                title="City should only contain letters and spaces.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" 
                                placeholder="Enter state" required pattern="[A-Za-z\s]+" 
                                title="State should only contain letters and spaces.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" 
                                name="zip_code" placeholder="Enter zip code" required pattern="\d{6}" 
                                title="Zip code should be a 5-digit number.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" 
                                name="country" placeholder="Enter country" 
                                required pattern="[A-Za-z\s]+" 
                                title="Country should only contain letters and spaces.">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="academic_session_id" class="form-label">Academic Session</label>
                            <select id="academic_session_id" name="academic_session_id" class="form-select">
                                <option value="" disabled selected>Select Academic Session</option>
                                <?php foreach($sessionData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="" disabled selected>Select Category</option>
                                <option value="General">General</option>
                                <option value="OBC">OBC</option>
                                <option value="SC">SC/ST</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                   </div>   
                    <div class="row mt-2">
                         <strong style="font-weight:600;font-size:16px">Fill Enrollment Details</strong> 
                        <hr>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="course_id" class="form-label">Course</label>
                            <select id="course_id" name="course_id" class="form-select">
                                <option value="" disabled selected>Select Course</option>
                                <?php foreach($courseData as $item): ?>
                                    <option value="<?php echo $item['id'];?>" data-fee="<?php echo $item['fee'];?>"><?php echo $item['name'];?></option>
                                <?php endforeach; ?>
                            </select>
                                <!-- <label class="mt-3" for="TotalFee" id="totalFee" style="display: none;">Total Fee</label>
                                <input type="text" class="form-control mt-3" id="courseFee" name="courseFee"  style="display: none;" readonly> -->
                        </div>

                        
                                   
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="Enrollment" class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" id="enroll_date" name="enroll_date">
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="" disabled selected>Select status</option>
                                <option value="Enrolled">Active</option>
                                <option value="Unenrolled">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <strong style="font-weight:600; font-size:16px">Fee Details</strong>
                        <hr>
                        <div class="col-md-6 col-sm-12 mb-3 mt-3">
                            <label class="mt-3" for="TotalFee" id="totalFee" style="display: none;">Total Fee</label>
                            <input type="text" class="form-control mt-3" id="courseFee" name="courseFee"  style="display: none;" readonly>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3 mt-3">
                            <label class="mt-3" for="TotalFee" id="" style="display: none;">Total Fee</label>
                        </div>
                    </div>
                    
                    <script>
                            document.getElementById('course_id').addEventListener('change', function() {
                                var selectedOption = this.options[this.selectedIndex];
                                var fee = selectedOption.getAttribute('data-fee');
                            
                                if (fee) {
                                    document.getElementById('courseFee').style.display = 'block'; 
                                    document.getElementById('courseFee').value = fee;
                                    document.getElementById('totalFee').style.display  = 'block';
                                    document.getElementById('')
                                } else {
                                    document.getElementById('courseFee').style.display = 'none';
                                    document.getElementById('courseFee').value = ''; 
                                }
                            });
                    </script>  

                    <button type="submit" name="submit" class="btn btn-primary float-end">Save Changes</button>
                    <button type="button" class="btn btn-secondary float-end mx-2" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#studentTable').DataTable({
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
        
        $('.update-student').on('click', function() {
            var studentId = $(this).data('id');
            console.log(studentId);
            $("#studentId").val(studentId);
            $.ajax({
                url: '../ajex/ajaxcall.php?function=studentById',
                type: 'GET',
                data: { id: studentId },
                success: function(response) {
                    console.log(response);
                    var student = JSON.parse(response);
                    $('#fname').val(student.fname);
                    $('#lname').val(student.lname);
                    $('#dob').val(student.dob);
                    $('#email').val(student.email);
                    $('#phone_number').val(student.phone_number);
                    $('#address').val(student.address);
                    $('#city').val(student.city);
                    $('#state').val(student.state);
                    $('#zip_code').val(student.zip_code);
                    $('#admission_date').val(student.admission_date);
                    $('#academic_session_id').val(student.academic_session_id);
                    $('#country').val(student.country);
                    $('#enroll_date').val(student.enrol_date);    
                    $('#enrollId').val(student.enrollId);
                    var imagePath = student.image;
                    console.log(imagePath);
                    if (imagePath) {
                        $('#studentImage').attr('src', imagePath);
                    }
                    // $('#course_id option[value="'+student.course_name+'"]').attr("selected","selected");
                    $('#status option[value="'+student.status+'"]').attr("selected", "selected");
                    $('#category option[value="'+student.category+'"]').attr("selected", "selected");

                    if (student.gender === 'Male' || student.gender === 'male') {
                        $('#genderMale').prop('checked', true);
                    } else {
                        $('#genderFemale').prop('checked', true);
                    }
                    console.log(student.course_name); 
                    var name = student.course_name.trim();
                    console.log(name);
                    $('#course_id option').each(function() {
                        // console.log('Option value:', $(this).val(), 'Option text:', $(this).text());
                        if ($(this).text().trim() === name) {
                            var courseName = $(this).val();
                            console.log(courseName);
                            $('#course_id option[value="' + courseName + '"]').attr("selected", "selected");
                        }
                    });
                    
                }
            });
        });
        
        $('.delete-student').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });     
</script>

<?php require_once('../layout/footer.php');
 }else{
    // header('location:../auth/login.php');   
      header('location:404.php');
}
?>
