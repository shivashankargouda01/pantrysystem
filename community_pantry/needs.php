<?php
$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$communityId = $_GET['community_id'];
$needs = $pdo->prepare("SELECT * FROM needs WHERE community_id = ?");
$needs->execute([$communityId]);
$needs = $needs->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Needs</title>
    <style>
        .need { padding: 10px; border: 1px solid #ccc; margin: 5px; background-color: #fff4e9; }
        .need input { margin-left: 10px; }
    </style>
</head>
<body>
    <h1>Needs</h1>
    <form action="donate.php" method="POST">
        <?php foreach ($needs as $need): ?>
            <div class="need">
                <span><?php echo $need['item_name']; ?> (<?php echo $need['quantity']; ?> needed)</span>
                <input type="hidden" name="community_id" value="<?php echo $communityId; ?>">
                <input type="hidden" name="item_name" value="<?php echo $need['item_name']; ?>">
                <input type="number" name="quantity" placeholder="Donate Quantity">
                <button type="submit">Donate</button>
            </div>
        <?php endforeach; ?>
    </form>
</body>
</html>
