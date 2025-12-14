<?php
session_start();

require_once 'config.php';

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to homepage
header("Location: index.php");
exit();
?>