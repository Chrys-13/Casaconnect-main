<?php
include '../db/connect.php';
include '../utils/session.php';
include 'role_control.php';

// Ensure only Super Admin can delete users
checkRole(1); // Check if the user's role is Super Admin

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "User deleted successfully!";
        header("Location: ../view/user_management.php");
    } else {
        echo "Error deleting user!";
    }

    $stmt->close();
}

$conn->close();
?>
