<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f8f8;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }

        .container {
            text-align: center;
        }

        .container h1 {
            font-size: 10rem;
            margin: 0;
            color: #ff6f61;
        }

        .container h2 {
            font-size: 2rem;
            margin: 20px 0;
            color: #333;
        }

        .container p {
            font-size: 1rem;
            margin: 20px 0;
            color: #666;
        }

        .container a {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: blue;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .container a:hover {
            background-color: #ff4a3a;
        }

        .container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>Oops! The page you are looking for does not exist. It might have been moved or deleted.</p>
        <a href="../auth/login.php">Go to Login</a>
    </div>
</body>
</html>
