<?php
require 'db_connection.php'; // Add your database connection details

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    if ($password !== $password_confirmation) {
        echo 'Passwords do not match';
        exit;
    }

    // Validate token and expiry
    $query = "SELECT * FROM users WHERE reset_token='$token' AND token_expiry > NOW()";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET password_hash='$hashed_password', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'";
        if (mysqli_query($conn, $query)) {
            echo 'Password has been reset successfully';
        } else {
            echo 'Error updating password';
        }
    } else {
        echo 'Invalid or expired token';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Add a button to redirect to login.php -->
    <form action="login.php" method="get">
        <button type="submit" style="padding: 10px; border: none; border-radius: 5px; background-color: #6200ea; color: #fff; font-size: 16px; cursor: pointer; font-family: Poppins, sans-serif;">Back to Login</button>
    </form>
</body>
</html>