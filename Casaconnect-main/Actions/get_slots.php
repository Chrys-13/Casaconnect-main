<?php
include '../db/connect.php';
include '../functions/consultation_func.php';
include '../functions/prop_val_func.php';

$type = $_GET['type']; // Get the slot type from query parameters

// Validate input
if (!in_array($type, ['consultation', 'valuation'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid slot type']);
    exit;
}

// Fetch available slots based on type
$slots = [];
if ($type === 'consultation') {
    $slots = getAvailableConsultationSlots($conn);
} elseif ($type === 'valuation') {
    $slots = getAvailableValuationSlots($conn);
}

header('Content-Type: application/json');
echo json_encode($slots);

$conn->close();
?>
