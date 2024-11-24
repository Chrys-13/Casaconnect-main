<?php
include '../db/connect.php';
include '../functions/consultation_func.php';
include '../functions/prop_val_func.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_id = $_POST['slot_id'];
    $user_id = $_POST['user_id']; // Replace with session user ID in production
    $type = $_POST['type']; // consultation or valuation

    // Validate input
    if (!in_array($type, ['consultation', 'valuation'])) {
        echo "Error: Invalid slot type.";
        exit;
    }

    // Book the slot based on type
    $result = "";
    if ($type === 'consultation') {
        $result = bookConsultationSlot($conn, $slot_id, $user_id);
    } elseif ($type === 'valuation') {
        $result = bookValuationSlot($conn, $slot_id, $user_id);
    }

    echo $result;
    $conn->close();
}
?>
