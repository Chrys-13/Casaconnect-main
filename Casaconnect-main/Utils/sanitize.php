<?php
// Function to sanitize input
function sanitizeInput($conn, $input) {
    return htmlspecialchars($conn->real_escape_string($input));
}
?>
