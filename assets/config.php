<?php
$serverName = "";
$username = "";
$password = "";
$dataBase = "";


$conn  = mysqli_connect($serverName,$username,$password,$dataBase);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Insert querry for admin 

// $name = "Shuja ur Rahman";
// $email = "shujaurrehman210@gmail.com";
// $adminPassword = "Opssasur@1989";
// $username = "shujaurrahman";

// $passwordHash = password_hash($adminPassword,PASSWORD_DEFAULT);

// $sql = "INSERT INTO `admin` (`name`,`email`,`username`,`password`)
//         VALUES('$name','$email','$username','$passwordHash')";

// $result = mysqli_query($conn,$sql);

?>