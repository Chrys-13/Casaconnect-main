<?php
include '../db/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $type = $_POST['type']; // consultation or valuation

    // Validate input
    if (!in_array($type, ['consultation', 'valuation'])) {
        echo "Error: Invalid slot type";
        exit;
    }

    // Insert slot into the database
    $stmt = $conn->prepare("INSERT INTO booking_slots (date, time, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $date, $time, $type);

    if ($stmt->execute()) {
        echo "Slot created successfully!";

        // Log the action
        $log_action = "Created a $type slot on $date at $time";
        $admin_id = 1; // Replace with dynamic admin ID from session or authentication system
        $log_stmt = $conn->prepare("INSERT INTO logs (admin_id, action, timestamp) VALUES (?, ?, NOW())");
        $log_stmt->bind_param("is", $admin_id, $log_action);

        if ($log_stmt->execute()) {
            echo " Log entry created successfully.";
        } else {
            echo " Error logging the action: " . $log_stmt->error;
        }

        $log_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
