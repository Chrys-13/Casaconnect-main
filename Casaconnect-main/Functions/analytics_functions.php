<?php
// Function to get the total number of users
function getTotalUsers($conn) {
    $result = $conn->query("SELECT COUNT(*) AS total FROM users");
    return $result->fetch_assoc()['total'];
}

// Function to get the total number of properties
function getTotalProperties($conn) {
    $result = $conn->query("SELECT COUNT(*) AS total FROM properties");
    return $result->fetch_assoc()['total'];
}

// Function to get the total number of Admins
function getTotalAdmins($conn) {
    $result = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 2");
    return $result->fetch_assoc()['total'];
}
?>
