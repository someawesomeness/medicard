<?php
    include 'database.php';
    
    // select data
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id FROM tbl_data";
    $q = $pdo->prepare($sql);
    $q->execute();
    $data = $q->fetchAll(PDO::FETCH_ASSOC);
    Database::disconnect();

    // Check if data is not empty
    if (!empty($data)) {
        // Extract only the 'id' values from each row
        $ids = array_map(function($row) {
            return $row['id'];
        }, $data);
        echo json_encode($ids);
    } else {
        echo "No data found";
    }
?>