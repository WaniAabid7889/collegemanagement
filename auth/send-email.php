<?php
session_start();
    require_once("../lib/SQLService.php");
    $conn = new SQLService();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';


    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $sql = "UPDATE users SET reset_token = :hashToken, token_expires = :expire WHERE email = :email";
    $prt = [
        ':email' => $email,
        ':hashToken' => $token_hash,
        ':expire' => $expiry
    ];

    try {
        $rest = $conn->Query($sql, $prt);
        if (!$rest) {    
            http_response_code(500);
            echo "Database query error";
            exit;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Database connection error";
        exit;
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'waniabid645@gmail.com';                     //SMTP username
        $mail->Password   = 'xrnsliygkxawhvqy';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        //Recipients
        $mail->setFrom('waniabid645@gmail.com', 'Aabid Hussain');
        $mail->addAddress($email, 'Joe User');     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'New Inquiry - Reset Password';
        $mail->Body    = '
        <h3>Hello, you got a new Inquiry</h3>
        <h3>Email: '.$email.'</h3>
        Click <a href="http://localhost/collegemanagement/authentication/reset-password.php?token='.$token_hash.'">here</a> to reset your password';            

        if($mail->send()){
            $_SESSION['status'] = 'Thank you for contacting us - Aabid Hussain';
            header("Location:{$_SERVER["HTTP_REFERER"]}");
            exit(0);
        } else {
            $_SESSION['status'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            header("Location:{$_SERVER["HTTP_REFERER"]}");
            exit(0);
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        exit(0);
    }
} else {
    header('Location:login.php');
    exit(0);
}
?>
