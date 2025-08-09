<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $departure_city = $_POST['departure_city'];
    $arrival_city = $_POST['arrival_city'];
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'] ?? null;
    $passengers = $_POST['passengers'];

    // Save search if user is logged in
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("INSERT INTO saved_searches (user_id, type, departure_city, arrival_city, check_in_date, check_out_date, passengers) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $type, $departure_city, $arrival_city, $check_in_date, $check_out_date, $passengers]);
    }

    // Fetch results
    if ($type === 'flight') {
        $stmt = $pdo->prepare("SELECT * FROM flights WHERE departure_city = ? AND arrival_city = ?");
        $stmt->execute([$departure_city, $arrival_city]);
        $results = $stmt->fetchAll();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM hotels WHERE city = ?");
        $stmt->execute([$arrival_city]);
        $results = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Skyscanner Clone</title>
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
        .filters {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: #1e3c72;
        }
        .filters select, .filters input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .results {
            display: grid;
            gap: 20px;
        }
        .result-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            color: #1e3c72;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .result-card button {
            padding: 10px 20px;
            background: #f1c40f;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }
        .result-card button:hover {
            background: #d4ac0d;
        }
        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
            }
            .result-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    <script>
        function redirectToProvider(url) {
            window.location.href = url;
        }

        function sortResults() {
            let sortBy = document.getElementById('sort').value;
            let results = Array.from(document.getElementsByClassName('result-card'));
            results.sort((a, b) => {
                let aValue = parseFloat(a.dataset[sortBy]);
                let bValue = parseFloat(b.dataset[sortBy]);
                return sortBy === 'price' ? aValue - bValue : bValue - aValue;
            });
            let container = document.getElementById('results');
            container.innerHTML = '';
            results.forEach(result => container.appendChild(result));
        }

        function filterResults() {
            let maxPrice = document.getElementById('max-price').value || Infinity;
            let minRating = document.getElementById('min-rating').value || 0;
            let results = document.getElementsByClassName('result-card');
            for (let result of results) {
                let price = parseFloat(result.dataset.price);
                let rating = parseFloat(result.dataset.rating);
                result.style.display = (price <= maxPrice && rating >= minRating) ? 'flex' : 'none';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Search Results</h1>
            <a href="index.php" style="color: #f1c40f; text-decoration: none;">Back to Home</a>
        </div>
        <div class="filters">
            <select id="sort" onchange="sortResults()">
                <option value="price">Cheapest</option>
                <option value="duration">Fastest</option>
                <option value="rating">Best Rated</option>
            </select>
            <input type="number" id="max-price" placeholder="Max Price" oninput="filterResults()">
            <input type="number" id="min-rating" placeholder="Min Rating" step="0.1" max="5" oninput="filterResults()">
        </div>
        <div class="results" id="results">
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $result): ?>
                    <div class="result-card" data-price="<?php echo $result['price'] ?? $result['price_per_night']; ?>" data-duration="<?php echo $result['duration'] ?? 0; ?>" data-rating="<?php echo $result['rating']; ?>">
                        <?php if ($type === 'flight'): ?>
                            <div>
                                <h3><?php echo htmlspecialchars($result['airline']); ?></h3>
                                <p><?php echo htmlspecialchars($result['departure_city']); ?> to <?php echo htmlspecialchars($result['arrival_city']); ?></p>
                                <p>Departure: <?php echo $result['departure_time']; ?></p>
                                <p>Duration: <?php echo $result['duration']; ?> min</p>
                                <p>Stops: <?php echo $result['stops']; ?></p>
                                <p>Price: $<?php echo $result['price']; ?></p>
                                <p>Rating: <?php echo $result['rating']; ?>/5</p>
                            </div>
                            <button onclick="redirectToProvider('https://example.com/book/flight/<?php echo $result['id']; ?>')">Book Now</button>
                        <?php else: ?>
                            <div>
                                <h3><?php echo htmlspecialchars($result['name']); ?></h3>
                                <p>City: <?php echo htmlspecialchars($result['city']); ?></p>
                                <p>Price per Night: $<?php echo $result['price_per_night']; ?></p>
                                <p>Rating: <?php echo $result['rating']; ?>/5</p>
                            </div>
                            <button onclick="redirectToProvider('<?php echo htmlspecialchars($result['provider_url']); ?>')">Book Now</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
