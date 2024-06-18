<?php
    include('../connection.php');

    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch suppliers
    $stmt = $conn->prepare("SELECT product_name, products.id FROM products, productsuppliers WHERE productsuppliers.supplier=$id AND productsuppliers.product = products.id");
    $stmt->execute();

    // Fetch the results and assign them to $products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Now it's safe to call array_column()
    $productIds = array_column($products, 'id');

    // Include the products in the response
    $row['products'] = $productIds;

    echo json_encode($row);
?>