<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$title = $artist = $album = $description = $cover_image = $audio_url = "";
$uploadOk = 1;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'];
    $description = $_POST['description'];

    // File upload handling
    $target_dir = "uploads/";
    $audio_file = $target_dir . basename($_FILES["audio_file"]["name"]);
    $cover_image = $target_dir . basename($_FILES["cover_image"]["name"]);

    $audioFileType = strtolower(pathinfo($audio_file, PATHINFO_EXTENSION));
    $imageFileType = strtolower(pathinfo($cover_image, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($audio_file)) {
        echo "Sorry, audio file already exists.";
        $uploadOk = 0;
    }
    if (file_exists($cover_image)) {
        echo "Sorry, cover image file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["audio_file"]["size"] > 500000) {
        echo "Sorry, your audio file is too large.";
        $uploadOk = 0;
    }
    if ($_FILES["cover_image"]["size"] > 500000) {
        echo "Sorry, your cover image is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($audioFileType != "mp3") {
        echo "Sorry, only MP3 files are allowed.";
        $uploadOk = 0;
    }
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move uploaded files to target directory
        if (move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file) && move_uploaded_file($_FILES["cover_image"]["tmp_name"], $cover_image)) {
            echo "The files have been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your files.";
        }
    }

    // Insert song details into the database
    $sql = "INSERT INTO songs (title, artist, album, cover_image, audio_url, description) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssssss", $title, $artist, $album, $cover_image, $audio_file, $description);

    if ($stmt->execute()) {
        echo "New song added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Song</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .upload-form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        .upload-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .upload-form input[type="text"], .upload-form textarea, .upload-form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .upload-form button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .upload-form button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="upload-form">
    <h2>Upload Song</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="title">Song Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="artist">Artist:</label>
        <input type="text" id="artist" name="artist" required>

        <label for="album">Album:</label>
        <input type="text" id="album" name="album" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"></textarea>

        <label for="cover_image">Cover Image (JPG, JPEG, PNG, GIF):</label>
        <input type="file" id="cover_image" name="cover_image" accept=".jpg, .jpeg, .png, .gif" required>

        <label for="audio_file">Audio File (MP3):</label>
        <input type="file" id="audio_file" name="audio_file" accept=".mp3" required>

        <button type="submit">Upload Song</button>
    </form>
</div>

</body>
</html>