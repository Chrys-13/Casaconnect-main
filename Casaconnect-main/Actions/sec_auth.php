<?php
// delete_user.php
include '../db/connect.php';
include '../utils/session.php';
include 'role_control.php';

function deleteUser($userId) {
    global $conn;
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Ensure only Super Admin can delete users
        checkRole(1);
        
        // Validate user exists
        $checkStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $checkStmt->bind_param("i", $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("User not found");
        }
        
        $userData = $result->fetch_assoc();
        
        // Prevent deletion of last super admin
        if ($userData['role'] === 1) {
            $superAdminCount = $conn->query("SELECT COUNT(*) FROM users WHERE role = 1")->fetch_row()[0];
            if ($superAdminCount <= 1) {
                throw new Exception("Cannot delete the last super admin");
            }
        }
        
        // Delete user
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $deleteStmt->bind_param("i", $userId);
        
        if (!$deleteStmt->execute()) {
            throw new Exception("Error deleting user");
        }
        
        $conn->commit();
        $_SESSION['success_message'] = "User deleted successfully";
        header("Location: ../view/user_management.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ../view/user_management.php");
        exit();
    }
}

if (isset($_POST['user_id']) && isset($_POST['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = "Invalid request";
        header("Location: ../view/user_management.php");
        exit();
    }
    deleteUser($_POST['user_id']);
}

// login.php
include '../db/connect.php';
include '../utils/session.php';
include '../functions/auth_functions.php';

function attemptLogin($email, $password) {
    global $conn;
    
    try {
        // Rate limiting check
        if (checkRateLimit($_SERVER['REMOTE_ADDR'])) {
            throw new Exception("Too many login attempts. Please try again later.");
        }
        
        // Fetch user by email
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows !== 1) {
            logFailedAttempt($_SERVER['REMOTE_ADDR']);
            throw new Exception("Invalid credentials");
        }
        
        $user = $result->fetch_assoc();
        
        if (!verifyPassword($password, $user['password'])) {
            logFailedAttempt($_SERVER['REMOTE_ADDR']);
            throw new Exception("Invalid credentials");
        }
        
        // Clear failed attempts on successful login.
        clearFailedAttempts($_SERVER['REMOTE_ADDR']);
        
        // Generating new session ID to prevent session fixation attacks if necessary and prevent users from accessing the session again later.
        session_regenerate_id(true);
        
        startSession($user['id'], $user['first_name'], $user['last_name'], $user['role']);
        
        // Set last login timestamp to current time.
        $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->bind_param("i", $user['id']);
        $updateStmt->execute();
        
        $_SESSION['success_message'] = "Welcome back, {$user['first_name']}!";
        header("Location: ../view/admin/dashboard.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ../view/login.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = "Invalid request";
        header("Location: ../view/login.php");
        exit();
    }
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (!$email || !$password) {
        $_SESSION['error_message'] = "All fields are required";
        header("Location: ../view/login.php");
        exit();
    }
    
    attemptLogin($email, $password);
}

// register.php will redirect you to the login page if you have registered previously and have not already logged in yet.
include '../db/connect.php';
include '../functions/auth_functions.php';

function registerUser($firstName, $lastName, $email, $password) {
    global $conn;
    
    try {
        $conn->begin_transaction();
        
        // Validate input
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }
        
        // Verifying if email already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("Email already exists");
        }
        
        // Hashing the user's password
        $hashedPassword = hashPassword($password);
        
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, active, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        $role = 2; // Default role: Admin
        $stmt->bind_param("ssssi", $firstName, $lastName, $email, $hashedPassword, $role);
        
        if (!$stmt->execute()) {
            throw new Exception("Error creating user account");
        }
        
        // Generate verification token
        $userId = $conn->insert_id;
        $token = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("INSERT INTO verification_tokens (user_id, token, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("is", $userId, $token);
        $stmt->execute();
        
        $conn->commit();
        
        // Send verification email
        sendVerificationEmail($email, $token);
        
        $_SESSION['success_message'] = "Registration successful! Please check your email to verify your account.";
        header("Location: ../view/login.php");
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ../view/register.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['error_message'] = "Invalid request";
        header("Location: ../view/register.php");
        exit();
    }
    
    $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (!$firstName || !$lastName || !$email || !$password) {
        $_SESSION['error_message'] = "All fields are required";
        header("Location: ../view/register.php");
        exit();
    }
    
    registerUser($firstName, $lastName, $email, $password);
}

// role_control.php
include '../utils/session.php';

function checkRole($requiredRole) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        $_SESSION['error_message'] = "Please log in to access this page";
        header("Location: ../view/login.php");
        exit();
    }
    
    if ($_SESSION['role'] > $requiredRole) {
        $_SESSION['error_message'] = "You don't have permission to access this page";
        header("Location: ../view/admin/dashboard.php");
        exit();
    }
    
    // Check if session is expired
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        $_SESSION['error_message'] = "Session expired. Please log in again";
        header("Location: ../view/login.php");
        exit();
    }
    
    $_SESSION['last_activity'] = time();
}

// Add these helper functions to auth_functions.php
function checkRateLimit($ip) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_row()[0];
    return $count >= 5;
}

function logFailedAttempt($ip) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, NOW())");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
}

function clearFailedAttempts($ip) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM login_attempts WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
}
 
function sendVerificationEmail($email, $token) {
    //  Email sending logic here is not yet implemented yet and will be soon.            
    // We can use PHPMailer or other email libraries
}