<?php
// Start or resume a session
function startSession($id, $first_name, $last_name, $role) {
    session_start();
    $_SESSION['user_id'] = $id;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;
    $_SESSION['role'] = $role; // 1 = Super Admin, 2 = Admin
}

// Destroy a session (logout)
function endSession() {
    session_start();
    session_destroy();
}

// Check if a user is logged in
function isLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}
?>
