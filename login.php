<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        echo "<script>alert('Login successful!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Skyscanner Clone</title>
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
        }
        .login-box {
            background: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-box h2 {
            color: #1e3c72;
            text-align: center;
            margin-bottom: 20px;
        }
        .login-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-form button {
            width: 100%;
            padding: 12px;
            background: #f1c40f;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .login-form button:hover {
            background: #d4ac0d;
        }
        .login-form a {
            color: #1e3c72;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
        }
        .login-form a:hover {
            color: #f1c40f;
        }
        @media (max-width: 768px) {
            .login-box {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form class="login-form" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="signup.php">Don't have an account? Sign Up</a>
        </form>
    </div>
</body>
</html>
