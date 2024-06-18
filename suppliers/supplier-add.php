<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');
    $_SESSION['table'] = 'suppliers';
    $_SESSION['redirect_to'] = 'supplier-add.php';

    $user = $_SESSION["user"];
    // $users = include("show-user.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Add Supplier</title>
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
                    <h1 class="sectionHeader"><i class="fa fa-plus"></i> Insert Supplier</h1>
                        <div id="userAddFormCont">

                    <form action="add.php" method="POST" class="appForm" enctype="multipart/form-data">
                        <div class="appFormInputCont">
                            <label for="product_name">Supplier Name</label>
                            <input type="text" id="supplier_name" name="supplier_name" class="appFormInput" placeholder="Enter supplier name">
                        </div>
                        <div class="appFormInputCont">
                            <label for="supplier_location">Location</label>
                            <input type="text" class="appFormInput productTextAreaInput" placeholder="Enter supplier location" id="supplier_location" name="supplier_location">
                        </div>
                        <div class="appFormInputCont">
                            <label for="email">Email</label>
                            <input type="text" class="appFormInput productTextAreaInput" placeholder="Enter supplier email" id="email" name="email">
                        </div>
                        <!-- <div class="appFormInputCont">
                            <label for="description">Suppliers</label>
                            <select name="suppliers" id="suppliersSelect" multiple="">
                                <option value="">Select Supplier</option>
                            </select>
                        </div> -->
                        <!-- <input type="hidden" name="table" value="adminstaffs"> -->
                        <button type="submit" class="appBtn"><i class="fa fa-plus">Add Product</i></button>
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