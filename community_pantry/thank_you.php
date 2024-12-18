<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if the donation was successful
$success = isset($_GET['success']) && $_GET['success'] == 'true';
$donorName = isset($_GET['donor_name']) ? htmlspecialchars($_GET['donor_name']) : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            padding: 50px;
        }
        .thank-you-message {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .thank-you-message.success {
            color: #28a745;
        }
        .thank-you-message.error {
            color: #dc3545;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="thank-you-message <?php echo $success ? 'success' : 'error'; ?>">
        <?php if ($success): ?>
            <h1>Thank you for your donation, <?php echo $donorName; ?>!</h1>
            <p>Your donation has been successfully recorded.</p>
        <?php else: ?>
            <h1>Oops! Something went wrong.</h1>
            <p>There was an issue processing your donation. Please try again.</p>
        <?php endif; ?>
    </div>

    <a href="dashboard.php" class="button">Back to Dashboard</a>

    <div id="top-donors">
        <h2>Updated Top Donors</h2>
        <!-- The Top Donors list will be updated here via AJAX -->
    </div>

    <script>
        // Fetch the updated Top Donors list and update the page dynamically
        async function loadTopDonors() {
            const response = await fetch('get_top_donors.php');
            const donors = await response.json();

            const 
