<?php
// Database connection setup
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

// Function to get song details by ID
function getSongDetails($song_id, $conn) {
    $sql = "SELECT * FROM songs WHERE song_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Get song ID from URL parameters
$song_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$songDetails = getSongDetails($song_id, $conn);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($songDetails['title']); ?></title>
    <link rel="stylesheet" href="..css\phpSSP.css">
</head>
<body>
    <div class="song-container">
        <div class="song-info">
            <img src="<?php echo htmlspecialchars($songDetails['cover_image']); ?>" alt="Song Cover">
            <h1><?php echo htmlspecialchars($songDetails['title']); ?></h1>
            <p><?php echo htmlspecialchars($songDetails['artist']); ?></p>
            <p><?php echo htmlspecialchars($songDetails['album']); ?></p>
            <audio controls>
                <source src="<?php echo htmlspecialchars($songDetails['audio_url']); ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
        <div class="song-description">
            <p><?php echo nl2br(htmlspecialchars($songDetails['description'])); ?></p>
        </div>
    </div>
</body>
</html>