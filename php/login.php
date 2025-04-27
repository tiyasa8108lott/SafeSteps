<?php

include 'config.php';

session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - SafeSteps</title>
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
    overflow: hidden;
    position: relative;
}
.container {
    background-color: var(--bg-secondary);
    padding: 2rem;
    border-radius: 10px;
    box-shadow: var(--shadow);
    max-width: 400px;
    width: 90%;
    z-index: 10;
    text-align: center;
    transition: all 0.3s ease;
}
.container:hover {
    box-shadow: 0 6px 25px rgba(0, 229, 255, 0.3);
}
h2 {
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
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
.register-link {
    margin-top: 1.5rem;
    font-size: 1rem;
    color: var(--text-secondary);
}
.register-link a {
    color: var(--accent-primary);
    text-decoration: underline;
}
.register-link a:hover {
    color: var(--accent-secondary);
}
#error-message {
    background-color: rgba(255, 62, 94, 0.1);
    color: var(--accent-danger);
    padding: 10px;
    border: 1px solid rgba(255, 62, 94, 0.3);
    border-radius: 6px;
    margin-bottom: 1rem;
    box-shadow: var(--error-shadow);
    animation: slideDown 0.5s ease;
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
    <h2>Login to SafeSteps</h2>

    <?php if (!empty($error)): ?>
        <div id="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST" autocomplete="off">
        <!-- Dummy hidden fields to prevent autofill -->
        <input type="text" name="fakeusernameremembered" style="display:none">
        <input type="password" name="fakepasswordremembered" style="display:none">

        <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" autocomplete="off" required><br>
        <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" autocomplete="new-password" required><br>

        <button type="submit" class="submit-btn">Login</button>
    </form>

    <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
</div>

<script>
particlesJS("particles-js", {
    "particles": {
        "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
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
