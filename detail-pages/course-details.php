<?php
 session_start();
 if(isset($_SESSION['username']) && isset($_SESSION['password']))
 {
    require_once('../layout/header.php');
    require_once('../models/course-model.php');
    $courseObj = new courseModel();  
    $id = $_GET['id'];
    $data = $courseObj->getCourseById($id);
   
?>
<div class="d-flex justify-content-between align-items-center text-white">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb p-2 rounded">
            <li class="breadcrumb-item"> <a href="../layout/home.php" style="text-decoration:none; color:#007bff;">Home</a> </li>
            <li class="breadcrumb-item"> <a href="../pages/courses.php" style="text-decoration:none; color:#007bff;">Course</a></li>
            <li class="breadcrumb-item active" aria-current="page">Course Details Page</li>
        </ol>
    </nav>
</div>


<div class="card border-0" style="width:80rem; box-shadow: 0 0 5px 0.5px rgba(0, 0, 0, 0.2);">
    <div class="card-header d-flex justify-content-between align-items-center text-white" style="background-color:#343a40;">
        <h5 class="card-title mb-0">Course Details</h5>
        <a href="../pages/courses.php" class="fa fa-times text-white" style="text-decoration:none; font-size:20px"></a>
  </div>
  <div class="card-body">
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Course Name:</h4>
        <p><?php echo htmlspecialchars($data['name']); ?></p>
      </div>
      <div class="card-col">
        <h4>Course Code:</h4>
        <p><?php echo htmlspecialchars($data['course_code']); ?></p>
      </div>
      <div class="card-col">
        <h4>Department:</h4>
        <p><?php echo htmlspecialchars($data['dept_name']); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
      <div class="card-col">
        <h4>Credits:</h4>
        <p><?php echo htmlspecialchars($data['credits'])." months"; ?></p>
      </div>
      <div class="card-col">
        <h4>Total Fee:</h4>
        <p><?php echo htmlspecialchars($data['fee']); ?></p>
      </div>
      <div class="card-col">
        <h4>Qualification:</h4>
        <p><?php echo htmlspecialchars($data['qual_name']); ?></p>
      </div>
    </div>
    <div class="card-row mb-3">
    <div class="card-col">
        <h4>Description:</h4>
        <p><?php echo htmlspecialchars($data['description']); ?></p>
      </div>
    </div>
  </div>
</div>
<?php
    require_once('../layout/footer.php');
 }else{ 
  header('location:404.php');
} 
?>