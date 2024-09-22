<?php
require_once('../lib/SQLService.php');
class feeModel {
    private $db;

    public function __construct() {
        $this->db = new SQLService();
    }

    public function addFee($params) {      
        $query = "INSERT INTO fees ( `student_id`, `fee_type`, `due_date`, `amount_due`, `amount_paid`, `payment_status`, `payment_method`, `remarks`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($params[1],$params[2],$params[3],$params[4],$params[5],$params[6],$params[7],$params[8]);
        return $this->db->Query($query, $params);   
    }

    // Method to get all addFee records
    public function getAllFees() {
        $query = "select fees.*,students.fname,students.lname from fees inner join students on fees.student_id = students.id";
        $stmt = $this->db->Query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to get a single fee details by ID
    public function getFeeById($id) {
        $query = "select fees.*,students.fname, students.lname, students.email, students.id as student_id from fees inner join students on fees.student_id = students.id where fees.id = ?";
        $params = array($id);
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Method to update a fee's information
    public function updateFee($params) {
        $query = "UPDATE fees SET student_id= ?, fee_type = ?, due_date = ?,
            amount_due = ?, amount_paid = ?, payment_status = ?, payment_method = ?, remarks = ?
            WHERE id = ?";
        $params = array($params[1],$params[2],$params[3],$params[4],$params[5],$params[6],$params[7],$params[8],$params[0]);
        return $this->db->Query($query, $params);
    }   
    
    public function updateFeePayment($date, $payment, $method, $id) {
        $query = "UPDATE fees SET due_date = ?, amount_paid = amount_paid + ?, payment_method = ? WHERE id = ?";
        $params = array($date, $payment, $method, $id);
        return $this->db->Query($query, $params);
    }
    
    
    public function receipt($student_id,$fee_id,$fee_receipt){
        $query = "INSERT INTO `receipts`(`student_id`, `fee_id`, `fee_receipt`)
         VALUES (?,?,?)";
        $params = array($student_id,$fee_id,$fee_receipt);
        return $this->db->Query($query,$params);
    }


    public function getReceiptById($id){
   
        $query = "SELECT * FROM `receipts` WHERE fee_id=?";
        $params = array($id);
        $stmt = $this->db->Query($query, $params);
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

   
}
?>
