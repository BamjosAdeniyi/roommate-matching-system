<?php
// Include session manager or session start logic
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Keep the session alive
$sessionLifetime = 10000; // 30 minutes

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $sessionLifetime)) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
} else {
    $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp
}

// Regenerate session ID
session_regenerate_id(true);
?>
