<?php
    $servername = "localhost:4306";
    $username = 'root';
    $password = '';

    //connect to database
    $conn = null; // Initialize to null
    try {
        $conn = new PDO("mysql:host=$servername;dbname=medinventory", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected Successfully.";
    } catch (\Exception $e) {
        $error_message = $e->getMessage() ."";
    }
    // make the connection variable global
    $GLOBALS['conn'] = $conn;

    //connect to database
    $conn2 = null; // Initialize to null
    try {
        $conn2 = new PDO("mysql:host=$servername;dbname=points_of_sale", $username, $password);
        // set the PDO error mode to exception
        $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected Successfully.";
    } catch (\Exception $e) {
        $error_message = $e->getMessage() ."";
    }
    // make the connection variable global
    if ($conn2 !== null) {
        $GLOBALS['conn_pos'] = $conn2;
    } else {
        echo "Failed to connect to points_of_sale database.";
    }
?>