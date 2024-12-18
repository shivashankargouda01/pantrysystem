<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$subsystemId = $_GET['subsystem_id'];
$communities = $pdo->prepare("SELECT * FROM communities WHERE subsystem_id = ?");
$communities->execute([$subsystemId]);
$communities = $communities->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communities</title>
    <style>
        .community { padding: 10px; border: 1px solid #ccc; margin: 5px; background-color: #e9f5ff; cursor: pointer; }
        .community:hover { background-color: #d0ecff; }
    </style>
    <script>
        function loadNeeds(communityId) {
            window.location.href = 'needs.php?community_id=' + communityId;
        }
    </script>
</head>
<body>
    <h1>Communities</h1>
    <?php foreach ($communities as $community): ?>
        <div class="community" onclick="loadNeeds(<?php echo $community['id']; ?>)">
            <?php echo $community['name']; ?> (<?php echo $community['city']; ?>)
        </div>
    <?php endforeach; ?>
</body>
</html>
