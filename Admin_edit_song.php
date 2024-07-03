<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/db_connection.php"; // Adjust the path to db_connection.php as needed

// Check if song ID is provided
if (!isset($_GET['id'])) {
    // Handle error, redirect or show error message
    exit("Song ID not provided");
}

$song_id = $_GET['id'];

// Fetch song data
$sql = "SELECT * FROM songs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $song_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $song = $result->fetch_assoc();
} else {
    exit("Song not found");
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables to store form data
    $song_title = $_POST['song_title'];
    $artist = $_POST['artist'];
    $language = $_POST['language'];
    $categories = $_POST['categories'];
    $release_date = $_POST['release_date'];

    // Check if a new MP3 file is uploaded
    if ($_FILES['mp3_upload']['error'] === UPLOAD_ERR_OK) {
        $mp3_name = $_FILES['mp3_upload']['name'];
        $temp_name = $_FILES['mp3_upload']['tmp_name'];
        $mp3_path = "uploads/mp3/" . $mp3_name;

        // Move uploaded file to desired location
        if (move_uploaded_file($temp_name, $mp3_path)) {
            $mp3_upload = $mp3_path;
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        $mp3_upload = $song['mp3_upload']; // Use existing MP3 if no new one uploaded
    }

    // Check if a new profile image is uploaded (assuming you have profile and background images for songs)
    if ($_FILES['profile_picture_upload']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['profile_picture_upload']['name'];
        $temp_name = $_FILES['profile_picture_upload']['tmp_name'];
        $image_path = "uploads/profile/" . $image_name;

        // Move uploaded file to desired location
        if (move_uploaded_file($temp_name, $image_path)) {
            $profile_picture_upload = $image_path;
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        $profile_picture_upload = $song['profile_picture_upload']; // Use existing profile image if no new one uploaded
    }

    // Check if a new background image is uploaded
    if ($_FILES['background_picture_upload']['error'] === UPLOAD_ERR_OK) {
        $bg_image_name = $_FILES['background_picture_upload']['name'];
        $bg_temp_name = $_FILES['background_picture_upload']['tmp_name'];
        $bg_image_path = "uploads/background/" . $bg_image_name;

        // Move uploaded file to desired location
        if (move_uploaded_file($bg_temp_name, $bg_image_path)) {
            $background_picture_upload = $bg_image_path;
        } else {
            echo "Failed to move uploaded file.";
            exit();
        }
    } else {
        $background_picture_upload = $song['background_picture_upload']; // Use existing background image if no new one uploaded
    }

    // Update song data in database
    $sql = "UPDATE songs SET song_title=?, artist=?, language=?, categories=?, release_date=?, mp3_upload=?, profile_picture_upload=?, background_picture_upload=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $song_title, $artist, $language, $categories, $release_date, $mp3_upload, $profile_picture_upload, $background_picture_upload, $song_id);

    if ($stmt->execute()) {
        // Song updated successfully
        header("Location: Admin_song_list.php");
        exit();
    } else {
        // Error updating song
        echo "Failed to update song: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Song</title>
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
            <h1>Edit Song</h1>
            <form id="editForm" action="Admin_edit_song.php?id=<?php echo $song_id; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                <div class="form-group">
                    <label for="song_title">Song Title *</label>
                    <input type="text" id="song_title" name="song_title" value="<?php echo $song['song_title']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="artist">Artist *</label>
                    <input type="text" id="artist" name="artist" value="<?php echo $song['artist']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="language">Language *</label>
                    <input type="text" id="language" name="language" value="<?php echo $song['language']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="categories">Categories *</label>
                    <input type="text" id="categories" name="categories" value="<?php echo $song['categories']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="release_date">Release Date *</label>
                    <input type="date" id="release_date" name="release_date" value="<?php echo $song['release_date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="mp3_upload">MP3 File</label>
                    <input type="file" id="mp3_upload" name="mp3_upload" accept="audio/mp3">
                </div>
                <div class="form-group">
                    <label for="profile_picture_upload">Profile Image</label>
                    <input type="file" id="profile_picture_upload" name="profile_picture_upload" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="background_picture_upload">Background Image</label>
                    <input type="file" id="background_picture_upload" name="background_picture_upload" accept="image/*">
                </div>
                <button type="submit">Update Song</button>
            </form>
        </main>
    </div>
</body>
</html>
