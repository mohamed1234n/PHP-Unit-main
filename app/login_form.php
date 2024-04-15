<?php
use Login\classes\user;
// Auteur: mo

// Start a secure session
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        /* Custom CSS for form styling */
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; }
        button:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <h3>PHP - PDO Login and Registration</h3>
    <hr/>

    <form action="" method="POST">    
        <h4>Login here...</h4>
        <hr>
        
        <label>Username</label>
        <input type="text" name="username" required />
        <br>
        <label>Password</label>
        <input type="password" name="password" required autocomplete="current-password" />
        <br>
        <!-- Remember Me Option -->
        <input type="checkbox" name="remember_me" id="remember_me">
        <label for="remember_me">Remember Me</label>
        <br>
        <button type="submit" name="login-btn">Login</button>
        <br>
        <a href="register_form.php">Registration</a>
    </form>
        
</body>
</html>

<?php

if(isset($_POST['login-btn'])){
    require_once('user.php');
    $user = new User();

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Implement rate limiting and proper error handling

    if($user->LoginUser($username, $password)) {
        // Remember Me Feature Implementation
        if(isset($_POST['remember_me'])){
            // Set cookies or session variables for "Remember Me" functionality
        }
        $_SESSION['username'] = $username;
        header("location: index.php");
        exit();
    } else {
        // Store error message in session and display it on the form page
        $_SESSION['error'] = 'Login failed. Please check your username and password.';
        header("location: login_form.php"); // Redirect to refresh the form page
        exit();
    }
}

?>
