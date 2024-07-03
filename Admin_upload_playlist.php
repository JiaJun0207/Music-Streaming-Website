<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playlistName = mysqli_real_escape_string($conn, $_POST['playlistName']);
    $userId = 1; // Example user_id, replace with the actual user ID from your session or logic
    $imagePath = '';

    // Handle file upload
    if (isset($_FILES['playlistImage']) && $_FILES['playlistImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . 'uploads/playlist_images/';
        $uploadFile = $uploadDir . basename($_FILES['playlistImage']['name']);

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['playlistImage']['tmp_name'], $uploadFile)) {
            $imagePath = 'uploads/playlist_images/' . basename($_FILES['playlistImage']['name']);
        } else {
            echo "Error uploading image.";
        }
    } else {
        // Handle different upload errors
        switch ($_FILES['playlistImage']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                echo "File is too large.";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "File was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "File upload stopped by extension.";
                break;
            default:
                echo "Unknown upload error.";
                break;
        }
    }

    $sql = "INSERT INTO playlists (playlist_name, user_id, playlist_image) VALUES ('$playlistName', '$userId', '$imagePath')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: Admin_playlist_list.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Playlist</title>
    <link rel="stylesheet" href="Admin_upload.css">
</head>
<body>
    <div class="container">
    <aside class="sidebar">
            <div class="navbar">
                <div class="navbar-logo">
                    <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image">
                    <span>IKUN MUSIC</span>
                </div>
                <div class="navbar-links-container">
                    <a href="Admin_dashboard.php" class="navbar-link">Dashboard</a>
                    <a href="Admin_playlist_list.php" class="navbar-link">Playlist List</a>
                    <a href="Admin_song_list.php" class="navbar-link">Song List</a>
                    <a href="Admin_edit_comment.php" class="navbar-link">Comment List</a>
                    <a href="Admin_artist_list.php" class="navbar-link">Artist List</a>
                    <a href="Admin_user_list.php" class="navbar-link">Users List</a>
                </div>
                <a href="index.php" class="logout">Logout</a>
            </div>
        </aside>
        <main class="main-content">
        <h1>Add New Playlist</h1>
        <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="playlistName">Playlist Name:</label>
            <input type="text" id="playlistName" name="playlistName" required>
            </div>
        <div class="form-group">
            <label for="playlistImage">Playlist Image:</label>
            <input type="file" id="playlistImage" name="playlistImage">
            </div>
            <button type="submit">Add Playlist</button>
        </form>
        </main>
    </div>
</body>
</html>
