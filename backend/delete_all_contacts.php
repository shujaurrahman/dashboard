<?php
session_start();
require "../assets/config.php"; // Adjust path as needed

// Check if the user is logged in
if (!isset($_SESSION['admin_username'])) {
    // If the session is not set, redirect to the login page
    header("Location: ../index.php");
    exit();
}

// Prepare the SQL DELETE statement
$delete_query = "DELETE FROM contact";

// Execute the query
if (mysqli_query($conn, $delete_query)) {
    // Redirect back to the dashboard with a success message
    header("Location: ../dashboard.php?delete=success");
} else {
    // Redirect back with an error message
    header("Location: ../dashboard.php?delete=error");
}

// Close the database connection
mysqli_close($conn);
?>
