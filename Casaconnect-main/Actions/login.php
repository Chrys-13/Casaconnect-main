<?php
// Import the configuration file
require 'connect.php';

// Start the session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['email']; // In the database, 'username' is used instead of 'email'
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'casaconnect');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Secure query to check user credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verify user credentials
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the password matches
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];

            // Redirect based on user role
            if ($user['user_role'] === 1) { // Admin role
                header("Location: dashboard.php");
                exit;
            } else { // Regular user role
                header("Location: home.php");
                exit;
            }
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No account found with that username.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CasaConnect</title>
    <link rel="stylesheet" href="../Css/front.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">CasaConnect</div>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
                <li><a href="dashboard.php">Administrator</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="login-section">
            <div class="form-container">
                <h2>Login</h2>
                <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
                <form id="login-form" class="form" action="" method="post">
                    <label for="login-email">Username:</label>
                    <input type="text" id="login-email" name="email" placeholder="Enter your username" required>

                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="password" placeholder="Enter your password" required>

                    <button type="submit" class="btn">Login</button>
                </form>
                <p class="toggle-link">
                    Don't have an account? <a href="register.php">Sign Up</a>
                </p>
            </div>
        </section>
    </main>
</body>

</html>
