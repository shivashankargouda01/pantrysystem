<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$communityId = $_GET['community_id'];
$stmt = $pdo->prepare("SELECT * FROM needs WHERE community_id = ?");
$stmt->execute([$communityId]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
