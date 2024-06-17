<?php
    // Start the session
    session_start();

    // Clear all session variables
    session_unset();
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the home page
    header('Location: index.php');
    exit;
?>