<?php
include 'config.php';

session_start();
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    if (empty($name) || empty($email) || empty($password)) {
        $message = "<div class='error-msg'>All fields are required!</div>";
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $message = "<div class='success-msg'>Registration successful!</div>";
        } else {
            $message = "<div class='error-msg'>Error: " . htmlspecialchars($stmt->error) . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - SafeSteps</title>
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<style>
:root {
    --bg-primary: #0a0e17;
    --bg-secondary: #141c2e;
    --accent-primary: #00e5ff;
    --accent-secondary: #00ff9d;
    --accent-danger: #ff3e5e;
    --text-primary: #ffffff;
    --text-secondary: #a0b0c5;
    --border: #1e2c45;
    --shadow: 0 4px 20px rgba(0, 229, 255, 0.15);
    --success-shadow: 0 4px 20px rgba(0, 255, 157, 0.3);
    --error-shadow: 0 4px 20px rgba(255, 62, 94, 0.3);
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: 'Rajdhani', sans-serif;
    background-color: var(--bg-primary);
    color: var(--text-primary);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.container {
    width: 90%;
    max-width: 400px;
    padding: 2rem;
    background-color: var(--bg-secondary);
    border-radius: 10px;
    box-shadow: var(--shadow);
    z-index: 10;
    backdrop-filter: blur(10px);
    text-align: center;
    transition: 0.3s ease;
}
.container:hover {
    box-shadow: 0 6px 25px rgba(0, 229, 255, 0.3);
}
h2 {
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    -webkit-background-clip: text;
    color: transparent;
}
.input-field {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid var(--border);
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
    font-size: 1rem;
    transition: border-color 0.3s;
}
.input-field:focus {
    border-color: var(--accent-primary);
    outline: none;
    box-shadow: 0 0 8px var(--accent-primary);
}
.submit-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    color: var(--bg-primary);
    font-weight: 600;
    font-size: 1.1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}
.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0, 229, 255, 0.3);
}
.login-link {
    margin-top: 1.5rem;
    font-size: 1rem;
    color: var(--text-secondary);
}
.login-link a {
    color: var(--accent-primary);
    text-decoration: underline;
}
.login-link a:hover {
    color: var(--accent-secondary);
}
.success-msg, .error-msg {
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 1rem;
    animation: slideDown 0.5s ease;
}
.success-msg {
    background-color: rgba(0, 255, 157, 0.1);
    color: var(--accent-secondary);
    border: 1px solid rgba(0, 255, 157, 0.3);
    box-shadow: var(--success-shadow);
}
.error-msg {
    background-color: rgba(255, 62, 94, 0.1);
    color: var(--accent-danger);
    border: 1px solid rgba(255, 62, 94, 0.3);
    box-shadow: var(--error-shadow);
}
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
#particles-js {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
</style>
</head>
<body>

<div id="particles-js"></div>

<div class="container">
    <h2>Register for SafeSteps</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form action="register.php" method="POST" autocomplete="off">
        <!-- Dummy fields to prevent browser autofill -->
        <input type="text" name="fakeusernameremembered" style="display:none">
        <input type="password" name="fakepasswordremembered" style="display:none">

        <input type="text" id="name" name="name" class="input-field" placeholder="Enter your name" autocomplete="off" required><br>
        <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" autocomplete="off" required><br>
        <input type="password" id="password" name="password" class="input-field" placeholder="Enter a password" autocomplete="new-password" required><br>

        <button type="submit" class="submit-btn">Register</button>
    </form>

    <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
</div>

<script>
particlesJS("particles-js", {
    "particles": {
        "number": {
            "value": 80,
            "density": { "enable": true, "value_area": 800 }
        },
        "color": { "value": "#00e5ff" },
        "shape": { "type": "circle" },
        "opacity": {
            "value": 0.5,
            "random": true,
            "anim": { "enable": true, "speed": 1, "opacity_min": 0.1, "sync": false }
        },
        "size": {
            "value": 3,
            "random": true,
            "anim": { "enable": true, "speed": 4, "size_min": 0.1, "sync": false }
        },
        "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#00e5ff",
            "opacity": 0.4,
            "width": 1
        },
        "move": {
            "enable": true,
            "speed": 6,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "bounce": false
        }
    }
});
</script>

</body>
</html>
