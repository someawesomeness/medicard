<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');
    $_SESSION['table'] = 'adminstaffs';
    $_SESSION['redirect_to'] = 'users-view.php';

    $show_table = 'adminstaffs';
    $user = $_SESSION["user"];
    $users = include("../process/show.php");  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Add Users</title>
    <?php include("../fixed/app-header-scripts.php"); ?>
</head>
<body>
    <div id="dashboardMainCont">
        <?php include('../fixed/app-sidebar.php') ?>
        <div class="dashboard_content_container" id="dashboard_content_container">
            <?php include('../fixed/app-topnav.php') ?>
        <div class="dashboard_content">
        <div class="dashboard_content_main">
            <div class="row">
                <div class="column column-5">
                    <h1 class="sectionHeader"><i class="fa fa-plus"></i> Insert Admin</h1>
                        <div id="userAddFormCont">

                    <form action="add.php" method="POST" class="appForm">
                        <div class="appFormInputCont">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="appFormInput">
                        </div>
                        <div class="appFormInputCont">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="appFormInput">
                        </div>
                        <div class="appFormInputCont">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" class="appFormInput">
                        </div>
                        <div class="appFormInputCont">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" class="appFormInput">
                        </div>
                        <!-- <input type="hidden" name="table" value="adminstaffs"> -->
                        <button type="submit" class="appBtn"><i class="fa fa-plus">Add User</i></button>
                    </form>
                    <?php
                        if(isset($_SESSION['response'])){ 
                            $response_message = $_SESSION['response']['message'];
                            $is_success = $_SESSION['response']['success'];
                            ?>
                            <div class="responseMessage">
                                <p class="<?= $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>" >
                                <?= $response_message ?> <!-- Corrected variable name -->
                            </p>
                            </div>
                    <?php unset($_SESSION['response']); } ?>
                        </div>
                </div>
                </div>
            </div>
        </div>     
        </div>
    </div>
    <?php include('../fixed/app-scripts.php'); ?>
</body>

</html>