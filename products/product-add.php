<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: login.php');
    $_SESSION['table'] = 'products';
    $_SESSION['redirect_to'] = 'product-add.php';

    $user = $_SESSION["user"];
    // $users = include("show-user.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Add Product</title>
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
                    <h1 class="sectionHeader"><i class="fa fa-plus"></i> Insert Product</h1>
                        <div id="userAddFormCont">

                    <form action="add.php" method="POST" class="appForm" enctype="multipart/form-data">
                        <div class="appFormInputCont">
                            <label for="product_name">Product Name</label>
                            <input type="text" id="product_name" name="product_name" class="appFormInput" placeholder="Enter product name">
                        </div>
                        <div class="appFormInputCont">
                            <label for="description">Description</label>
                            <textarea class="appFormInput productTextAreaInput" placeholder="Enter product name" id="description" name="description">

                            </textarea>
                        </div>
                        <div class="appFormInputCont">
                            <label for="expirartion">Expiration</label>
                            <input type="date" id="product_expiration" name="product_expiration" class="appFormInput" placeholder="Enter product name">
                        </div>
                        <div class="appFormInputCont">
                            <label for="description">Suppliers</label>
                            <select name="suppliers[]" id="suppliersSelect" multiple="">
                                <option value="">Select Supplier</option>
                                    <?php 
                                        $show_table = 'suppliers';
                                        $suppliers = include('../process/show.php');
                                        foreach($suppliers as $supplier){
                                            echo '<option value="'.$supplier['id'].'">'.$supplier['supplier_name'].'</option>';
                                        }
                                    ?>
                            </select>
                        </div>
                        <div class="appFormInputCont">
                            <label for="product_name">Product Image</label>
                            <input type="file" name="img" class="appFormInput">
                        </div>
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