<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$subsystems = $pdo->query("SELECT * FROM subsystems")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subsystems</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 20px; }
        .subsystem { padding: 10px; border: 1px solid #ccc; margin: 5px; background-color: #e9f5ff; cursor: pointer; }
        .subsystem:hover { background-color: #d0ecff; }
    </style>
    <script>
        function loadCommunities(subsystemId) {
            window.location.href = 'communities.php?subsystem_id=' + subsystemId;
        }
    </script>
</head>
<body>
    <h1>Select a Subsystem</h1>
    <?php foreach ($subsystems as $subsystem): ?>
        <div class="subsystem" onclick="loadCommunities(<?php echo $subsystem['id']; ?>)">
            <?php echo $subsystem['name']; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
