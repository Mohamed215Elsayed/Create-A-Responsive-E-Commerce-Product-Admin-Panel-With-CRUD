<?php
try {
    $conn = new PDO("mysql:host=127.0.0.1;port=3308;dbname=cart_db", "root", "");
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
