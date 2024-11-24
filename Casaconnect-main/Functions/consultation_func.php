<?php

// Fetch available consultation slots
function getAvailableConsultationSlots($conn) {
    $stmt = $conn->prepare("SELECT id, date, time FROM booking_slots WHERE type = 'consultation' AND status = 'available'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Book a consultation slot
function bookConsultationSlot($conn, $slot_id, $user_id) {
    // Check if the slot is available
    $stmt = $conn->prepare("SELECT status FROM booking_slots WHERE id = ? AND type = 'consultation'");
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return "Error: Slot does not exist.";
    }

    $slot = $result->fetch_assoc();
    if ($slot['status'] !== 'available') {
        return "Error: Slot already booked.";
    }

    // Mark the slot as booked
    $stmt = $conn->prepare("UPDATE booking_slots SET status = 'booked', user_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $user_id, $slot_id);
    if ($stmt->execute()) {
        return "Consultation slot booked successfully!";
    }

    return "Error: Unable to book slot.";
}
?>
