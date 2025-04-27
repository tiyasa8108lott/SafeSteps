<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id']; // Get user ID from session

// Fetch contacts from the database
$sql = "SELECT * FROM contacts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Emergency Contacts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 12px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        li:hover {
            background-color: #f1f1f1;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
        }

        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <header>
        <h1>SafeSteps - Emergency Contacts</h1>
    </header>

    <div class="container">
        <h2>Your Emergency Contacts</h2>
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo $row['contact_name']; ?></strong><br>
                        <span><?php echo $row['contact_phone']; ?></span><br>
                        <span><?php echo $row['contact_email']; ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No emergency contacts found.</p>
        <?php endif; ?>
        
        <a href="dashboard.php">Back to Profile</a>
    </div>
</body>
</html>
