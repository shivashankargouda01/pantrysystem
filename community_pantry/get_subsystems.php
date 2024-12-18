<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=comm_pan', 'root', 'Shivu@0425');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT * FROM subsystems";
    $result = $pdo->query($query);

    foreach ($result as $row) {
        print_r($row);
    }
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
