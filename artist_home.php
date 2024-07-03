<?php
session_start();

include 'db_connection.php';

$artist_name = isset($_GET['artist']) ? $conn->real_escape_string($_GET['artist']) : '';
$songs = [];
$artist = [];

if ($artist_name) {
    $songsQuery = "SELECT * FROM Songs WHERE artist = '$artist_name'";
    $songsResult = $conn->query($songsQuery);

    while ($row = $songsResult->fetch_assoc()) {
        $songs[] = $row;
    }

    $artistQuery = "SELECT * FROM artist WHERE artist_name = '$artist_name'";
    $artistResult = $conn->query($artistQuery);

    if ($artistResult->num_rows > 0) {
        $artist = $artistResult->fetch_assoc();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artist_name); ?> - Artist Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/artist_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(8px);
        }
        .container {
            background: url('<?php echo '/uploads/background/' . htmlspecialchars($artist['artist_photo'] ?? ''); ?>') no-repeat center center/cover;
            padding: 20px;
        }
        .artist-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .artist-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .artist-header h1 {
            font-size: 3em;
            margin: 0;
        }
        .song-list {
            list-style: none;
            padding: 0;
        }
        .song-list li {
            margin: 10px 0;
        }
        .song-list a {
            text-decoration: none;
            color: black;
            font-weight: 600;
        }
        .song-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="artist-header">
            <img src="<?php echo '/uploads/artist/' . htmlspecialchars($artist['artist_photo'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($artist_name); ?> Photo">
            <div>
                <h1><?php echo htmlspecialchars($artist_name); ?></h1>
                <p><?php echo htmlspecialchars($artist['artist_email'] ?? ''); ?></p>
                <p><a href="<?php echo htmlspecialchars($artist['artist_youtube'] ?? '#'); ?>" target="_blank">YouTube</a></p>
            </div>
        </div>
        <h2>Songs by <?php echo htmlspecialchars($artist_name); ?></h2>
        <ul class="song-list">
            <?php foreach ($songs as $song): ?>
                <li>
                    <a href="song_page.php?id=<?php echo $song['id']; ?>">
                        <?php echo htmlspecialchars($song['song_title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
