<?php
include('../connection.php');
include('../sale.php');

function getChartData($start, $end) {
    // loop datees

    while ($start <= $end) {
        $sales = getSales($start, $end); // provide both start and end dates
        $date_amount[$start] = array_sum(array_column($sales, 'total_amount'));
        $start = date('Y-m-d', strtotime($start . ' + 1 days'));
    }
    return json_encode(['categories' => array_keys($date_amount), 'series' => array_values($date_amount)]);
}

function getRecentOrders($limit = 5){
    $conn = $GLOBALS['conn_pos']; // Use square brackets to access array elements
    $sql = "SELECT * FROM sales WHERE sales.date_created ORDER BY sales.date_created DESC LIMIT $limit";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC); // declare and assign $sales variable
    return $sales; // Add this line to check the value of $sales
}

function getSaleWidgetData(){
    $start = '2024-06-01';
    $end = '2024-06-14';
    $sales = getSales($start, $end); // provide both start and end dates

    $qty = 0;
    $sale_amount = 0.00;
    $orders = count($sales);

    foreach ($sales as $sale) {
        $sale_amount += $sale['total_amount'];

        // get order items qty
        $order_items = getOrderItems($sale['id']);
        $qty += array_sum(array_column($order_items, 'quantity'));
    }

    // Use $qty and $orders somewhere after this
    // For example, you might want to return them:
    return ['qty' => $qty, 'orders' => $orders, 'sale_amount' => $sale_amount];
}

function getSales($start, $end) {
    // Fetch the sales
    $conn = $GLOBALS['conn_pos'];
    $sql = "SELECT * FROM sales WHERE sales.date_created >= '$start' and sales.date_created >= '$end'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC); // declare and assign $sales variable

    return $sales; // Add this line to check the value of $sales

    foreach ($sales as $sale) {
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
    }
}