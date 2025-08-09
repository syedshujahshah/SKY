<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Skyscanner Clone</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .logout-box {
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .logout-box h2 {
            color: #1e3c72;
            margin-bottom: 20px;
        }
        .logout-box a {
            color: #f1c40f;
            text-decoration: none;
            font-size: 16px;
        }
        .logout-box a:hover {
            color: #d4ac0d;
        }
    </style>
    <script>
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
    </script>
</head>
<body>
    <div class="logout-box">
        <h2>Logged Out</h2>
        <p>You have been logged out successfully. Redirecting to homepage...</p>
        <a href="index.php">Go to Homepage</a>
    </div>
</body>
</html>
