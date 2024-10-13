<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "../assets/config.php";

// Check if email and password are provided
if (isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Query to check if admin exists
    $sql = "SELECT * FROM `admin` WHERE `email` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $admin['password'])) {
            // Start a session and store admin details
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['name'];

            echo "success";  // If successful, return success
        } else {
            echo "Incorrect password.";  // Password does not match
        }
    } else {
        echo "Admin not found.";  // Admin email does not exist
    }

    // Close the connection
    mysqli_close($conn);
} else {
    echo "Incomplete data provided.";  // Email or password not set
}
?>
