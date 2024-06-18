<?php
    // Database credentials
    $host = 'localhost:4306';
    $dbname = 'points_of_sale';
    $username = 'root';
    $password = '';

    // Create a new PDO instance
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Prepare your SQL statement without placeholders if no specific criteria are needed
        $stmt = $pdo->prepare("SELECT * FROM sale_items");

        // Execute the statement
        $stmt->execute();

        // Fetch all rows from the executed statement
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Your code to interact with the database goes here
        if (!empty($sales)) {
            $dispense = array_map(function($sale) {
                // Your mapping function here
                return $sale['product_id']; // Modify this line as needed
            }, $sales);
            
            // Output the result or do something with it
            echo json_encode($dispense);
        } else {
            echo "No data found";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
?>
