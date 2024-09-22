<?php
 session_start();
 if(isset($_SESSION['username']) && isset($_SESSION['password']))
 {
    require_once('../layout/header.php');
    require_once('../models/student-model.php');
    $studentObj = new studentModel();  
    $id = $_GET['id'];
    $data = $studentObj->getStudentById($id);
 
    
?>
<div class="d-flex justify-content-between align-items-center text-white">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-2 rounded">
            <li class="breadcrumb-item">
                <a href="../layout/home.php" style="text-decoration:none; color:#007bff;">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Student Details Page</li>
        </ol>
    </nav>
</div>


<div class="card border-0" style="width:80rem; box-shadow: 0 0 5px 0.5px rgba(0, 0, 0, 0.2);">
    <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color:#343a40;">
        <h5 class="card-title mb-0">Student Details</h5>
        <a href="../pages/students.php" class="fa fa-times text-white" style="text-decoration:none; font-size:20px"></a>
  </div>
  <div class="card-body">
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>First Name:</h4>
        <p><?php echo htmlspecialchars($data['fname'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Last Name:</h4>
        <p><?php echo htmlspecialchars($data['lname'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Gender:</h4>
        <p><?php echo htmlspecialchars($data['gender'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Date of Birth:</h4>
        <p><?php echo htmlspecialchars($data['dob'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Email:</h4>
        <p><?php echo htmlspecialchars($data['email'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Phone:</h4>
        <p><?php echo htmlspecialchars($data['phone_number'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>State:</h4>
        <p><?php echo htmlspecialchars($data['state'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>City:</h4>
        <p><?php echo htmlspecialchars($data['city'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Country:</h4>
        <p><?php echo htmlspecialchars($data['country'] ?? ""); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
        <div class="card-col">
            <h4>Address:</h4>
            <p><?php echo htmlspecialchars($data['address'] ?? ""); ?></p>
        </div>
        <div class="card-col">
            <h4>Zip Code:</h4>
            <p><?php echo htmlspecialchars($data['zip_code'] ?? ""); ?></p>
        </div>
        <div class="card-col">
            <h4>Admission Date:</h4>
            <p><?php echo htmlspecialchars($data['admission_date'] ?? ""); ?></p>
        </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Session:</h4>
        <p><?php echo htmlspecialchars($data['name'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Category:</h4>
        <p><?php echo htmlspecialchars($data['category'] ?? ""); ?></p>
      </div>
      <div class="card-col">
        <h4>Image:</h4>
        <img src="../uploads/<?php echo htmlspecialchars($data['image'] ?? ""); ?>" class="img-fluid rounded-circle" style="height: 50px; width: 50px;" />
      </div>
    </div>
    <!-- <div class="card-row mb-3">
      <div class="card-col">
        <h4>Course:</h4>
            <?php echo htmlspecialchars($data['course_name'] ?? ""); ?>
      </div>
      <div class="card-col">
        <h4>Enrollment Date:</h4>
         <?php echo htmlspecialchars($data['enrol_date'] ?? ""); ?>
      </div>
      <div class="card-col">
        <h4>Status:</h4>
        <span class="badge badge-<?php echo ($data['status'] == 'Enrolled') ? 'success' : 'danger'; ?>">
          <?php echo htmlspecialchars($data['status']); ?>
        </span>
      </div> -->
    </div>
  </div>
</div>

<?php
    // require_once('../layout/footer.php');
 }else{ 
  header('location:404.php');
}
?>