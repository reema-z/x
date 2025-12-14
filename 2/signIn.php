<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password!";
    } else {
        // Check if user exists (by ID or email)
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id_number = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['id_number'] = $user['id_number'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            // Redirect to homepage
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password. Please try again or register first.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/signUp.css">
    <style>
        .error-message {
            color: #d32f2f;
            background-color: #ffebee;
            border: 1px solid #ef9a9a;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
        .success-message {
            color: #2e7d32;
            background-color: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="top-bar">
            <a href="mailto:445001472@sm.edu.imamu.sa">Email: 445001472@sm.edu.imamu.sa</a> |
            <a href="tel:+966552616596">Phone: +966 552616596</a> |
            <a href="https://www.linkedin.com/in/reema-alzoman-6b30732a7?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank">LinkedIn</a> |
            <a href="https://github.com/reema-z/web-devolpment-project.git" target="_blank">GitHub</a>
        </div>
    </header>
    
    <?php include 'navbar.php'; ?>
    
    <main class="centered-container">
        <div class="sign-in-box">
            <h2>SIGN IN</h2>
            
            <?php if (isset($_GET['registered']) && $_GET['registered'] == 'success'): ?>
                <div class="success-message">
                    Registration successful! Please sign in with your credentials.
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form id="signInForm" method="POST" action="">
                <label for="username">Username (ID or Email):</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter your ID or email">
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
                
                <button type="submit">Sign In</button>
            </form>
            
            <p class="small-text-link">
                Don't have an account? <a href="Registration.php">Register New Account</a>
            </p>
        </div>
    </main>
    
    <footer>
        &copy;2025-26 / IMSIU / CCIS<sup>TM</sup>
    </footer>
</body>
</html>