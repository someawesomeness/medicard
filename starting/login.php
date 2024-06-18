<?php 
    // Start the session.
    session_start();
    if (isset($_SESSION["user"])) header("location: ../starting/dashboard.php");

    $error_message = '';
    if ($_POST) {
        include('../connection.php');
        // var_dump($_POST);
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = 'SELECT * FROM adminstaffs WHERE adminstaffs.email="'.$username.'" AND adminstaffs.password="'. $password .'"';
        $stmt = $conn->prepare($query);
        $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];

        // captures data of currently login users.
        $_SESSION['user'] = $user;

        header('Location: dashboard.php');
        } else $error_message = 'Please make sure that username and password are correct.';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin</title>
    <link rel="stylesheet" href="../css/login1.css">
</head>
<body id="loginBody">
    <?php
    if (!empty($error_message)) { ?>
    
    <div id="errorMessage">
        <strong>ERROR: </strong><p><?= $error_message ?></p>
    </div>
    <?php } ?>
    <div class="conteiner">
        <div class="loginHeader">
            <h1>Medicard</h1>
            <p>Inventory Management System</p>
        </div>
        <div class="loginBody">
            <form action="login.php" method="POST">
                <div class="loginInput">
                    <label for="">Username</label>
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="loginInput">
                    <label for="">Password</label>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div class="loginButtonCont">
                    <button>Login</button>
                    <button type="button" onclick="window.location.href='../homepage.php'">Back</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>