<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/db_connection.php";

// Initialize variables to store counts
$songCount = 0;
$artistCount = 0;
$playlistCount = 0;
$commentCount = 0;
$userCount = 0;

// Fetch the count of songs
$songResult = $conn->query("SELECT COUNT(*) as count FROM songs");
if ($songResult) {
    $songCount = $songResult->fetch_assoc()['count'];
}

// Fetch the count of artists
$artistResult = $conn->query("SELECT COUNT(*) as count FROM artist");
if ($artistResult) {
    $artistCount = $artistResult->fetch_assoc()['count'];
}

// Fetch the count of playlists
$playlistResult = $conn->query("SELECT COUNT(*) as count FROM playlists"); // Adjust the table name if necessary
if ($playlistResult) {
    $playlistCount = $playlistResult->fetch_assoc()['count'];
}

// Fetch the count of comments
$commentResult = $conn->query("SELECT COUNT(*) as count FROM comments"); // Adjust the table name if necessary
if ($commentResult) {
    $commentCount = $commentResult->fetch_assoc()['count'];
}

// Fetch the count of users
$userResult = $conn->query("SELECT COUNT(*) as count FROM users"); // Adjust the table name if necessary
if ($userResult) {
    $userCount = $userResult->fetch_assoc()['count'];
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Admin_list.css">
    <style>
        
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .stat {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            width: 200px;
            text-align: center;
            margin: 10px;
        }
        .stat h2 {
            margin: 10px 0;
        }
    </style>
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
            <h1>Admin Dashboard</h1>
            <div class="stats">
                <div class="stat">
                    <h2><?php echo $songCount; ?></h2>
                    <p>Songs</p>
                </div>
                <div class="stat">
                    <h2><?php echo $artistCount; ?></h2>
                    <p>Artists</p>
                </div>
                <div class="stat">
                    <h2><?php echo $playlistCount; ?></h2>
                    <p>Playlists</p>
                </div>
                <div class="stat">
                    <h2><?php echo $commentCount; ?></h2>
                    <p>Comments</p>
                </div>
                <div class="stat">
                    <h2><?php echo $userCount; ?></h2>
                    <p>Users</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
