<?php
session_start();
require_once(__DIR__ . '/../lib/SQLService.php');
$conn = new SQLService();
if ($_SERVER['REQUEST_METHOD']=='POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = :email AND password = :password";
    $params = [
        ':email' => $email,
        ':password' => $password
    ];

    $result = $conn->Query($query, $params);
    $row = $result->fetchAll(PDO::FETCH_ASSOC);

    if ($row) {
         $username = $row[0]['username'];
         $password = $row[0]['password'];
         $_SESSION['username'] = $username;
         $_SESSION['password'] = $password;
    } else {
         echo "No user found with the provided email and password.";
    }

    if ($result) {
        $loginSuccess = true;
    } else {
        $loginSuccess = false;
    }

    echo '<input type="hidden" id="loginResult" value="' . ($loginSuccess ? 'success' : 'failed') . '">';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Management</title>
    <link rel="shortcut icon" type="image/x-icon" href="../uploads/logo.png"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="icon" href="../uploads/logo.jpg">
    <style>
        body {
            background-image: url('../uploads/collegeimage.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #ffffff;
            border-radius: 0 5px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            position: relative;
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .login-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-control {
            height: 50px;
            /* border-radius: 10px; */
            border: 1px solid #ddd;
            font-size: 16px;
            padding-left: 45px;

        }
        .form-control:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 10px rgba(75, 108, 183, 0.3);
        }
        .form-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 20px;
        }
        .btn-primary {
            background-color: #4b6cb7;
            border-color: #4b6cb7;
            font-size: 18px;
            font-weight: 600;
            padding: 12px;
            border-radius: 5px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #182848;
            border-color: #182848;
        }
        .forgot-password {
            margin-top: 15px;
        }
        .forgot-password a {
            color: #4b6cb7;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Welcome Back</h1>
    <form id="loginForm" method="POST" action="">
        <div class="form-group">
            <span class="form-icon"><i class="fas fa-user"></i></span>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <span class="form-icon"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login<i class="fa fa-arrow-right" style="position:relative; left:150px" aria-hidden="true"></i></button>
        <div class="forgot-password">
            <a href="forgot-password.php">Forgot Password?</a>
        </div>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var loginResult = document.getElementById('loginResult').value;

        if (loginResult === 'success') {
            Swal.fire({
                title: "Login Successful!",
                text: "You will be redirected to the home page.",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log(result.isConfirmed);
                    window.location.href = "../layout/home.php";
                }
            });
        } else if (loginResult === 'failed') {
            Swal.fire({
                title: "Login Failed",
                text: "Invalid username or password!",
                icon: "error",
                confirmButtonText: "Try Again"
            }).then(() => {
                window.location.href = "login.php";
            });
        }
    });
</script>
</body>
</html> 

