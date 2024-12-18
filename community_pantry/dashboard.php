<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch all subsystems
$subsystems = $pdo->query("SELECT * FROM subsystems")->fetchAll(PDO::FETCH_ASSOC);

// Fetch top donors
$topDonors = $pdo->query("SELECT donor_name, SUM(quantity) AS total_donated FROM donations GROUP BY donor_name ORDER BY total_donated DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Pantry Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1d1d1d;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .dashboard {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            padding: 30px;
            background: linear-gradient(135deg, #e60000, #800000);
            border-radius: 20px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: 5px solid #660000;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            font-size: 40px;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px 10px #660000;
            margin-bottom: 30px;
        }
        .logout {
            text-align: right;
            margin-bottom: 20px;
        }
        .logout a {
            text-decoration: none;
            color: #f5a623;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
        }
        .logout a:hover {
            color: #fff;
            text-decoration: underline;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 2px solid #660000;
        }
        .section h2 {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #f5a623;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px #660000;
        }
        .item {
            padding: 15px;
            margin: 10px 0;
            background-color: #660000;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .item:hover {
            background-color: #ff6666;
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .needs {
            margin-top: 15px;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            color: #333;
            font-size: 16px;
        }
        .needs input {
            margin-left: 10px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .needs button {
            padding: 10px 15px;
            margin-left: 10px;
            background-color: #ff4500;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .needs button:hover {
            background-color: #ff5733;
        }
        .donor-list {
            margin-top: 15px;
            padding: 20px;
            background-color: #ff4d4d;
            border-radius: 10px;
            color: #fff;
            font-size: 18px;
        }
        .donor-list ul {
            list-style-type: none;
            padding-left: 0;
        }
        .donor-list li {
            padding: 8px;
            margin-bottom: 15px;
            background-color: #660000;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }
        .donor-list li:hover {
            background-color: #b30000;
            transform: scale(1.05);
        }
    </style>
    <script>
        async function loadCommunities(subsystemId) {
            const response = await fetch(`get_communities.php?subsystem_id=${subsystemId}`);
            const communities = await response.json();

            const communitySection = document.getElementById('communities');
            communitySection.innerHTML = '<h2>Communities</h2>';
            communities.forEach(community => {
                const div = document.createElement('div');
                div.className = 'item';
                div.textContent = `${community.name} (${community.city})`;
                div.onclick = () => loadNeeds(community.id);
                communitySection.appendChild(div);
            });
        }

        async function loadNeeds(communityId) {
            const response = await fetch(`get_needs.php?community_id=${communityId}`);
            const needs = await response.json();

            const needsSection = document.getElementById('needs');
            needsSection.innerHTML = '<h2>Needs</h2>';
            needs.forEach(need => {
                const div = document.createElement('div');
                div.className = 'needs';
                div.innerHTML = `
                    <span><strong>${need.item_name}</strong> (${need.quantity} needed)</span>
                    <form action="donate.php" method="POST" style="display: inline;" onsubmit="return submitDonation(event, ${communityId}, '${need.item_name}')">
                        <input type="hidden" name="community_id" value="${communityId}">
                        <input type="hidden" name="item_name" value="${need.item_name}">
                        <input type="number" name="quantity" placeholder="Donate Quantity" required>
                        <button type="submit">Donate</button>
                    </form>
                `;
                needsSection.appendChild(div);
            });
        }

        // This function will handle donation submission via AJAX
        async function submitDonation(event, communityId, itemName) {
            event.preventDefault();

            const form = event.target;
            const quantity = form.querySelector('input[name="quantity"]').value;

            const response = await fetch('donate.php', {
                method: 'POST',
                body: new URLSearchParams(new FormData(form)),
            });

            const result = await response.json();

            if (result.success) {
                // Update the available quantity in the needs list without refreshing the page
                loadNeeds(communityId);
                alert('Thank you for your donation!');
            } else {
                alert('Error: Donation failed.');
            }
        }
    </script>
</head>
<body>
    <div class="dashboard">
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>

        <h1>Community Pantry Dashboard</h1>
        
        <div class="section">
            <h2>Subsystems</h2>
            <?php foreach ($subsystems as $subsystem): ?>
                <div class="item" onclick="loadCommunities(<?php echo $subsystem['id']; ?>)">
                    <?php echo $subsystem['name']; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="communities" class="section">
            <h2>Communities</h2>
            <!-- Communities will be loaded here dynamically -->
        </div>

        <div id="needs" class="section">
            <h2>Needs</h2>
            <!-- Needs will be loaded here dynamically -->
        </div>

        <div class="section">
            <h2>Top Donors</h2>
            <div class="donor-list">
                <ul>
                    <?php foreach ($topDonors as $donor): ?>
                        <li>
                            <?php echo $donor['donor_name']; ?> - <?php echo $donor['total_donated']; ?> items donated
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
