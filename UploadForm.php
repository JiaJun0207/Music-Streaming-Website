<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $conn = require __DIR__ . "/db_connection.php";
    
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
    <link rel="stylesheet" href="assets/css/upload_song.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="navbar">
                <div class="navbar-logo">
                    <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image"><span>IKUN MUSIC</span>
                </div>
                <div class="navbar-links-container">
                    <a href="User_Home.html" class="navbar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="#" class="navbar-link"><i class="fas fa-search"></i> Discover</a>
                    <a href="#" class="navbar-link"><i class="fas fa-music"></i> My Playlist</a>
                    <a href="#" class="navbar-link"><i class="fas fa-th-large"></i> Categories</a>
                    <a href="#" class="navbar-link"><i class="fas fa-envelope"></i> Message</a>
                    <a href="#" class="navbar-link"><i class="fas fa-user"></i> My Profile</a>
                    <a href="#" class="navbar-link"><i class="fas fa-cog"></i> Settings</a>
                    <a href="Help and Support.html" class="navbar-link"><i class="fas fa-question-circle"></i> Help & Support</a>
                </div>
                <div class="navbar-user">
                    <img src="<?php echo $profile_image ? 'uploads/' . htmlspecialchars($profile_image) : 'assets/pic/default.jpg'; ?>" alt="User Image">
                    <span><a href="User_Home.php" class="profile-link"><?php echo htmlspecialchars($name); ?></a></span>
                </div>
            </div>
        </aside>
        <main class="main-content">
            <h1>Upload a Song</h1>
            <form id="uploadForm" action="Upload.php" method="POST" enctype="multipart/form-data">
                <label for="songTitle">Song Title<span class="required">*</span></label>
                <input type="text" id="songTitle" name="songTitle" required>

                <label for="artist">Artist<span class="required">*</span></label>
                <input type="text" id="artist" name="artist" required>

                <label for="language">Language</label>
                <select id="language" name="language">
                    <option value="english">English</option>
                    <option value="chinese">Chinese</option>
                    <option value="korean">Korean</option>
                    <option value="japanese">Japanese</option>
                </select>

                <label for="categories">Categories<span class="required">*</span></label>
                <input type="text" id="categories" name="categories" required>

                <label for="releaseDate">Release Date</label>
                <input type="date" id="releaseDate" name="releaseDate" required>

                <label for="mp3Upload">MP3 Upload<span class="required">*</span></label>
                <input type="file" id="mp3Upload" name="mp3Upload" accept="audio/mp3" required>

                <label for="profilePictureUpload">Profile Picture Upload</label>
                <input type="file" id="profilePictureUpload" name="profilePictureUpload" accept="image/*">

                <label for="backgroundPictureUpload">Background Picture Upload</label>
                <input type="file" id="backgroundPictureUpload" name="backgroundPictureUpload" accept="image/*">

                <button type="submit" name="submit">Add Song</button>
            </form>
        </main>
    </div>
    <!-- <script src="assets\js\upload_song.js"></script> -->
</body>
</html>
