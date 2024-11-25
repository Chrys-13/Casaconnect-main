<?php
// Database connection
include 'db_config.php';

// Validate and sanitize `id`
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid property ID.");
}

// Fetch property details
$query = "SELECT * FROM properties WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$property = $result->fetch_assoc();
if (!$property) {
    die("Property not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['property_name']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?= htmlspecialchars($property['property_name']) ?></h1>
    <div class="property-details">
        <div class="carousel">
            <?php foreach (explode(',', $property['images']) as $image): ?>
                <img src="<?= htmlspecialchars(trim($image)) ?>" alt="Property Image">
            <?php endforeach; ?>
        </div>
        <p>Location: <?= htmlspecialchars($property['location']) ?></p>
        <p>Price: $<?= htmlspecialchars($property['price']) ?></p>
        <p>Description: <?= htmlspecialchars($property['description']) ?></p>
    </div>
    <a href="explore.php">Back to Listings</a>
</body>
</html>
