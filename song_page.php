<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/Montage_page.css">
</head>
<body>
    <div class="container">
        <?php
        // Include database connection and song.php script
        include 'db_connection.php';
        include 'song.php';

        // Get song ID from URL parameter
        $songID = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Fetch song details
        $song = fetchSongDetails($conn, $songID);

        if (!$song) {
            echo "<h1>Song not found!</h1>";
            exit;
        }

        // Handle new comment submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentText'])) {
            $commentText = $_POST['commentText'];
            $result = addComment($conn, $songID, $commentText);

            if ($result) {
                // Redirect to avoid resubmission on refresh
                header("Location: {$_SERVER['REQUEST_URI']}");
                exit;
            } else {
                echo "<p>Error adding comment.</p>";
            }
        }

        // Fetch comments for the song
        $comments = fetchComments($conn, $songID);

        // Close connection
        mysqli_close($conn);
        ?>

        <header>
            <img src="<?php echo htmlspecialchars($song['profile_picture_upload']); ?>" alt="Song Cover">
            <h1><?php echo htmlspecialchars($song['song_title']); ?></h1>
            <p><?php echo htmlspecialchars($song['artist']); ?></p>
            <p><?php echo htmlspecialchars($song['categories']); ?></p>
        </header>
        <main>
            <audio controls>
                <source src="<?php echo htmlspecialchars($song['mp3_upload']); ?>" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
            <section class="comments">
                <h2>Comments</h2>
                <form id="comment-form" method="POST">
                    <textarea id="comment-text" name="commentText" placeholder="Write a Comment..." required></textarea>
                    <button type="submit" class="send-icon">Submit</button>
                </form>
                <div id="comment-list">
                    <?php foreach ($comments as $comment) { ?>
                        <div class="comment">
                            <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            <small><?php echo htmlspecialchars($comment['created_at']); ?></small>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
