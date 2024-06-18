<?php

$product_name = $_POST['product_name'];
$description = $_POST['description'];
$stock = $_POST['stock'];
$price = $_POST['price'];
$product_expiration = $_POST['product_expiration'];
$pid = $_POST['id'];

// Upload or move the file to our directory
$target_dir = "../uploads/products/";
$file_name_value = NULL;

if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
    $file_data = $_FILES['img'];

    $file_name = $file_data['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name = 'product-'.time().'.'.$file_ext;

    $check = getimagesize($file_data["tmp_name"]);

    # Move the file
    if ($check) {
       if (move_uploaded_file($file_data['tmp_name'], $target_dir. $file_name)) {
            // Save the file_name to the database
            $file_name_value = $file_name;
       }
    } 
}
// Save the data to the database
include('../connection.php');
$sql = "UPDATE products SET product_name=?, description=?, stock=?, price=?, product_expiration=?, img=?, updated_at=NOW() WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$product_name, $description, $stock, $price, $product_expiration, $file_name_value, $pid]);

// Delete the old values

$sql = "DELETE FROM productsuppliers WHERE product=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$pid]);

# Loop through the suppliers and add records
$suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];    
    foreach ($suppliers as $supplier) {
        $supplier_data = [
            'supplier_id' => $supplier,
            'product_id' => $pid,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $sql = "INSERT INTO productsuppliers (supplier, product, updated_at, created_at) VALUES (:supplier_id, :product_id, :updated_at, :created_at)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($supplier_data);
    }
$response = [
    'success' => true,
    'message' => ' input successfully added to the system.'
];

echo json_encode($response);

?>