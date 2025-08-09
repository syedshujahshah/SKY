<?php
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skyscanner Clone - Homepage</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
        }
        .header a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin: 0 15px;
        }
        .header a:hover {
            color: #f1c40f;
        }
        .search-box {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            margin-top: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .search-box h2 {
            color: #1e3c72;
            margin-bottom: 20px;
        }
        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .search-form input, .search-form select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            flex: 1;
            min-width: 150px;
        }
        .search-form button {
            padding: 12px 20px;
            background: #f1c40f;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-form button:hover {
            background: #d4ac0d;
        }
        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            .header {
                flex-direction: column;
                text-align: center;
            }
            .header a {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Skyscanner Clone</h1>
            <div>
                <?php if (isset($user)): ?>
                    <span>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</span>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="signup.php">Sign Up</a>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="search-box">
            <h2>Search Flights & Hotels</h2>
            <form class="search-form" action="search.php" method="POST">
                <select name="type" required>
                    <option value="flight">Flight</option>
                    <option value="hotel">Hotel</option>
                </select>
                <input type="text" name="departure_city" placeholder="Departure City" required>
                <input type="text" name="arrival_city" placeholder="Destination City" required>
                <input type="date" name="check_in_date" required>
                <input type="date" name="check_out_date">
                <input type="number" name="passengers" placeholder="Passengers" min="1" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
</body>
</html>
