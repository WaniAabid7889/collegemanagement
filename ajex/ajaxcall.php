<?php
    require_once('../models/student-model.php');
    require_once('../models/course-model.php');
    require_once('../models/enrollment-Model.php');
    require_once('../models/fee-model.php');

    $studentObj = new studentModel();
    $courseObj  = new courseModel();
    $enrollObj  = new enrollmentModel();
    $feeObj     = new feeModel();

    if ($_REQUEST['function'] == 'studentById') {
        try {
            $id = $_REQUEST['id'];
            $response = $studentObj->getStudentAndEnrollById($id);
            echo json_encode($response);
        } catch (Exception $e) {
            var_dump($e);
        }
    }    
     
    else if($_REQUEST['function'] == 'courseById'){
        try{
            $id = $_REQUEST['id'];
            $response = $courseObj->getCourseById($id);
            echo json_encode($response);
        }catch(Exception $e){
            var_dump($e);
        }
    }

    else if($_REQUEST['function'] == 'enrollById'){
        try{
            $id = $_REQUEST['id'];
            $response = $enrollObj->getEnrollmentById($id);
            echo json_encode($response);
        }catch(Exception $e){
            var_dump($e);
        }
    }

    else if($_REQUEST['function'] == 'feeByPd'){
        try{
            $id = $_REQUEST['id'];
            $response = $feeObj->getFeeById($id);
            echo json_encode($response);
        }catch(Exception $e){
            var_dump($e);
        }
    }
    
?>