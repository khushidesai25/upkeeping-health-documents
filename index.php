<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Health Document System</title>
    <link rel="stylesheet" type="text/css" href="assests/css/style.css">
</head>
<body>
    <h2>Welcome to Health Document System</h2>
    <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
</body>
</html>
