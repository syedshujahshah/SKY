<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to access the dashboard.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM saved_searches WHERE user_id = ? ORDER BY search_date DESC");
$stmt->execute([$user_id]);
$searches = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_date DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Skyscanner Clone</title>
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
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .section {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            color: #1e3c72;
        }
        .section h2 {
            margin-top: 0;
        }
        .item {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }
        .item:last-child {
            border-bottom: none;
        }
        a {
            color: #f1c40f;
            text-decoration: none;
        }
        a:hover {
            color: #d4ac0d;
        }
        @media (max-width: 768px) {
            .section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Dashboard</h1>
            <a href="index.php">Back to Home</a> | <a href="logout.php">Logout</a>
        </div>
        <div class="section">
            <h2>Saved Searches</h2>
            <?php if (!empty($searches)): ?>
                <?php foreach ($searches as $search): ?>
                    <div class="item">
                        <p><strong>Type:</strong> <?php echo ucfirst($search['type']); ?></p>
                        <p><strong>From:</strong> <?php echo htmlspecialchars($search['departure_city']); ?></p>
                        <p><strong>To:</strong> <?php echo htmlspecialchars($search['arrival_city']); ?></p>
                        <p><strong>Check-in:</strong> <?php echo $search['check_in_date']; ?></p>
                        <?php if ($search['check_out_date']): ?>
                            <p><strong>Check-out:</strong> <?php echo $search['check_out_date']; ?></p>
                        <?php endif; ?>
                        <p><strong>Passengers:</strong> <?php echo $search['passengers']; ?></p>
                        <p><strong>Searched on:</strong> <?php echo $search['search_date']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No saved searches.</p>
            <?php endif; ?>
        </div>
        <div class="section">
            <h2>Booking History</h2>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="item">
                        <p><strong>Type:</strong> <?php echo ucfirst($booking['type']); ?></p>
                        <p><strong>Item ID:</strong> <?php echo $booking['item_id']; ?></p>
                        <p><strong>Booked on:</strong> <?php echo $booking['booking_date']; ?></p>
                        <p><a href="<?php echo htmlspecialchars($booking['provider_url']); ?>" target="_blank">View Booking</a></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
