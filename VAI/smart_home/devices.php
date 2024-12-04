<?php
// devices.php

// Create a connection to the SQLite database
try {
    $pdo = new PDO('sqlite:database.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch devices from the database
    $stmt = $pdo->prepare('SELECT * FROM devices');
    $stmt->execute();

    // Fetch the result as an associative array
    $devices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output devices in JSON format
    echo json_encode($devices);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
