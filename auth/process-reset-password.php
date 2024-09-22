
<?php
session_start();
if(isset($_SESSION['username']))
{
    require_once('../lib/SQLService.php');
    $conn = new SQLService();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];
        $token = $_POST['token'];

        // Fetch user by token
        $stmt = $conn->Query("SELECT * FROM users WHERE reset_token = '$token'");
        $user = $stmt->fetch_assoc();

        $query = "SELECT * FROM users WHERE reset_token = :token";
        $params = [
            ':token' => $token,
        ];

        $stmt = $this->db->Query($query, $params);
        $user =  $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user === null) {
            echo json_encode(['status' => 'error', 'message' => 'Token not found.']);
            exit;
        }

        if (strtotime($user['token_expires']) <= time()) {
            echo json_encode(['status' => 'error', 'message' => 'Token has expired.']);
            exit;
        }

        if ($password !== $password_confirmation) {
            echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters.']);
            exit;
        }

        if (!preg_match("/[a-z]/i", $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must contain at least one letter.']);
            exit;
        }

        if (!preg_match("/[0-9]/", $password)) {
            echo json_encode(['status' => 'error', 'message' => 'Password must contain at least one number.']);
            exit;
        }

        // $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update user password
        $update_sql = "UPDATE users SET passwords = '$password', reset_token = NULL, token_expires = NULL WHERE id = '{$user['id']}'";
        $conn->Query($update_sql);


        $query = "UPDATE users SET 
        passwords = :password,
        reset_token = :reset_token,
        token_expires = :expires_token,
        WHERE id = :id";
            
        $params = [
        ':id' => $id,
        ':password' => $password,
        ':reset_token' => null,
        ':token_expires' => null,
        ];

        return conn->Query($query, $params);

        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully.']);
    }
}
?>