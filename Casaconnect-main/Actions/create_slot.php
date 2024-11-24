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
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
