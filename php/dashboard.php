<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_result = $conn->query("SELECT name FROM users WHERE id = $user_id");
$user_data = $user_result->fetch_assoc();
$contacts_result = $conn->query("SELECT * FROM contacts WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SafeSteps Dashboard</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

html { scroll-behavior: smooth; }

body {
    font-family: 'Rajdhani', sans-serif;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* Container */
.container {
    flex: 1;
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

/* Header */
header {
    background-color: var(--bg-secondary);
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content h1 {
    font-size: 1.8rem;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    color: transparent;
}

.nav-logout {
    background: none;
    border: none;
    color: var(--accent-danger);
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.nav-logout:hover {
    color: #ff5b77;
}

/* Sections */
.contacts-section, .alert-section {
    background-color: var(--bg-secondary);
    margin-top: 30px;
    padding: 25px;
    border-radius: 8px;
    box-shadow: var(--shadow);
    animation: fadeIn 1s ease;
}

/* Section Headers */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Buttons */
.add-contact-btn, .edit-btn, .delete-btn {
    padding: 10px 15px;
    border-radius: 6px;
    font-weight: 600;
    transition: 0.4s;
    text-decoration: none;
}

.add-contact-btn {
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
}

.edit-btn {
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
}

.delete-btn {
    background-color: rgba(255, 62, 94, 0.1);
    color: var(--accent-danger);
    border: 1px solid rgba(255, 62, 94, 0.2);
}

.add-contact-btn:hover, .edit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 10px var(--accent-primary);
}

.delete-btn:hover {
    background-color: rgba(255, 62, 94, 0.2);
}

/* Table */
.contacts-table {
    width: 100%;
    border-collapse: collapse;
}

.contacts-table th, .contacts-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid var(--border);
}

.contacts-table th { color: var(--text-tertiary); text-transform: uppercase; }
.contacts-table td { color: var(--text-secondary); }

/* Emergency Alert Card */
.alert-card {
    text-align: center;
    background: linear-gradient(135deg, var(--bg-secondary), rgba(28, 37, 56, 0.8));
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.send-alert-btn {
    margin-top: 20px;
    padding: 1rem 2rem;
    font-size: 1.1rem;
    background: linear-gradient(90deg, var(--accent-danger), #ff6b8b);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s;
}

.send-alert-btn:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
}

/* Footer */
footer {
    background-color: var(--bg-secondary);
    text-align: center;
    padding: 20px;
    font-size: 0.9rem;
    color: var(--text-tertiary);
    width: 100%;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}

@media (max-width: 768px) {
    .container { width: 100%; padding: 15px; }
    header { flex-direction: column; gap: 10px; }
}
</style>

</head>
<body>

<div class="container">
<header>
    <div class="header-content">
        <h1>Welcome, <?php echo htmlspecialchars($user_data['name']); ?></h1>
    </div>
    <form action="../index.html" method="POST">
        <button type="submit" class="nav-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
</header>

<!-- Contacts Section -->
<section class="contacts-section">
    <div class="section-header">
        <h2>Your Emergency Contacts</h2>
        <a href="add_contact.php" class="add-contact-btn">+ Add New Contact</a>
    </div>
    <?php if ($contacts_result->num_rows > 0): ?>
    <table class="contacts-table">
        <thead>
            <tr>
                <th>Contact Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($contact = $contacts_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($contact['contact_name']); ?></td>
                <td><?php echo htmlspecialchars($contact['contact_phone']); ?></td>
                <td><?php echo htmlspecialchars($contact['contact_email']); ?></td>
                <td>
                    <a href="edit_contact.php?id=<?php echo $contact['id']; ?>" class="edit-btn">Edit</a>
                    <button onclick="confirmDelete(<?php echo $contact['id']; ?>)" class="delete-btn">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No emergency contacts yet. <a href="add_contact.php">Add one now!</a></p>
    <?php endif; ?>
</section>

<!-- Emergency Alert Section -->
<section class="alert-section">
    <div class="alert-card">
        <h2>Emergency Alert</h2>
        <p>Send your current location to all your emergency contacts.</p>
        <form id="alertForm" method="POST" action="send_alert.php">
            <input type="hidden" id="location" name="location" required>
            <button type="button" onclick="getLocationAndSendAlert()" class="send-alert-btn">
                <i class="fas fa-exclamation-triangle"></i> Send Emergency Alert
            </button>
        </form>
    </div>
</section>

</div>

<footer>
    <p>&copy; 2025 SafeSteps. All rights reserved.</p>
</footer>

<script>
function getLocationAndSendAlert() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            var location = latitude + ',' + longitude;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_alert.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                var response = JSON.parse(this.responseText);
                if (response.status === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#00e5ff',
                        background: '#141c2e',
                        color: '#fff'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        confirmButtonColor: '#ff3e5e',
                        background: '#141c2e',
                        color: '#fff'
                    });
                }
            };
            xhr.send("location=" + encodeURIComponent(location));
        }, function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Location Error',
                text: error.message,
                confirmButtonColor: '#ff3e5e',
                background: '#141c2e',
                color: '#fff'
            });
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Not Supported',
            text: 'Geolocation not supported on this device.',
            confirmButtonColor: '#ff3e5e',
            background: '#141c2e',
            color: '#fff'
        });
    }
}

function confirmDelete(contactId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this contact?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff3e5e',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        background: '#141c2e',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "delete_contact.php?id=" + contactId;
        }
    });
}
</script>

</body>
</html>
