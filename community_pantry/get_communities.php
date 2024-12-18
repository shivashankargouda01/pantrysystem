<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$subsystemId = $_GET['subsystem_id'];
$stmt = $pdo->prepare("SELECT * FROM communities WHERE subsystem_id = ?");
$stmt->execute([$subsystemId]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
