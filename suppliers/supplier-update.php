<?php

$supplier_name = isset($_POST['supplier_name']) ? $_POST['supplier_name'] : '';

$supplier_location = isset($_POST['supplier_location']) ? $_POST['supplier_location'] : '';

$email = isset($_POST['email']) ? $_POST['email'] : '';

$products = isset($_POST['products']) ? $_POST['products'] : '';

$supplier_id = isset($_POST['id']) ? $_POST['id'] : '';

// If supplier_id is empty, return an error
if (empty($supplier_id)) {
    $response = [
        'success' => false,
        'message' => 'No supplier ID provided.'
    ];

    echo json_encode($response);
    exit;
}

// Save the data to the database
include('../connection.php');
$sql = "UPDATE suppliers SET supplier_name=?, supplier_location=?, email=?, updated_at=NOW() WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$supplier_name, $supplier_location, $email, $supplier_id]);

// Delete the old values

$sql = "DELETE FROM productsuppliers WHERE supplier=?";
$stmt = $conn->prepare($sql);
$stmt->execute([$supplier_id]);

# Loop through the suppliers and add records
$products = isset($_POST['products']) ? $_POST['products'] : [];    
    foreach ($products as $product) {
        $supplier_data = [
            'supplier_id' => $supplier_id,
            'product_id' => $product,
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