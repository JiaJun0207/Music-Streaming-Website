<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/../db_connection.php"; // Adjust the path to db_connection.php as needed

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables to store form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // You may need additional validation and sanitization of input fields

    // Insert user data into database
    $sql = "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // User added successfully
        header("Location: user_list.php"); // Redirect to user list page
        exit();
    } else {
        // Error inserting user
        echo json_encode(array("error" => "Failed to add user: " . $conn->error));
    }
    
    // Close statement and connection
    $stmt->close();
    $conn->close();

    exit(); // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload a User</title>
    <link rel="stylesheet" href="upload.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="navbar">
                <div class="navbar-logo">
                    <img src="../assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image">
                    <span>IKUN MUSIC</span>
                </div>
                <div class="navbar-links-container">
                    <a href="#" class="navbar-link">Dashboard</a>
                    <a href="song_list.html" class="navbar-link">Song List</a>
                    <a href="#" class="navbar-link">Artist</a>
                    <a href="user_list.php" class="navbar-link">Users</a> <!-- Update to PHP file -->
                </div>
                <a href="#" class="logout">Logout</a> <!-- Update logout link as per your implementation -->
            </div>
        </aside>
        <main class="main-content">
            <h1>Upload a User</h1>
            <form id="uploadForm" action="upload_user.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Username *</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">Add User</button>
            </form>
        </main>
    </div>
   
</body>
</html>
