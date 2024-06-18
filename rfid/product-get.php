<?php
    // Database credentials
    $host = 'localhost:4306';
    $dbname = 'medinventory';
    $username = 'root';
    $password = '';

    // Create a new PDO instance
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Prepare your SQL statement without placeholders if no specific criteria are needed
        $stmt = $pdo->prepare("SELECT * FROM products");

        // Execute the statement
        $stmt->execute();

        // Fetch all rows from the executed statement
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Your code to interact with the database goes here
        if (!empty($products)) {
            $result = array_map(function($item) {
                // Your mapping function here
                return $item['product_name']; // Modify this line as needed
            }, $products);
            
            // Output the result or do something with it
            echo json_encode($result);
        } else {
            echo "No data found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
