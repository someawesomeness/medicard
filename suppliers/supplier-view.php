<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');

    $show_table = 'suppliers';
    $user = $_SESSION["user"];
    $suppliers = include("../process/show.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - View Suppliers</title>
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
                        <div class="column column-12">
                            <h1 class="sectionHeader"><i class="fa fa-list"></i> List of Suppliers</h1>
                            <div class="sectionContent">
                                <div class="admins">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier Name</th>
                                                <th>Supplier Location</th>
                                                <th>Contact Details</th>
                                                <th>Product</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody>
                                                <?php foreach($suppliers as $index => $supplier){ ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <?= $supplier['supplier_name'] ?>
                                                    </td>
                                                    <td><?= $supplier['supplier_name'] ?></td>
                                                    <td><?= $supplier['email'] ?></td>
                                                    <td>
                                                        <?php
                                                            $sid = $supplier['id'];
                                                            $stmt = $conn->prepare("SELECT product_name FROM products, productsuppliers WHERE productsuppliers.supplier=$sid AND productsuppliers.product = products.id");
                                                            $stmt->execute();
                                                            $rows = $stmt->fetchAll();

                                                            if ($rows) {
                                                                $product_arr = array_column($rows, 'product_name');
                                                                $product_list = '<li>' . implode('</li><li>', $product_arr);
                                                            } else {
                                                                $product_list = '-';
                                                            }

                                                            echo $product_list;
                                                            ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                            $uid = $supplier['created_by'];
                                                            $stmt = $conn->prepare("SELECT * FROM adminstaffs WHERE id=$uid");
                                                            $stmt->execute();
                                                            $row = $stmt->fetch();

                                                            $created_by_name = $row['first_name'] . ' ' . $row['last_name'];
                                                            echo $created_by_name;
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d,Y @ H:i:s A', strtotime($supplier['created_at'])) ?></td>
                                                    <td><?= date('M d,Y @ H:i:s A', strtotime($supplier['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateSupplier" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i>Edit</a>
                                                        <a href="" class="deleteSupplier" data-sname="<?= $supplier['supplier_name']?>" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-trash"></i>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </thead>
                                    </table>
                                    <p class="adminCount"><?= count($suppliers)?> suppliers </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </div>
    </div>
    <?php 
    include("../fixed/app-scripts.php");
    
    $show_table = 'products';
    $products = include '../process/show.php';

    $products_arr = [];

        foreach($products as $product){
            $products_arr[$product['id']] = $product['product_name'];
        }
        $products_arr = json_encode($products_arr);
    ?>
    <script>
        var productsList = <?= $products_arr ?>;
    function Script() {
        var vm = this;

        this.registerEvents = function() {
            document.addEventListener('click', function(e) {
                var targetElement = e.target;
                var classList = targetElement.classList;

                if (classList.contains('deleteSupplier')) {
                    e.preventDefault();

                    let sId = targetElement.dataset.sid;
                    let sName = targetElement.dataset.sname;

                    if (typeof sName !== 'undefined' && window.confirm('Are you sure to delete: ' + sName + '?')) {
                        $.ajax({
                            method: 'POST',
                            data: {
                                id: sId,
                                action: 'delete', // Added action parameter to specify the operation
                                table: 'suppliers'
                            },
                            url: '../process/delete.php',
                            dataType: 'json',
                            success: function(data) {
                                if (data.success) {
                                    alert(data.message); // Show the success message in an alert
                                    window.location.href = window.location.href; // Reload the page immediately
                                } else {
                                    console.log(data.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.responseText);
                            }
                        });
                    } else {
                        console.log('will not delete');
                    }
                }

                if(classList.contains('updateSupplier')){
                    e.preventDefault();
                    sId = targetElement.dataset.sid;
                    vm.showEditDialog(sId);
                }
            });
        }

        this.showEditDialog = function(id) {
            console.log('Supplier ID:', id); // Log the supplier ID

            $.get('../process/get-supplier.php', {id: id}, function(response) {
                var supplierDetails;
                try {
                    supplierDetails =JSON.parse(response)
                } catch (e) {
                    
                }

                if (!supplierDetails || !supplierDetails.hasOwnProperty('products')) {
                    console.error('Products property does not exist in the response:', supplierDetails);
                    return;
                }

                let curProducts = supplierDetails['products'];
                let productOptions = '';
                
                for (const [pId, pName] of Object.entries(productsList)) {
                    let selected = curProducts.indexOf(pId) > -1 ? 'selected' : '';
                    productOptions += "<option "+ selected +" value='" + pId +"'>"+ pName +"</option>";
                }

                // Rest of your code...


                // Rest of your code...
                // Create Bootstrap modal HTML
        var modalHTML = '<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" style="z-index: 1050;">\
            <div class="modal-dialog" role="document">\
                <div class="modal-content">\
                    <div class="modal-header">\
                        <h5 class="modal-title" id="updateModalLabel">Update <strong> ' + (supplierDetails.supplier_name || '') + ' ' + '</strong>' + '</h5>\
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                            <span aria-hidden="true">&times;</span>\
                        </button>\
                    </div>\
                    <div class="modal-body">\
                        <form action="add.php" method="POST" enctype="multipart/form-data" id="updateForm">\
                            <div class="form-group">\
                                <label for="supplier_name">Supplier Name:</label>\
                                <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="' + (supplierDetails.supplier_name || '') +'">\
                            </div>\
                            <div class="form-group">\
                                <label for="supplier_location">Location:</label>\
                                <input type="text" class="form-control" id="supplier_location" name="supplier_location" value="' + (supplierDetails.supplier_location || '') +'">\
                            </div>\
                            <div class="form-group">\
                                <label for="email">Email:</label>\
                                <input type="text" class="form-control" id="email" name="email" value="' + (supplierDetails.email || '') +'">\
                            </div>\
                            <div class="appFormInputCont">\
                                <label for="description">Products</label>\
                                <select name="products[]" id="products" multiple>\
                                    <option value="">Select Products</option>\
                                    ' + productOptions + '\
                                </select>\
                            </div>\
                        </form>\
                    </div>\
                    <div class="modal-footer">\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
                        <input type="hidden" id="sid" name="sid" value="">\
                        <button type="button" class="btn btn-primary" id="saveChanges" name="sid">Save changes</button>\
                    </div>\
                </div>\
            </div>\
        </div>';

                // Append modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Show modal
                $('#updateModal').modal('show');

                // When the modal is fully shown
                $('#updateModal').on('shown.bs.modal', function () {
                    // Add event listener to save changes button
                    document.getElementById('saveChanges').addEventListener('click', function() {
                        var supplier_name = document.getElementById('supplier_name') ? document.getElementById('supplier_name').value : null;
                        var supplier_location = document.getElementById('supplier_location') ? document.getElementById('supplier_location').value : null;
                        var email = document.getElementById('email') ? document.getElementById('email').value : null;
                        var products = $('#products').val(); // Corrected jQuery selector
                        var sid = document.getElementById('sid') ? document.getElementById('sid').value : null;
                        console.log('Supplier ID: ', sid);
                    
                        if (!supplier_name || !supplier_location || !email || !products) {
                            console.log('One or more elements could not be found');
                            return;
                        }
                    
                        var formData = new FormData();
                        formData.append('sid', sid);
                        formData.append('supplier_name', supplier_name);
                        formData.append('supplier_location', supplier_location);
                        formData.append('email', email);
                        formData.append('products', products);
                        // ... rest of your AJAX code
                        $.ajax({
                        method: 'POST',
                        data: formData,
                        url: 'supplier-update.php',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.success) {
                                console.log('Data saved successfully');
                                location.reload();
                            } else {
                                console.log('Server responded with error: ' + data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('AJAX request failed: ' + xhr.responseText);
                        }
                    });
                    });
                });      
            });
        };

        this.initialize = function(){
            this.registerEvents();
        };
    }

    // Usage
    var script = new Script();
    script.initialize();
</script>
</body>
</html>