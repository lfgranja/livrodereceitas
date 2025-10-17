<?php
// logout.php
// User logout script

require_once 'auth/auth_functions.php';

// Perform logout
$result = logoutUser();

// Redirect to home page
header("Location: index.php");
exit();
?>