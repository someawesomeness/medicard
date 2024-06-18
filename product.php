<?php
include 'connection.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// if user checkout item
if ($action === 'add_product') {
    saveProducts();
}

function getProducts() {
    // Get connection variable
    $conn = $GLOBALS['conn'];

    // Prepare the SQL query
    $sql = "SELECT * FROM products";
    $stmt = $conn->prepare($sql);

    // Execute the query
    $stmt->execute();

    // Fetch all the rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the rows
    return $rows;
}

function saveProducts() {
    try {
        // get conn variable
        $conn = $GLOBALS['conn_pos'];

        // Check if 'data' and 'customer' keys exist in $_POST
        if (!isset($_POST['data']) || !isset($_POST['customer'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Data or customer information not provided.'
            ]);
            return;
        }

        $data = $_POST['data'];
        $customer = $_POST['customer'];

        // insert to customer
        $sql = "INSERT INTO customers(uid, family_name, beneficiary_name, contact, address, date_created, date_updated) VALUES (:uid, :family_name, :beneficiary_name, :contact, :address, :date_created, :date_updated)";
        $db_arr = [
            'uid' => $customer['uid'],
            'family_name' => $customer['familyName'],
            'beneficiary_name'=> $customer['beneficiaryName'],
            'contact'=> $customer['mobile'],
            'address'=> isset($customer['address']) ? $customer['address'] : '', // Check if 'address' key exists
            'date_created' => date('Y-m-d H:i:s'),
            'date_updated' => date('Y-m-d H:i:s')
        ];
        // Bind the values to the placeholders
        $stmt = $conn->prepare($sql);
        $stmt->execute($db_arr);
        $customer_id = $conn->lastInsertId();
        
        // insert to sales
        $sql = "INSERT INTO sales(customer_id, user_id, total_amount, amount_tendered, change_amt, date_created, date_updated) VALUES (:customer_id, :user_id, :total_amount, :amount_tendered, :change_amt, :date_created, :date_updated)";

        $total_amount = $_POST['totalAmt'];
        $change_amt = $_POST['change'];
        $tenderedAmount = $_POST['tenderedAmount'];
        $user_id = 1; // hard code for now

        $db_arr = [
            'customer_id' => $customer_id, 
            'user_id' => $user_id, // hard code for now
            'total_amount' => $total_amount, 
            'amount_tendered' => $tenderedAmount, // Use the variable defined above
            'change_amt' => $change_amt,
            'date_created' => date('Y-m-d H:i:s'), 
            'date_updated' => date('Y-m-d H:i:s')
        ];
        // Bind the values to the placeholders
        $stmt = $conn->prepare($sql);
        $stmt->execute($db_arr);
        $sales_id = $conn->lastInsertId();

        // insert to order items
        foreach ($data as $product_id => $order_item) {
            // Insert to sales
            $sql = "INSERT INTO sale_items(sales_id, product_id, quantity, unit_price, sub_total, date_created, date_updated) VALUES (:sales_id, :product_id, :quantity, :unit_price, :sub_total, :date_created, :date_updated)";
            
            $db_arr = [
                'sales_id' => $sales_id, 
                'product_id' => $product_id, 
                'quantity' => $order_item['orderQty'], 
                'unit_price' => $order_item['price'],
                'sub_total' => $order_item['amount'],
                'date_created' => date('Y-m-d H:i:s'), 
                'date_updated' => date('Y-m-d H:i:s')
            ];
            // Bind the values to the placeholders
            $stmt = $conn->prepare($sql);
            $stmt->execute($db_arr);

            // get current stock
            $inv_conn = $GLOBALS['conn'];
            $sql = "SELECT products.stock FROM products WHERE id = $product_id";
            $stmt = $inv_conn->prepare($sql);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            $current_stock = (int) $product['stock'];
            // var_dump($product);

            // update inventory qty of products
            $new_stock = $current_stock - (int) $order_item['orderQty'];
            $sql = "UPDATE products SET stock =? WHERE id =?";

            $stmt = $inv_conn->prepare($sql);
            $stmt->execute([$new_stock, $product_id]);
        }
        // var_dump($data);
        echo json_encode([
            'success' => true,
            'id' => $sales_id, 
            'message' => 'Order successfully!',
            'products' => getProducts()
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]); 
    }
}