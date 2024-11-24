<?php
include '../../db/connect.php';
include '../../functions/analytics_functions.php';

$total_users = getTotalUsers($conn);
$total_properties = getTotalProperties($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>Total Users: <?php echo $total_users; ?></p>
    <p>Total Properties: <?php echo $total_properties; ?></p>
    <a href="../user_management.php">Manage Users</a>
    <a href="../explore_properties.php">Explore Properties</a>
</body>
</html>
