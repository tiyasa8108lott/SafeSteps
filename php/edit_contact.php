<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Contact ID not provided!";
    exit();
}

$contact_id = $_GET['id'];

$contact_result = $conn->query("SELECT * FROM contacts WHERE id = $contact_id AND user_id = $user_id");
if ($contact_result->num_rows == 0) {
    echo "Contact not found!";
    exit();
}

$contact_data = $contact_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_name = $_POST['contact_name'];
    $contact_phone = $_POST['contact_phone'];
    $contact_email = $_POST['contact_email'];

    $update_query = "UPDATE contacts SET contact_name = '$contact_name', contact_phone = '$contact_phone', contact_email = '$contact_email' WHERE id = $contact_id AND user_id = $user_id";
    if ($conn->query($update_query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating contact: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Emergency Contact</title>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root {
    --bg-primary: #0a0e17;
    --bg-secondary: #141c2e;
    --bg-tertiary: #1c2538;
    --accent-primary: #00e5ff;
    --accent-secondary: #00ff9d;
    --accent-danger: #ff3e5e;
    --text-primary: #ffffff;
    --text-secondary: #a0b0c5;
    --text-tertiary: #6a7a8c;
    --border: #1e2c45;
    --shadow: 0 4px 20px rgba(0, 229, 255, 0.15);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Rajdhani', sans-serif;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1;
    width: 90%;
    max-width: 600px;
    margin: 30px auto;
    padding: 20px;
}

header {
    background-color: var(--bg-secondary);
    padding: 20px;
    text-align: center;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.header-content h1 {
    font-size: 2rem;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    color: transparent;
}

.edit-contact-section {
    background-color: var(--bg-secondary);
    margin-top: 30px;
    padding: 25px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: var(--text-secondary);
}

.form-group input {
    width: 100%;
    padding: 12px;
    font-size: 1rem;
    border: 1px solid var(--border);
    border-radius: 5px;
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.form-group input:focus {
    border-color: var(--accent-primary);
    outline: none;
}

.submit-btn {
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
    padding: 12px 25px;
    font-size: 1.1rem;
    font-weight: 600;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s ease;
    width: 100%;
}

.submit-btn:hover {
    opacity: 0.9;
}

footer {
    text-align: center;
    margin-top: 40px;
    padding: 20px;
    background-color: var(--bg-secondary);
    border-radius: 8px;
    font-size: 0.9rem;
    color: var(--text-tertiary);
}
</style>
</head>
<body>
<div class="container">
    <header>
        <div class="header-content">
            <h1>Edit Emergency Contact</h1>
        </div>
    </header>

    <section class="edit-contact-section">
        <form method="POST">
            <div class="form-group">
                <label for="contact_name">Contact Name:</label>
                <input type="text" id="contact_name" name="contact_name" value="<?php echo htmlspecialchars($contact_data['contact_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_phone">Phone:</label>
                <input type="text" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($contact_data['contact_phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_email">Email:</label>
                <input type="email" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($contact_data['contact_email']); ?>" required>
            </div>
            <button type="submit" class="submit-btn">Update Contact</button>
        </form>
    </section>

   
</div>
</body>
</html>
