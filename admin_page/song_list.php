<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/../db_connection.php";

// Initialize variable to store songs data
$songs = [];

// Fetch song data
$sql = "SELECT id, song_title, artist, language, categories, release_date, mp3_upload, profile_picture_upload, background_picture_upload FROM songs";
$result = $conn->query($sql);

// Check if query execution was successful
if ($result) {
    // Fetch all rows as associative array
    $songs = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Query execution failed
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Song List</title>
    <link rel="stylesheet" href="list.css">
    <style>
    .profile-image, .background-image {
        max-width: 100%;
        max-height: 45px;
        width: auto;
        height: auto;
        display: block;
        margin-top: 10px;
        object-fit: contain;
        align-items: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="navbar">
                <div class="navbar-logo">
                    <img src="../assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image">
                    <span>IKUN MUSIC</span>
                </div>
                <div class="navbar-links-container">
                    <a href="dashboard.php" class="navbar-link">Dashboard</a>
                    <a href="song_list.php" class="navbar-link">Song List</a>
                    <a href="artist_list.php" class="navbar-link">Artist</a>
                    <a href="user_list.php" class="navbar-link">Users</a>
                </div>
                <a href="../index.php" class="logout">Logout</a> <!-- Replace with your logout page -->
            </div>   
        </aside>
        <main class="main-content">
            <header>
                <input type="text" name="search" placeholder="Artist, Album, Song, etc...">
            </header>
            <h1>Song List</h1>
            <button id="addNewBtn">Add New</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Song Title</th>
                        <th>Artist</th>
                        <th>Language</th>
                        <th>Categories</th>
                        <th>Release Date</th>
                        <th>MP3 File</th>
                        <th>Profile Image</th>
                        <th>Background Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="songList">
                    <?php foreach ($songs as $song): ?>
                        <tr>
                            <td><?php echo $song['id']; ?></td>
                            <td><?php echo $song['song_title']; ?></td>
                            <td><?php echo $song['artist']; ?></td>
                            <td><?php echo $song['language']; ?></td>
                            <td><?php echo $song['categories']; ?></td>
                            <td><?php echo $song['release_date']; ?></td>
                            <td>
                                <?php if (!empty($song['mp3_upload'])): ?>
                                    <a href="<?php echo $song['mp3_upload']; ?>" target="_blank">Listen</a>
                                <?php else: ?>
                                    No MP3 available
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($song['profile_picture_upload'])): ?>
                                    <img src="image.php?path=<?php echo $song['profile_picture_upload']; ?>" alt="Profile Image" class="profile-image">
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($song['background_picture_upload'])): ?>
                                    <img src="image.php?path=<?php echo $song['background_picture_upload']; ?>" alt="Background Image" class="background-image">
                                <?php else: ?>
                                    No image available
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <button class="edit" onclick="editSong(<?php echo $song['id']; ?>)">✏️</button>
                                <button class="delete" onclick="deleteSong(<?php echo $song['id']; ?>)">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($songs)): ?>
                        <tr><td colspan="10">No songs found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function editSong(id) {
            window.location.href = `edit_song.php?id=${id}`;
        }

        function deleteSong(id) {
            if (confirm('Are you sure you want to delete this song?')) {
                // Send AJAX request to delete song
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_song.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Refresh the page after deletion
                        window.location.reload();
                    } else {
                        alert('Failed to delete song. Please try again.');
                    }
                };
                xhr.send('id=' + id);
            }
        }

        document.getElementById('addNewBtn').addEventListener('click', function() {
            window.location.href = 'upload_song.php'; // Navigate to the upload song page
        });
    </script>
</body>
</html>
