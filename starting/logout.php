<?php
    session_start();
    // if (isset($_SESSION["user"])) header('location: login.php');
    // $user = $_SESSION["user"];
    // remove all session variables
    session_unset();

    // destroy
    session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/login1.css">
</head>
<body>
    <p>You have been successfully logged out from the system.</p>
    <a href="../homepage.php" id="logoutBtn"><i class="fa fa-power-off">Click here to go homepage</i></a><br>
    <a href="../starting/login.php" id="logoutBtn"><i class="fa fa-power-off">Click to go back to login</i></a>
</body>
</html>