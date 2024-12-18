<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $communityId = $_POST['community_id'];
    $itemName = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $donorName = $_SESSION['user_name'];

    try {
        // Insert the donation into the database
        $stmt = $pdo->prepare("INSERT INTO donations (community_id, donor_name, item_name, quantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$communityId, $donorName, $itemName, $quantity]);

        // Optionally, update the quantity of the need (if you're tracking available quantities in the "needs" table)
        $updateStmt = $pdo->prepare("UPDATE needs SET quantity = quantity - ? WHERE community_id = ? AND item_name = ?");
        $updateStmt->execute([$quantity, $communityId, $itemName]);

        // Return JSON response for AJAX request
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Return JSON error message in case of failure
        echo json_encode(['success' => false]);
    }
} else {
    // Redirect to login if the user is not logged in
    header('Location: login.php');
    exit;
}
?>
