<?php
     session_start();
     if(isset($_SESSION['username']) && isset($_SESSION['password']))
     {
        require_once('../layout/header.php');
        require_once('../models/faculty-model.php');
       
        $factObj = new facultyModel();
         
        $id="";
        if (isset($_POST['submit']))
        {
            $id = $_POST['facultyId'];
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
            $hire_date = $_POST['hire_date'];
            $department_id = $_POST['department_id'];
            $qualification_id = $_POST['qualification_id'];
            $status = $_POST['status'];

            $params = array($id, $fname, $lname, $dob, $gender, $email, $phone_number,
            $address, $city, $state, $zip_code, $hire_date, $department_id, $qualification_id, $status);
            if ($id) {
                $result = $factObj->updateFaculty($params);
                if ($result) {
                    $_SESSION['toast_message'] = "Faculty updated successfully!";
                } else {
                    $_SESSION['toast_message'] = "Faculty is not updated successfully!";
                }
            } else {
                $result = $factObj->addFaculty($params); 
                if ($result) {
                    $_SESSION['toast_message'] = "Faculty added successfully!";
                } else {
                    $_SESSION['toast_message'] = "Faculty not added successfully!";
                }
            }
        }


        //Get All Students Data
        $data = $factObj->getAllFaculty();


        // //get All Sessions Data
        // $sessionData =$studentObj->getAllSessionData();

        // //get All courses Data
        // $courseData =$enrollObj->getCourses();

   
        // Deleting records
        if (isset($_REQUEST['id']) && isset($_REQUEST['delete'])) 
        {
            $id = $_REQUEST['id'];
            $stmt = $factObj->deleteFaculty($id);
            if ($stmt) {
                echo "<script>alert('Record deleted successfully'); window.location='faculties.php';</script>";
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
                        <li class="breadcrumb-item"><a style="text-decoration:none"  href="../pages/faculties.php">Faculty</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Faculty</li>
                    </ol>
                </nav>
                <div class="float-end">
                    <!-- <button class="btn btn-success btn-sm mb-3"><i class="fa fa-list" aria-hidden="true"></i><a style="text-decoration:none;color:white" href="enrollments.php"> Enrollment</a></button> -->
                    <!-- <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#enrollModal"><i class="fa fa-plus" aria-hidden="true"></i> Enrollment</button> -->
                    <button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#studentModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Faculty</button>
                </div>
            </div>
            <table id="studentTable" class="table table-bordered table-striped">
                <thead> 
                    <tr style="background-color:#00000A; color:white;">
                        <th style="background-color:#00000A; color:white;">Id</th>
                        <th style="background-color:#00000A; color:white;">Name</th>
                        <th style="background-color:#00000A; color:white;">Qualification</th>
                        <th style="background-color:#00000A; color:white;">Hired</th>
                        <th style="background-color:#00000A; color:white;">Department</th>
                        <th style="background-color:#00000A; color:white;">Status</th>
                        <th style="background-color:#00000A; text-align:center; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><a href="../detail-pages/faculty-details.php?id=<?php echo $item['id'];?>" style="text-decoration:none"><?php echo $item['fname']; ?></a></td>
                            <td><?php echo $item['qual_name']; ?></td>
                            <td><?php echo $item['hire_date']; ?></td>
                            <td><?php echo $item['dept_name']; ?></td>  
                            <td>
                                <div class="card-col">
                                    <span class="badge badge-<?php echo ($item['status'] == 'Active') ? 'success' : 'danger'; ?>">
                                        <?php echo htmlspecialchars($item['status']); ?>
                                    </span>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <a href="javascript:void(0);" class="btn btn-sm update-faculty text-success" data-id="<?php echo $item['id']; ?>" data-bs-toggle="modal" data-bs-target="#studentModal"> 
                                    <i class="fas fa-edit"></i></a>
                                    <a href="faculties.php?id=<?php echo $item['id']; ?>&delete=1" class="btn btn-sm text-danger delete-faculty">
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
#facultyTable {
    /* border: none;  */
}

#facultyTable th, #facultyTable td {
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
<div class="modal fade" id="facultyModal" aria-labelledby="facultyModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facultyModalLabel">Add Student</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="facultyForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <input type="hidden" name="facultyId" id="facultyId">
    
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
                            <label for="hire_date" class="form-label">Hire Date</label>
                            <input type="date" class="form-control" id="hire_date" name="hire_date" required>
                        </div>
                        <!-- <div class="col-md-6 col-sm-12 mb-3">
                            <label for="qualification_id" class="form-label">Qualification</label>
                            <select id="qualification_id" name="qualification_id" class="form-select">
                                <option value="" disabled selected>Select Qualification</option>
                                <?php foreach($qualificationData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['qual_name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div> -->

                        <!-- <div class="col-md-6 col-sm-12 mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select id="department_id" name="department_id" class="form-select">
                                <option value="" disabled selected>Select Department</option>
                                <?php foreach($departmentData as $item): ?>
                                    <option value="<?php echo $item['id'];?>"><?php echo $item['dept_name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div> -->
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="" disabled selected>Select status</option>
                                <option value="Active">active</option>
                                <option value="Un-Active">un-active</option>
                                
                            </select>
                        </div>
                   </div>   
                    <button type="submit" name="submit" class="btn btn-primary float-end">Save Changes</button>
                    <button type="button" class="btn btn-secondary float-end mx-2" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#facultyTable').DataTable({
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
            var facultyId = $(this).data('id');
            console.log(facultyId);
            $("#facultyId").val(facultyId);
            $.ajax({
                url: '../ajex/ajaxcall.php?function=facultyById',
                type: 'GET',
                data: { id: facultyId },
                success: function(response) {
                    console.log(response);
                    var faculty = JSON.parse(response);
                    $('#fname').val(faculty.fname);
                    $('#lname').val(faculty.lname);
                    $('#dob').val(faculty.dob);
                    $('#email').val(faculty.email);
                    $('#phone_number').val(faculty.phone_number);
                    $('#address').val(faculty.address);
                    $('#city').val(faculty.city);
                    $('#state').val(faculty.state);
                    $('#zip_code').val(faculty.zip_code);
                    $('#hire_date').val(faculty.hire_date);
                    $('#qualification_id').val(faculty.qualification_id);
                    $('#enroll_date').val(faculty.enrol_date);    
                    $('#department_id').val(faculty.department_id);
                 
                    $('#status option[value="'+faculty.status+'"]').attr("selected", "selected");
                  
                    if (faculty.gender === 'Male' || faculty.gender === 'male') {
                        $('#genderMale').prop('checked', true);
                    } else {
                        $('#genderFemale').prop('checked', true);
                    }
                    // console.log(faculty.course_name); 
                    // var name = student.course_name.trim();
                    // console.log(name);
                    // $('#course_id option').each(function() {
                    //     // console.log('Option value:', $(this).val(), 'Option text:', $(this).text());
                    //     if ($(this).text().trim() === name) {
                    //         var courseName = $(this).val();
                    //         console.log(courseName);
                    //         $('#course_id option[value="' + courseName + '"]').attr("selected", "selected");
                    //     }
                    // });
                    
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
