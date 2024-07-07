<?php
require_once 'db_connection.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate and sanitize form inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Validate inputs (example checks, adjust as per your requirements)
    if (empty($name) || empty($email) || empty($password)) {
        die("All fields are required");
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }
    
    // Check if email already exists
    $check_email_sql = "SELECT id FROM users WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_email_sql);
    
    if ($check_email_stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }
    
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();
    
    if ($check_email_stmt->num_rows > 0) {
        die("Email address already taken");
    }
    
    $check_email_stmt->close();
    
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert into database
    $sql = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("SQL prepare error: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $name, $email, $password_hash);
    
    if ($stmt->execute()) {
        // Success: Send response back to JavaScript indicating success
        echo "User registered successfully";
    } else {
        echo "Error: " . $conn->error;
    }
    
    $stmt->close();
} else {
    // Handle cases where the script is accessed directly without POST data
    die("Invalid request");
}

$conn->close(); // Close the database connection
?>
