<?php
session_start();

include 'db_connection.php';

$artist_id = isset($_GET['artist_id']) ? $_GET['artist_id'] : '';

if ($artist_id) {
    // Fetch artist details
    $artistQuery = "SELECT * FROM artist WHERE artist_id = ?";
    $stmt = $conn->prepare($artistQuery);
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $artistResult = $stmt->get_result();

    if ($artistResult->num_rows > 0) {
        $artist = $artistResult->fetch_assoc();
        
        // Fetch songs by artist
        $songsQuery = "SELECT * FROM songs WHERE artist_id = ?";
        $stmt = $conn->prepare($songsQuery);
        $stmt->bind_param("i", $artist_id);
        $stmt->execute();
        $songsResult = $stmt->get_result();
    } else {
        echo "Artist not found.";
    }
} else {
    echo "Invalid artist ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist Name'); ?> - Artist Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/artist_page.css">
    <!-- Include any additional CSS or scripts here -->
</head>
<body>
    <div class="container">
        <header class="artist-header">
            <img class="artist-photo" src="<?php echo htmlspecialchars($artist['artist_photo'] ?? ''); ?>" alt="Artist Image">
            <div class="artist-details">
                <h1 class="artist-name"><?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist Name'); ?></h1>
                <p class="artist-email"><?php echo htmlspecialchars($artist['artist_email'] ?? ''); ?></p>
                <p class="artist-social">
                    <a href="<?php echo htmlspecialchars($artist['artist_youtube'] ?? '#'); ?>" target="_blank">YouTube</a>
                </p>
            </div>
        </header>
        <section class="song-section">
            <h2 class="section-title">Songs by <?php echo htmlspecialchars($artist['artist_name'] ?? 'Artist Name'); ?></h2>
            <ul class="song-list">
                <?php while ($song = $songsResult->fetch_assoc()): ?>
                    <li class="song-item">
                        <a href="song_page.php?id=<?php echo $song['id']; ?>" class="song-link">
                            <?php echo htmlspecialchars($song['song_title']); ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </section>
    </div>
</body>
</html>