<?php
require_once('../lib/SQLService.php');
class enrollmentModel {
    private $db;

    public function __construct() {
        $this->db = new SQLService();
    }

    public function addEnrollment($student_id,$course_id,$enroll_date,$status) {
        $query = "INSERT INTO enrollments (`student_id`, `course_id`, `enrol_date`, `status`) 
                  VALUES (?, ?, ?, ?)";
        $params = array($student_id,$course_id,$enroll_date, $status);
        $this->db->Query($query, $params);
        $lastInsertedId = $this->db->InsertId();
        return $lastInsertedId;
    }
    


    // Method to get all courses
    public function getAllEnrollments() {
        $query = "SELECT enrollments.*, students.fname, courses.name from enrollments
                inner join students on enrollments.student_id = students.id
                inner join courses on enrollments.course_id = courses.id
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
    public function getEnrollmentById($id) {
        $query = "SELECT courses.*, departments.dept_name, qualification.qual_name from courses 
                  inner join departments on courses.department_id = departments.id
                  inner join qualification on courses.qual_id = qualification.id 
                  where courses.id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    

    // Method to update a course's information
    public function updateEnrollment($id,$student_id,$course_id,$enroll_date,$status) {
        $query = "UPDATE enrollments SET student_id = ?, course_id = ?,
            enrol_date = ?, status = ? WHERE id = ?";
         $params = array($student_id,$course_id, $enroll_date,$status,$id);
        return $this->db->Query($query, $params);
    }

    
    public function updateStudent($student_id, $enroll_id, $course_id){
        $query = "UPDATE students SET 
            enrollment_id = :enroll_id,
            course_id = :course
            WHERE id = :id";
        $params = [
            ':id' => $student_id,
            ':enroll_id' => $enroll_id,
            ':course' => $course_id
        ];

        return $this->db->Query($query,$params);
    }

    // Method to delete a student
    public function deleteEnroll($id) {
        $query = "DELETE FROM enrollments WHERE id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt;
    }
}
?>
