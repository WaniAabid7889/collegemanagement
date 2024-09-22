<?php
session_start();
if(isset($_SESSION['username']))
{
require_once("../lib/SQLService.php");
$conn  = new SQLService();

$token = $_GET["token"];


if (empty($token)) {
    http_response_code(400);
    echo "Invalid token";
    exit;
}

// $token_hash = hash("sha256", $token);
// echo $token_hash;

$sql = "SELECT * FROM users WHERE reset_token = :token_hash";
$params = [
    ':token_hash' => $token
];



try {
    $stmt = $conn->Query($sql, $params);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo "<br>Token not found";
        exit;
    }

    $token_expires = strtotime($user["token_expires"]);
    if ($token_expires <= time()) {
        http_response_code(401);
        echo "Token has expired";
        exit;
    }

    // Proceed with the rest of the logic if the token is valid
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database connection error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            /* background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%); */
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .reset-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 400px;
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
        .reset-container h2 {
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
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
            padding-left: 45px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
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
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
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
            box-shadow: 0 0 10px rgba(24, 40, 72, 0.3);
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h2>Reset Password</h2>
        <form id="resetForm" method="post" action="">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <span class="form-icon"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
            </div>

            <div class="form-group">
                <span class="form-icon"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                <div id="error-message" class="error-message">Passwords do not match</div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var password = document.getElementById('password').value;
            var passwordConfirmation = document.getElementById('password_confirmation').value;
            var errorMessage = document.getElementById('error-message');

            if (password !== passwordConfirmation) {
                errorMessage.style.display = 'block'; // Show the error message
            } else {
                errorMessage.style.display = 'none'; // Hide the error message if passwords match
                
                // Call your PHP script to update the password
                $.ajax({
                    type: "POST",
                    url: "process-reset-password.php", // Use the same URL or specify a separate handler
                    data: {
                        password: password,
                        password_confirmation: passwordConfirmation,
                        token: "<?= htmlspecialchars($token) ?>"
                    },
                    success: function(response) {
                        // Show SweetAlert success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your password has been updated. Redirecting to login...',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            onClose: () => {
                                window.location.href = "login.php"; // Redirect to the login page
                            }
                        });
                    },
                    error: function() {
                        // Show SweetAlert error message if the AJAX request fails
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem updating your password. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>

<?php
}
?>

