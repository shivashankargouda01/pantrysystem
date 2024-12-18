<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch the top donors ordered by total donations
$topDonors = $pdo->query("SELECT donor_name, SUM(quantity) AS total_donated FROM donations GROUP BY donor_name ORDER BY total_donated DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

// Return the top donors as a JSON response
echo json_encode($topDonors);
?>
