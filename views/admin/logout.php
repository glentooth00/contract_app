<?php
session_start(); // Start the session to access session variables

// Destroy all session data to log the user out
session_unset(); // Removes all session variables
session_destroy(); // Destroys the session

// Redirect to the login page or home page after logging out
header("Location:../../"); // Change "login.php" to your actual login page
exit();
?>
