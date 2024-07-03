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

// Handle the image path
if (!empty($profile_image)) {
    if (strpos($profile_image, 'uploads/') === 0) {
        $image_path = $profile_image;
    } elseif (strpos($profile_image, '../uploads/') === 0) {
        $image_path = substr($profile_image, 3);
    } else {
        $image_path = 'uploads/profile/' . $profile_image;
    }
} else {
    $image_path = 'assets/pic/default.jpg';
}

// Fetch trending songs
$trending_sql = "SELECT id, song_title, artist FROM Songs ORDER BY release_date DESC LIMIT 10";
$trending_result = $conn->query($trending_sql);

// Fetch albums (grouped by artist)
$albums_sql = "SELECT artist, COUNT(*) AS album_count, MAX(profile_picture_upload) AS profile_picture FROM Songs GROUP BY artist";
$albums_result = $conn->query($albums_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikun Music Dashboard</title>
    <link rel="stylesheet" href="assets/css/Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <style>
        #logout {
            color: #ffffff;
            transition: color 0.3s;
        }
        #logout:hover {
            color: #ff0000;
        }
        #logout:hover .fas {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="navbar-logo">
            <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image"><span>IKUN MUSIC</span>
        </div>
        <div class="navbar-links-container">
            <a href="User_Home.php" class="navbar-link"><i class="fas fa-home"></i> Home</a>
            <a href="user_playlist.php" class="navbar-link"><i class="fas fa-music"></i> My Playlist</a>
            <a href="#" class="navbar-link"><i class="fas fa-th-large"></i> Categories</a>
            <a href="#" class="navbar-link"><i class="fas fa-envelope"></i> Message</a>
            <a href="Help_and_Support.html" class="navbar-link"><i class="fas fa-question-circle"></i> Help & Support</a>
            <a href="UploadForm.php" class="navbar-link"><i class="fas fa-space-shuttle"></i> Ikun Space</a>
            <a href="logout.php" class="navbar-link" id="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
        <div class="navbar-user">
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="User Image">
            <span><a href="User_Profile.php" class="profile-link"><?php echo htmlspecialchars($name); ?></a></span>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <input type="text" class="search-bar" placeholder="Artist, Album, Song, etc ...">
            <img src="assets/pic/ikun-coin.png" alt="Coins" width="50" height="50">
            <span><div class="counter">100</div></span>
        </div>
        <div class="banner">
            <h1>No need to upgrade just Support what you like!</h1>
            <p>Donate to your favorite artist to support them ヾ(•ω•`)o</p>
            <div class="banner-buttons">
                <button class="donate-button">Donate!</button>
                <button class="learn-more-button">Learn more</button>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content">
                <div class="content-header">
                    <button class="content-button">For You</button>
                    <button class="content-button">Hot</button>
                    <button class="content-button">Trend</button>
                    <button class="content-button">Shuffle</button>
                    <button class="content-button">Following</button>
                </div>
                <div class="albums">
                    <?php while ($album = $albums_result->fetch_assoc()): ?>
                        <div class="album">
                            <img src="<?php echo htmlspecialchars($album['profile_picture']); ?>" alt="Album Image">
                            <span><?php echo htmlspecialchars($album['artist']); ?></span>
                            <span><?php echo htmlspecialchars($album['album_count']) . ' Albums'; ?></span>
                            <a href="artist_home.php?artist=<?php echo urlencode($album['artist']); ?>" class="see-details">See Details</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="trending">
                <h1>Popular and Trending</h1>
                <ul>
                    <hr>
                    <?php while ($trending = $trending_result->fetch_assoc()): ?>
                        <li><a href="song_page.php?id=<?php echo $trending['id']; ?>"><?php echo htmlspecialchars($trending['song_title']) . ' - ' . htmlspecialchars($trending['artist']); ?></a></li>
                        <hr>
                    <?php endwhile; ?>
                    <h4 id="ads">Upload your production and become the next Trending! 🥳 </h4>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
