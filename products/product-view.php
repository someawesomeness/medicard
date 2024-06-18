<?php 
    // Start the session.
    session_start();
    if (!isset($_SESSION["user"])) header('location: ../starting/login.php');

    $show_table = 'products';
    $user = $_SESSION["user"];
    $products = include("../process/show.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicard System Admin - View Products</title>
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
                            <h1 class="sectionHeader"><i class="fa fa-list"></i> List of Products</h1>
                            <div class="sectionContent">
                                <div class="admins">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Stock</th>
                                                <th>Expiration</th>
                                                <th>Value</th>
                                                <th>Suppliers</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                            <tbody>
                                                <?php foreach($products as $index => $product){?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td class="firstName">
                                                    <img src="../uploads/products/<?php echo $product['img']; ?>" class="productImages" alt="Product Image">
                                                    </td>
                                                    <td class="lastName"><?= $product['product_name'] ?></td>
                                                    <td class="email"><?= $product['description'] ?></td>
                                                    <td class="stock"><?= $product['stock'] ?></td>
                                                    <td class="expiration"><?= $product['product_expiration'] ?></td>
                                                    <td class="price"><?= $product['price'] ?></td>
                                                    <td class="email">
                                                            <?php
                                                                $pid = $product['id'];
                                                                $stmt = $conn->prepare("SELECT supplier_name FROM suppliers, productsuppliers WHERE productsuppliers.product=$pid AND productsuppliers.supplier = suppliers.id");
                                                                $stmt->execute();
                                                                $rows = $stmt->fetchAll();

                                                                if ($rows) {
                                                                    $supplier_arr = array_column($rows, 'supplier_name');
                                                                    $supplier_list = '<li>' . implode('</li><li>', $supplier_arr);
                                                                } else {
                                                                    $supplier_list = '-';
                                                                }

                                                                echo $supplier_list;
                                                            ?>
                                                    </td>
                                                    <!-- <td>
                                                    <?php 
                                                        $show_table = 'suppliers';
                                                        $suppliers = include('../process/show.php');
                                                        foreach($suppliers as $supplier){
                                                            echo '<option value="'.$supplier['id'].'">'.$supplier['supplier_name'].'</option>';
                                                        }
                                                    ?>
                                                    </td> -->
                                                    <td>
                                                        <?php
                                                            $uid = $product['created_by'];
                                                            $stmt = $conn->prepare("SELECT * FROM adminstaffs WHERE id=$uid");
                                                            $stmt->execute();
                                                            $row = $stmt->fetch();

                                                            $created_by_name = $row['first_name'] . ' ' . $row['last_name'];
                                                            echo $created_by_name;
                                                        ?>
                                                    </td>
                                                    <td><?= date('M d,Y @ H:i:s A', strtotime($product['created_at'])) ?></td>
                                                    <td><?= date('M d,Y @ H:i:s A', strtotime($product['updated_at'])) ?></td>
                                                    <td>
                                                        <a href="" class="updateProduct" data-pid="<?= $product['id'] ?>" data-description="<?= $product['description'] ?>"><i class="fa fa-pencil"></i>Edit</a>
                                                        <a href="" class="deleteProduct" data-pname="<?= $product['product_name']?>" data-pid="<?= $product['id'] ?>"><i class="fa fa-trash"></i>Delete</a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </thead>
                                    </table>
                                    <p class="adminCount"><?= count($products)?> Items </p>
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
    
    $show_table = 'suppliers';
    $suppliers = include('../process/show.php');

    $suppliers_arr = [];

        foreach($suppliers as $supplier){
            $suppliers_arr[$supplier['id']] = $supplier['supplier_name'];
        }
        $suppliers_arr = json_encode($suppliers_arr);
    ?>
    <!-- <script src="deleteupdate.js">
    </script> -->
    <script>
        var suppliersList = <?= $suppliers_arr ?>;
    function Script() {
        var vm = this;

        this.registerEvents = function() {
            document.addEventListener('click', function(e) {
                var targetElement = e.target;
                var classList = targetElement.classList;

                if (classList.contains('deleteProduct')) {
                    e.preventDefault();

                    let pId = targetElement.dataset.pid;
                    let pName = targetElement.dataset.pname;

                    if (typeof pName !== 'undefined' && window.confirm('Are you sure to delete: ' + pName + '?')) {
                        $.ajax({
                            method: 'POST',
                            data: {
                                id: pId,
                                action: 'delete', // Added action parameter to specify the operation
                                table: 'products'
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

                if(classList.contains('updateProduct')){
                    e.preventDefault();
                    pId = targetElement.dataset.pid;
                    vm.showEditDialog(pId);
                }
            });
        }

        this.showEditDialog = function(id) {
            console.log('Product ID:', id); // Log the product ID
        
            $.get('../process/get-product.php', {id: id}, function(response) {
                var productDetails;
                try {
                    productDetails = JSON.parse(response);
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    return;
                }

                if (!productDetails || !productDetails.hasOwnProperty('suppliers')) {
                    console.error('Suppliers property does not exist in the response:', productDetails);
                    return;
                }

                let curSuppliers = productDetails['suppliers'];
                let supplierOption = '';
                
                for (const [supId, supName] of Object.entries(suppliersList)) {
                    selected = curSuppliers.indexOf(supId) > -1 ? 'selected' : '';
                    supplierOption += "<option "+ selected +" value='" + supId +"'>"+ supName +"</option>";
                }

                console.log('Response:', response); // Log the entire response

                // Rest of your code...
                // Create Bootstrap modal HTML
                var modalHTML = '<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" style="z-index: 1050;">\
                    <div class="modal-dialog" role="document">\
                        <div class="modal-content">\
                            <div class="modal-header">\
                                <h5 class="modal-title" id="updateModalLabel">Update <strong> ' + productDetails.product_name + ' ' + '</strong>' + '</h5>\
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\
                                    <span aria-hidden="true">&times;</span>\
                                </button>\
                            </div>\
                            <div class="modal-body">\
                                <form action="add.php" method="POST" enctype="multipart/form-data" id="updateForm">\
                                        <div class="form-group">\
                                            <label for="product_name">Product Name:</label>\
                                            <input type="text" class="form-control" id="product_name" name="product_name" value="' + productDetails.product_name +'">\
                                        </div>\
                                        <div class="appFormInputCont">\
                                            <label for="description">Suppliers</label>\
                                            <select name="suppliers[]" id="suppliersSelect" multiple>\
                                                <option value="">Select Supplier</option>\
                                                ' + supplierOption + '\
                                            </select>\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="descripiton">Description:</label>\
                                            <textarea class="form-control" id="description" name="description">' + productDetails.description + '</textarea>\
                                            </textarea>\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="stock">Stock:</label>\
                                            <input type="number" class="form-control" id="stock" name="stock" value="' + productDetails.stock +'">\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="price">Value:</label>\
                                            <input type="number" class="form-control" id="price" name="price" value="' + productDetails.price +'">\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="expiration">Expiration:</label>\
                                            <input type="date" class="form-control" id="product_expiration" name="product_expiration" value="' + productDetails.product_expiration + '">\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="product_name">Product Image:</label>\
                                            <input type="file" class="form-control" placeholder="productDetails.price" id="img" value="' + productDetails.img + '">\
                                        </div>\
                                    </form>\
                            </div>\
                            <div class="modal-footer">\
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>\
                                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>\
                            </div>\
                        </div>\
                    </div>\
                </div>';

                // Append modal to body
                document.body.insertAdjacentHTML('beforeend', modalHTML);
                
                // Show modal
                $('#updateModal').modal('show');

                // Hide modal
                $('#updateModal').on('hidden.bs.modal', function () {
                        $(this).remove();
                    });

                // Add event listener to save changes button
                document.getElementById('saveChanges').addEventListener('click', function() {
                    var product_name = document.getElementById('product_name').value;
                    var stock = document.getElementById('stock').value;
                    var price = document.getElementById('price').value;
                    var description = document.getElementById('description').value;
                    var product_expiration = document.getElementById('product_expiration').value;
                    var img = document.getElementById('img').files[0];
                
                    var formData = new FormData();
                    formData.append('id', id);
                    formData.append('product_name', product_name);
                    formData.append('stock', stock);
                    formData.append('price', price);
                    formData.append('description', description);
                    formData.append('product_expiration', product_expiration);
                    formData.append('img', img);
                
                    $.ajax({
                        method: 'POST',
                        data: formData,
                        url: 'product-update.php',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.success) {
                                location.reload();
                            } else {
                                console.log(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
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