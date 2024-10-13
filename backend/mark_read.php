<?php
session_start();
require "../assets/config.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    // Update the status to 'read'
    $query = "UPDATE contact SET status = 'read' WHERE id = '$id'";
    mysqli_query($conn, $query);
}
?>
