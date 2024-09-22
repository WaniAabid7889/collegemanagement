
<?php
define("HOST", "localhost");
define("USER", "root");
define("PASS", "");
define("DATABASE", "collegemanagement");

class SQLService {
    private $conn;
    private $stmt;

    public function __construct($hst = HOST, $usr = USER, $paswd = PASS, $db = DATABASE) {
        try {
            $dsn = "mysql:host=$hst;dbname=$db;charset=utf8";
            $this->conn = new PDO($dsn, $usr, $paswd);
            // Set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function Query($query, $params = []) {
        try {
            $this->stmt = $this->conn->prepare($query);
            $this->stmt->execute($params);
            return $this->stmt;
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public function FetchObject() {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        } else {
            echo "Statement is not valid";
            return false;
        }
    }

    public function FetchArray() {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "Statement is not valid";
            return false;
        }
    }

    public function FetchRow() {
        if ($this->stmt) {
            return $this->stmt->fetch(PDO::FETCH_NUM);
        } else {
            echo "Statement is not valid";
            return false;
        }
    }

    public function NumRows() {
        if ($this->stmt) {
            return $this->stmt->rowCount();
        } else {
            return 0;
        }
    }

    public function AffectedRows() {
        return $this->stmt->rowCount();
    }

    public function InsertId() {
        return $this->conn->lastInsertId();
    }

    public function __destruct() {
        $this->stmt = null;
        $this->conn = null;
    }
}
?>





