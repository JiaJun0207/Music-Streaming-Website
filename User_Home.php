<?php
session_start();

$conn = require __DIR__ . "/db_connection.php"; // Ensure database connection is established

if (isset($_SESSION["user_id"])) {
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
$trending_sql = "SELECT id, song_title, artist_id FROM songs ORDER BY release_date DESC LIMIT 10";
$trending_result = $conn->query($trending_sql);

// Fetch artist details along with the number of songs
$albums_sql = "SELECT a.artist_id, a.artist_name, a.artist_photo, COUNT(s.id) AS song_count
               FROM artist a
               LEFT JOIN songs s ON a.artist_id = s.artist_id
               GROUP BY a.artist_id";
$albums_result = $conn->query($albums_sql);

// Fetch songs
$songs_result = $conn->query("SELECT id, song_title, profile_picture_upload FROM songs");

// Check if songs_result is valid before using fetch_assoc()
if ($songs_result) {
    // Fetch songs data
    while ($song = $songs_result->fetch_assoc()) {
        // Process each song
        // Example: echo htmlspecialchars($song['song_title']);
    }
} else {
    // Handle query error or no results
    echo "Error fetching songs: " . mysqli_error($conn);
}
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

        body::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        .main-content {
            padding: 20px;
            width: calc(100% - 250px);
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-left: 40px;
            margin-right: 80px;
        }

        .albums {
            display: flex;
            overflow-x: hidden; /* Hide the scrollbar */
            white-space: nowrap; /* Prevent line breaks */
            width: 100%;
            position:relative;
            justify-content: space-evenly;
            margin-top: 5px;
            margin-bottom: 0px;
        }

        .album {
            display: inline-block;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            width: 200px;
            margin-right: 20px; /* Space between albums */
        }

        .album img {
            width: 150px;
            height: 150px;
            border-radius: 5%;
            margin-bottom: 5px;
        }

        .album span {
            display: block;
            margin-bottom: 10px;
        }
        .see-details {
            text-decoration: none;
            color: #6200ea;
        }

        .songs {
            display: flex;
            margin-top: 10px;
            flex-wrap: nowrap; /* Prevent wrapping to new lines */
            overflow-x: auto; /* Enable horizontal scrolling if necessary */
            gap: 20px;
        }

        .song-card {
            flex: 0 0 auto; /* Allow items to shrink, basis auto */
            width: calc(50% - 460px); /* Adjust width as needed */
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .song-card img {
            max-width: 150px;
            height: 150px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .song-card .song {
            margin-top: 10px;
        }

        .song-card .song span {
            display: block;
            margin-top: 5px;
            font-weight: bold;
        }

        .song-card .song .listen {
            display: block;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .song-card .song .listen:hover {
            background-color: #0056b3;
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
                <!-- Albums Section -->
                <div class="albums">
                    <?php while ($album = $albums_result->fetch_assoc()): ?>
                        <div class="album">
                            <img src="<?php echo htmlspecialchars($album['artist_photo']); ?>" alt="Artist Image">
                            <span><?php echo htmlspecialchars($album['artist_name']); ?></span>
                            <span><?php echo htmlspecialchars($album['song_count']) . ' Songs'; ?></span>
                            <a href="artist_home.php?artist_id=<?php echo urlencode($album['artist_id']); ?>" class="see-details">See Details</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="trending">
                <h1>Popular and Trending</h1>
                <ul>
                    <?php while ($trending = $trending_result->fetch_assoc()): ?>
                        <li><a href="song_page.php?id=<?php echo $trending['id']; ?>"><?php echo htmlspecialchars($trending['song_title']) . ' - ' . htmlspecialchars($trending['artist_id']); ?></a></li>
                        <hr>
                    <?php endwhile; ?>
                    <h4 id="ads">Upload your production and become the next Trending! 🥳 </h4>
                </ul>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content">
                <!-- Songs Section -->
                <div class="songs">
                    <?php
                    // Assuming $albums_result is still valid here and has been reset if needed.
                    $albums_result->data_seek(0); // Reset result set pointer if needed
                    while ($album = $albums_result->fetch_assoc()) {
                        $songs_sql = "SELECT song_title, profile_picture_upload FROM songs WHERE artist_id = ?";
                        $stmt = $conn->prepare($songs_sql);
                        $stmt->bind_param("i", $album['artist_id']);
                        $stmt->execute();
                        $stmt->bind_result($song_title, $song_profile_picture);
                        while ($stmt->fetch()): ?>
                            <div class="song-card">
                                <img src="<?php echo htmlspecialchars($song_profile_picture); ?>" alt="Song Image">
                                <div class="song">
                                    <span><?php echo htmlspecialchars($song_title); ?></span>
                                    <a href="song_page.php?id=<?php echo urlencode($album['artist_id']); ?>" class="listen">Listen</a>
                                </div>
                            </div>
                        <?php endwhile;
                        $stmt->close();
                    }?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>