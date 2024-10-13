<?php
session_start();
// Check if the session is already set for the admin
if (isset($_SESSION['admin_username']) && isset($_SESSION['admin_name'])) {
    header("Location: dashboard.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="./static/form.css">
</head>
<body>
    <section class="form login">
        <div id="login">
            <div id="formLogin">
                <h1>Sign In →
                </h1>
                <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="error-text"></div>
                    <div class="inputContainer">
                        <input type="text" class="inputLogin" placeholder=" " name="email">
                        <label class="labelLogin">Email</label>
                    </div>
                    <div class="inputContainer">
                        <input type="password" class="inputLogin" placeholder=" " name="password">
                        <label class="labelLogin">Password</label>
                    </div>
                    <div class="field button">
                        <input type="submit" class="submitButton" value="Sign In">
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
<script src="./js/login.js" defer></script>

</html>