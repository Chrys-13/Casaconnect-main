<?php
include '../utils/session.php';

// Function to check if a user has the required role
function checkRole($required_role) {
    session_start();
    if ($_SESSION['role'] > $required_role) { // If user role is greater (lower permissions), deny access.
        header("Location: ../view/login.php");
        exit;
    }
}
?>
