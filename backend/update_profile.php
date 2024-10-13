<?php
session_start();
require "../assets/config.php";

// Check if the user is logged in
if (!isset($_SESSION['admin_username'])) {
    // If the session is not set, redirect to the login page
    header("Location: ./index.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize form input
    $name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Get the current admin username from the session
    $current_username = $_SESSION['admin_username'];

    // If password is not empty, update it (hash it first), otherwise keep the old password
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $query = "UPDATE admin SET name='$name', username='$username', email='$email', password='$hashed_password' WHERE username='$current_username'";
    } else {
        // If no new password is provided, update other fields without changing the password
        $query = "UPDATE admin SET name='$name', username='$username', email='$email' WHERE username='$current_username'";
    }

    // Execute the update query
    if (mysqli_query($conn, $query)) {
        // Update session variables after successful update
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_username'] = $username;

        // Redirect back to the dashboard with a success message
        header("Location: ../dashboard.php?update=success");
        exit();
    } else {
        // Handle error in update query
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
