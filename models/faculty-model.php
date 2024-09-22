<?php
require_once('../lib/SQLService.php');
class facultyModel {
    private $db;
    public function __construct() {
        $this->db = new SQLService();
    }

    public function addFaculty($params) {
        $query = "INSERT INTO `faculty`(`fname`, `lname`, `dob`, `gender`, `email`, `phone_number`, `address`, `city`, `state`, `zip_code`, `hire_date`, `department_id`, `qualification_id`,`status`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($params[1],$params[2],$params[3], $params[4], $params[5], $params[6], $params[7], $params[8], $params[9],$params[10], $params[11],$params[12],$params[13],$params[14],$params[15]);
        return $this->db->Query($query, $params);
      //$lastInsertedId = $this->db->InsertId();
      //return $lastInsertedId;
    }
    


    // Method to get all courses
    public function getALLFaculty() {
        $query = "SELECT faculty.*, departments.dept_name, qualification.qual_name from faculty
                inner join departments on faculty.department_id = departments.id
                inner join qualification on faculty.qualification_id = qualification.id
                ";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourses(){
        $query = "SELECT  * from courses";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStudents(){
        $query = "SELECT students.fname, students.id FROM students 
                LEFT JOIN enrollments  ON students.id = enrollments.student_id
                WHERE enrollments.student_id IS NUll";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    

    // Method to get a single course by ID
    public function getFacultyById($id) {
        $query = "SELECT courses.*, departments.dept_name, qualification.qual_name from courses 
                  inner join departments on courses.department_id = departments.id
                  inner join qualification on courses.qual_id = qualification.id 
                  where courses.id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    // Method to update a course's information
    public function updateFaculty($id,$student_id,$course_id,$enroll_date,$status) {
        $query = "UPDATE enrollments SET student_id = ?, course_id = ?,
            enrol_date = ?, status = ? WHERE id = ?";
         $params = array($student_id,$course_id, $enroll_date,$status,$id);
        return $this->db->Query($query, $params);
    }

    
    // public function updateFaculty($params){
    //     $query = "UPDATE students SET 
    //         enrollment_id = :enroll_id,
    //         course_id = :course
    //         WHERE id = :id";
    //     $params = [
    //         ':id' => $student_id,
    //         ':enroll_id' => $enroll_id,
    //         ':course' => $course_id
    //     ];

    //     return $this->db->Query($query,$params);
    // }

    // Method to delete a student
    public function deleteFaculty($id) {
        $query = "DELETE FROM enrollments WHERE id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt;
    }
}
?>
