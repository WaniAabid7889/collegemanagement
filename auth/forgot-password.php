<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: absolute;
            left:37%;   
            top:25%;
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
        .container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .form-group label {
            margin-bottom: 20px;
        }
        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
            padding-left: 45px;
        }
        .form-control:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 10px rgba(75, 108, 183, 0.3);
        }
        .btn-primary {
            background-color: #4b6cb7;
            border-color: #4b6cb7;
            font-size: 18px;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #182848;
            border-color: #182848;
        }
        .alert {
            display: none;
            margin-top: 20px;
        }
        .text-center a {
            color: #6a11cb;
            margin-top: 15px;
            text-decoration: none;
            transition: color 0.3s;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="send-email.php" method="post">
            <div class="form-group">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" required>
            </div>
            <div class="form-group">
                <button type="submit"  class="btn btn-primary">Reset Password</button>
            </div>
            <div id="alert" class="alert alert-danger" role="alert">
                Invalid email address. Please try again.
            </div>
        </form>
        <p class="text-center"><a href="login.php">Back to Login</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (isset($_POST['submit'])): ?>
                $('#alert').show();
            <?php endif; ?>
        });
    </script>
</body>
</html>
