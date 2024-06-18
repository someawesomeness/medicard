<?php
include 'connection.php';

function getSaleCustomer($customer_id) {
    // get current stock
    $conn = $GLOBALS['conn_pos'];
    $sql = "SELECT * FROM customers WHERE id = $customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    return $customer;
}

function getOrderItems($id) {
    $conn = $GLOBALS['conn_pos'];
    $sql = "SELECT * FROM sale_items WHERE sales_id = $id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $rows;
}

function getSale($sale_id) {
    $conn = $GLOBALS['conn_pos'];
    $sql = "SELECT * FROM sales WHERE sales.id = $sale_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sale = $stmt->fetch(PDO::FETCH_ASSOC); // declare and assign $sale variable

    // Get customers data
    if (isset($sale['customer_id'])) {
        $customers_data = getSaleCustomer($sale['customer_id']);
    } else {
        // Handle the case where 'customer_id' is not set in the $sale array
        die('Customer ID not found in sale.');
    }
    // get order items data
    $items = getOrderItems($sale['id']);
    $items_data = [];

    $inv_conn = $GLOBALS['conn'];
        foreach ($items as $item) {
        $pid = $item['product_id'];
        $sql = "SELECT * FROM products WHERE id = $pid";
        $stmt = $inv_conn->prepare($sql);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        $items_data[$item['id']] = $item; // Use 'id' from $item as the key
        $items_data[$item['id']]['product'] = $product['product_name'];
    }
    return [
        'sale' => $sale,
        'items' => $items_data,
        'customer' => $customers_data
    ]; // Added semicolon at the end of the return statement
}