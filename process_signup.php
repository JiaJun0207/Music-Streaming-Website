<?php

if (empty($_POST["name"])) {
    die("Name is required");
}

if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Valid email is required");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$conn = require __DIR__ . "/db_connection.php";

$sql = "INSERT INTO users (name, email, password_hash, otp)
        VALUES (?, ?, ?)";
        
$stmt = $conn->stmt_init();

if ( ! $stmt->prepare($sql)) {
    die("SQL error: " . $conn->error);
}

$stmt->bind_param("sss",
                  $_POST["name"],
                  $_POST["email"],
                  $password_hash);
                  
if ($stmt->execute()) {
    header("Location: login.php"); // Redirect to login page after signup
    exit;
    
} else {
    
    if ($conn->errno === 1062) {
        die("Email already taken");
    } else {
        die($conn->error . " " . $conn->errno);
    }
}
?>