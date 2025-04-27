<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['contact_name']);
    $phone = trim($_POST['contact_phone']);
    $email = trim($_POST['contact_email']);

    $sql = "INSERT INTO contacts (user_id, contact_name, contact_phone, contact_email) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $name, $phone, $email);

    if ($stmt->execute()) {
        $message = "Emergency contact added successfully!";
    } else {
        $message = "Error adding emergency contact.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Emergency Contact - SafeSteps</title>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;700&display=swap" rel="stylesheet">
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
    --border: #1e2c45;
    --shadow: 0 4px 20px rgba(0, 229, 255, 0.15);
    --success-shadow: 0 0 20px rgba(0, 255, 157, 0.5);
    --error-shadow: 0 0 20px rgba(255, 62, 94, 0.5);
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

/* Navbar */
.navbar {
    width: 100%;
    background-color: var(--bg-secondary);
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--shadow);
}

.navbar .logo {
    font-size: 1.8rem;
    font-weight: bold;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    color: transparent;
}

.navbar a {
    text-decoration: none;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

.navbar a:hover {
    opacity: 0.85;
}

/* Main Content */
.main {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 2rem;
}

.container {
    background-color: var(--bg-secondary);
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: var(--shadow);
    width: 100%;
    max-width: 600px;
    text-align: center;
    animation: slideDown 0.6s ease;
}

h2 {
    margin-bottom: 1.5rem;
    font-size: 2rem;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    color: transparent;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-secondary);
    font-size: 1rem;
}

input[type="text"],
input[type="tel"],
input[type="email"] {
    width: 100%;
    padding: 14px;
    border-radius: 8px;
    background-color: var(--bg-tertiary);
    border: 1px solid var(--border);
    color: var(--text-primary);
    font-size: 1rem;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus {
    border-color: var(--accent-primary);
    outline: none;
    box-shadow: 0 0 10px var(--accent-primary);
}

button {
    width: 100%;
    padding: 14px;
    font-size: 1.1rem;
    font-weight: 600;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}

button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 229, 255, 0.4);
}

.message {
    margin-top: 1rem;
    font-size: 1.2rem;
    padding: 10px;
    border-radius: 8px;
    animation: slideDown 0.5s ease;
}

.success {
    background: rgba(0, 255, 157, 0.1);
    color: var(--accent-secondary);
    box-shadow: var(--success-shadow);
}

.error {
    background: rgba(255, 62, 94, 0.1);
    color: var(--accent-danger);
    box-shadow: var(--error-shadow);
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">SafeSteps</div>
    <a href="dashboard.php">&larr; Dashboard</a>
</div>

<!-- Main Content -->
<div class="main">
    <div class="container">
        <h2>Add Emergency Contact</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="add_contact.php" id="contactForm">
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input type="text" id="contact_name" name="contact_name" placeholder="Enter contact's name" required>
            </div>

            <div class="form-group">
                <label for="contact_phone">Contact Phone</label>
                <input type="tel" id="contact_phone" name="contact_phone" placeholder="Enter contact's phone" required>
            </div>

            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" id="contact_email" name="contact_email" placeholder="Enter contact's email" required>
            </div>

            <button type="submit">Add Contact</button>
        </form>
    </div>
</div>

<?php if (!empty($message) && strpos($message, 'successfully') !== false): ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    document.getElementById("contactForm").reset();
});
</script>
<?php endif; ?>

</body>
</html>
