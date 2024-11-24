<?php
// Function to hash a password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify a hashed password
function verifyPassword($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}
?>
