<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=community_pantry', 'root', 'Shivu@0425');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
        $stmt->execute([$name, $password]);

        // Automatically log in the user after registration
        $userId = $pdo->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;

        // Redirect to the dashboard
        header('Location: /community_pantry/dashboard.php');
        exit;
    } catch (Exception $e) {
        $message = 'An error occurred: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .register-container {
            background: #ffffff;
            color: #333;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .register-container h1 {
            margin-bottom: 20px;
            color: #2575fc;
            font-size: 28px;
        }

        .register-container form {
            display: flex;
            flex-direction: column;
        }

        .register-container input {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: 0.3s;
        }

        .register-container input:focus {
            border-color: #2575fc;
            box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
            outline: none;
        }

        .register-container button {
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            background-color: #2575fc;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-container button:hover {
            background-color: #1a5bbf;
        }

        .message {
            color: #28a745;
            margin-bottom: 15px;
        }

        .error {
            color: #e63946;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>
        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
