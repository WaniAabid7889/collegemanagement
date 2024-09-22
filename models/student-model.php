<?php
require_once('../lib/SQLService.php');
// require_once('../models/enrollment-model.php');
        
class studentModel {
    private $db;
    private $enrollObj;
    public function __construct() {
        $this->db = new SQLService();
        // $this->enrollObj = new enrollmentModel();
    }

    public function addStudent($fname, $lname, $dob, $gender, $email, $phone_number, $address,
            $city, $state, $zip_code, $admission_date, $academic_session_id,
            $category, $image, $country) {
            $query = "INSERT INTO students (`fname`, `lname`, `dob`, `gender`, `email`, `phone_number`, 
                `address`, `city`, `state`, `zip_code`, `admission_date`, `academic_session_id`,
                `category`, `image`, `country`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $params = array($fname,$lname,$dob,$gender,$email,$phone_number,$address,$city,$state,$zip_code,$admission_date,$academic_session_id,$category,$image,$country);
        $this->db->query($query, $params);
        $lastInsertedId = $this->db->InsertId();
        return $lastInsertedId;
    }



    // Method to get all students
    public function getAllStudents() {
        // $query = "
        //         SELECT students.*, enrollments.status,enrollments.enrol_date, academic_sessions.name, courses.name as course_name FROM students 
        //         INNER JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
        //         inner join enrollments on students.id = enrollments.student_id 
        //         inner join courses on courses.id  = enrollments.course_id;
        // ";
        // $query = "SELECT students.* ,enrollments.status, enrollments.enrol_date, academic_sessions.name FROM students
        //     inner join academic_sessions on students.academic_session_id = academic_sessions.id
        //     LEFT JOIN enrollments  ON students.id = enrollments.student_id
        //     WHERE enrollments.student_id IS NUll or enrollments.status = 'Enrolled'";

        $query = "SELECT students.*, enrollments.status, enrollments.enrol_date, academic_sessions.name AS session_name, courses.name AS course_name
                    FROM students
                    INNER JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
                    LEFT JOIN enrollments ON students.id = enrollments.student_id
                    LEFT JOIN courses ON courses.id = enrollments.course_id
                    WHERE enrollments.student_id IS NULL OR enrollments.status = 'Enrolled'";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllSessionData(){
        $query = "select * from academic_sessions";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Method to get a single student by ID
    public function getStudentById($id) {
        // $query = "SELECT students.*, enrollments.*, academic_sessions.name, courses.name as course_name FROM students 
        //         INNER JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
        //         inner join enrollments on students.id = enrollments.student_id 
        //         inner join courses on courses.id  = enrollments.course_id
        //         WHERE students.id = :id";

        $query = "SELECT students.*, academic_sessions.name FROM students 
                  INNER JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
                  WHERE students.id =:id
                 ";

        // $query = "SELECT students.*, enrollments.status, enrollments.enrol_date, academic_sessions.name AS session_name, courses.name AS course_name
        //         FROM students
        //         INNER JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
        //         LEFT JOIN enrollments ON students.id = enrollments.student_id
        //         LEFT JOIN courses ON courses.id = enrollments.course_id
        //         WHERE enrollments.student_id IS NULL and enrollments.status = 'Enrolled' and students.id =:id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function updateStudent($id, $fname, $lname, $dob, $gender, $email, $phone_number, $address,
        $city, $state, $zip_code, $admission_date, $academic_session_id, $category, $image, $country) {
        $query = "UPDATE students SET fname = ?, lname = ?, dob = ?, gender = ?,
        email = ?, phone_number = ?, address = ?, city = ?, state = ?, zip_code = ?, 
        admission_date = ?, academic_session_id = ?, category = ?, image = ?, country = ? WHERE id = ?";

        $params = array($fname, $lname, $dob, $gender, $email, $phone_number, $address,
            $city, $state, $zip_code, $admission_date, $academic_session_id, $category, $image, $country, $id
        );

         return $this->db->Query($query, $params);
         
}



    // Method to delete a student
    public function deleteStudent($id) {
        $query = "DELETE FROM students WHERE id = :id";
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
        return $stmt;
    }

    public function getStudentId($email){
        $query = "SELECT students.id FROM students WHERE email: = ".$email;
        $params = [':email' => $email];
        $stmt = $this->db->Query($query,$params);
        return $stmt;
    }


    // public function getStudentAndEnrollById($id){
    //     $query = "SELECT students.*, enrollments.id as enrollId, academic_sessions.name, courses.name as course_name FROM students 
    //             LEFT JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
    //             LEFT join enrollments on students.id = enrollments.student_id 
    //             LEFT join courses on courses.id  = enrollments.course_id
    //             WHERE students.id is null or students.id=:id";
    //     $params = [':id' => $id];
    //     $stmt = $this->db->Query($query,$params);
    //     return $stmt;
    // }

    public function getStudentAndEnrollById($id) {
        $query = "SELECT students.*, enrollments.enrol_date, enrollments.status, enrollments.student_id, enrollments.id as enrollId, academic_sessions.name, courses.name as course_name 
                  FROM students 
                  LEFT JOIN academic_sessions ON students.academic_session_id = academic_sessions.id
                  LEFT JOIN enrollments ON students.id = enrollments.student_id 
                  LEFT JOIN courses ON courses.id = enrollments.course_id
                  WHERE students.id is null or students.id = :id"; // Fix the condition to match a valid student ID
        $params = [':id' => $id];
        $stmt = $this->db->Query($query, $params);
    
        // Fetch the result as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
}
?>
