<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $conn = require __DIR__ . "/../db_connection.php";
    
    $sql = "SELECT name, profile_image FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($name, $profile_image);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload a Song</title>
    <link rel="stylesheet" href="upload.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
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
                    <a href="dashboard.php" class="navbar-link">Dashboard</a>
                    <a href="song_list.php" class="navbar-link">Song List</a>
                    <a href="artist_list.php" class="navbar-link">Artist</a>
                    <a href="user_list.php" class="navbar-link">Users</a>
                </div>
                <a href="#" class="logout">Logout</a>
            </div>
        </aside>
        <main class="main-content">
            <h1>Upload a Song</h1>
            <form id="uploadForm" action="../Upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="songTitle">Song Title<span class="required">*</span></label>
                <input type="text" id="songTitle" name="songTitle" required>
            </div>
            <div class="form-group">
                <label for="artist">Artist<span class="required">*</span></label>
                <input type="text" id="artist" name="artist" required>
            </div>
            <div class="form-group">
                <label for="language">Language</label>
                <select id="language" name="language">
                    <option value="english">English</option>
                    <option value="chinese">Chinese</option>
                    <option value="korean">Korean</option>
                    <option value="japanese">Japanese</option>
                </select>
            </div>
            <div class="form-group">
                <label for="categories">Categories<span class="required">*</span></label>
                <input type="text" id="categories" name="categories" required>
            </div>
            <div class="form-group">
                <label for="releaseDate">Release Date</label>
                <input type="date" id="releaseDate" name="releaseDate" required>
            </div>
            <div class="form-group">
                <label for="mp3Upload">MP3 Upload<span class="required">*</span></label>
                <input type="file" id="mp3Upload" name="mp3Upload" accept="audio/mp3" required>
            </div>
            <div class="form-group">
                <label for="profilePictureUpload">Profile Picture Upload</label>
                <input type="file" id="profilePictureUpload" name="profilePictureUpload" accept="image/*">
            </div>
            <div class="form-group">
                <label for="backgroundPictureUpload">Background Picture Upload</label>
                <input type="file" id="backgroundPictureUpload" name="backgroundPictureUpload" accept="image/*">
            </div>
                <button type="submit" name="submit">Add Song</button>
            </form>
        </main>
    </div>
</body>
</html>