<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');

    $user = $_SESSION["user"];
    // $users = include("show-user.php");

    // get all products
    $show_table = 'products';
    $products = include("../process/show.php");
    $products = json_encode($products);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - Order Product</title>
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
                    <h1 class="sectionHeader"><i class="fa fa-plus"></i> Order Product</h1>
                        <div>
                            <div class="alignRight">
                                <button class="orderBtn orderProductBtn">Order Product</button>
                            </div>
                            <div id="orderProductLists">
                                <div class="orderProductRow">
                                    <div>
                                        <label for="product_name">PRODUCT NAME</label>
                                        <select name="product_name" id="product_name" class="productNameSelect">
                                            <option value="">Product 1</option>
                                        </select>
                                    </div>
                                    <div class="supplierRows">
                                        <div class="row">
                                            <div style="width: 50%;">
                                                <p class="supplierName">Supplier 1</p>
                                            </div>
                                            <div style="width: 50%;">
                                                <label for="quantity">Quantity: </label>
                                                <input type="number" id="quantity" name="quantity" class="appFormInput" placeholder="Enter product quantity">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div style="width: 50%;">
                                                <p class="supplierName">Supplier 2</p>
                                            </div>
                                            <div style="width: 50%;">
                                            <label for="quantity">Quantity: </label>
                                            <input type="number" id="quantity" name="quantity" class="appFormInput" placeholder="Enter product quantity">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div style="width: 50%;">
                                                <p class="supplierName">Supplier 3</p>
                                            </div>
                                            <div style="width: 50%;">
                                            <label for="quantity">Quantity: </label>
                                            <input type="number" id="quantity" name="quantity" class="appFormInput" placeholder="Enter product quantity">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alignRight marginTop20">
                                <button class="orderBtn submitOrderProductBtn">Submit Order</button>
                            </div>
                        </div>
                </div>
                </div>
            </div>
        </div>     
        </div>
    </div>
    <?php include('../fixed/app-scripts.php'); ?>
    <script>
        var products = <?php echo $products?>;
        console.log(products);
    </script>
</body>

</html>