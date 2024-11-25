<?php
// Database connection
include 'db_config.php'; // Separate config file with credentials

// Pagination setup
$limit = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch property listings
$query = "SELECT id, property_name, location, price, thumbnail FROM properties LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

$properties = $result->fetch_all(MYSQLI_ASSOC);

// Count total listings for pagination
$totalQuery = "SELECT COUNT(*) AS total FROM properties";
$totalResult = $conn->query($totalQuery);
$totalListings = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalListings / $limit);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Properties</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Property Listings</h1>
    <div class="property-grid">
        <?php foreach ($properties as $property): ?>
            <div class="property-card">
                <img src="<?= htmlspecialchars($property['thumbnail']) ?>" alt="Property Image">
                <h2><?= htmlspecialchars($property['property_name']) ?></h2>
                <p>Location: <?= htmlspecialchars($property['location']) ?></p>
                <p>Price: $<?= htmlspecialchars($property['price']) ?></p>
                <a href="property.php?id=<?= htmlspecialchars($property['id']) ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="explore.php?page=<?= $page - 1 ?>">Previous</a>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <a href="explore.php?page=<?= $page + 1 ?>">Next</a>
        <?php endif; ?>
    </div>
</body>
</html>
