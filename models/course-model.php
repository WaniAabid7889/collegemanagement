<?php
require_once('../lib/SQLService.php');
class courseModel {
    private $db;

    public function __construct() {
        $this->db = new SQLService();
    }

    public function addCourse($name, $code, $department_id, $credits, $description, $qual_id,$fee) {
        $query = "INSERT INTO courses (`name`, `course_code`, `department_id`, `credits`, `description`, `qual_id`, `fee`) 
                  VALUES (:name, :code, :department_id, :credits, :description, :qual_id, :fee)";
    
        $params = [
            ':name' => $name,
            ':code' => $code,
            ':department_id' => $department_id,
            ':credits' => $credits,
            ':description' => $description,
            ':qual_id' => $qual_id,
            ':fee'=> $fee
        ];
        
        return $this->db->Query($query, $params);
    }
    


    // Method to get all courses
    public function getAllCourses() {
        $query = "SELECT courses.*, departments.dept_name, qualification.qual_name from courses 
                inner join departments on courses.department_id = departments.id
                inner join qualification on courses.qual_id = qualification.id
                ";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDepartmentData(){
        $query = "SELECT * from departments";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllQualification(){
        $query = "SELECT * from qualification";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
   
    

    // Method to get a single course by ID
    public function getCourseById($id) {
        $query = "SELECT courses.*, departments.dept_name, qualification.qual_name from courses 
                  inner join departments on courses.department_id = departments.id
                  inner join qualification on courses.qual_id = qualification.id 
                  where courses.id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    

    // Method to update a course's information
    public function updateCourse($id, $name, $code, $department_id, $credits, $description, $qual_id, $fee) {
        $query = "UPDATE courses SET 
            name = :name,
            course_code = :code,
            department_id = :department_id,
            credits = :credits,
            description = :description,
            qual_id = :qual_id,
            fee = :fee
            WHERE id = :id";

    
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':code' => $code,
            ':department_id' => $department_id,
            ':credits' => $credits,
            ':description' => $description,
            ':fee' => $fee,
            ':qual_id' => $qual_id
        ];
        return $this->db->Query($query, $params);
    }
    

    

    // Method to delete a student
    public function deleteCourse($id) {
        $query = "DELETE FROM students WHERE id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt;
    }
}
?>
