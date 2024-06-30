<?php
include 'db_connection.php';
include 'song.php';

$songID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$song = fetchSongDetails($conn, $songID);

if (!$song) {
    header("Location: error.php");
    exit;
}

$userID = 1; // For example purposes, replace with actual user ID

// Check if song is already liked by the user
$isLikedQuery = $conn->prepare("SELECT COUNT(*) as count FROM liked_songs WHERE user_id = ? AND song_id = ?");
$isLikedQuery->bind_param("ii", $userID, $songID);
$isLikedQuery->execute();
$result = $isLikedQuery->get_result();
$likeStatus = $result->fetch_assoc()['count'] > 0;
$isLikedQuery->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['commentText'])) {
        $commentText = $_POST['commentText'];
        $result = addComment($conn, $songID, $userID, $commentText);

        if ($result) {
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit;
        } else {
            $error = "Error adding comment.";
        }
    } elseif (isset($_POST['like'])) {
        if ($likeStatus) {
            // Remove from Liked Songs playlist
            $deleteQuery = $conn->prepare("DELETE FROM liked_songs WHERE user_id = ? AND song_id = ?");
            $deleteQuery->bind_param("ii", $userID, $songID);
            $deleteQuery->execute();
            $deleteQuery->close();
            $likeStatus = false; // Update like status
        } else {
            // Add to Liked Songs playlist
            $likeQuery = $conn->prepare("INSERT INTO liked_songs (user_id, song_id) VALUES (?, ?)");
            $likeQuery->bind_param("ii", $userID, $songID);
            $likeQuery->execute();
            $likeQuery->close();
            $likeStatus = true; // Update like status
        }
    }
}

$comments = fetchComments($conn, $songID);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/song_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body::before {
            content: "";
            background: url('<?php echo htmlspecialchars($song['background_picture_upload']); ?>') no-repeat center center/cover;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(8px);
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
        }
        .like-button {
            font-size: 24px;
            color: <?php echo $likeStatus ? '#ff0000' : '#ccc'; ?>;
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
            outline: none;
        }
        .like-button:hover {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="<?php echo htmlspecialchars($song['profile_picture_upload']); ?>" alt="Song Cover">
            <h1><?php echo htmlspecialchars($song['song_title']); ?></h1>
            <p><?php echo htmlspecialchars($song['artist']); ?></p>
            <p><?php echo htmlspecialchars($song['categories']); ?></p>
            <form method="POST">
                <button type="submit" name="like" class="like-button"><i class="far fa-heart"></i></button>
            </form>
        </header>
        <main>
            <audio id="audio-player" controls>
                <source src="<?php echo htmlspecialchars($song['mp3_upload']); ?>" type="audio/mp3">
                Your browser does not support the audio element.
            </audio>
            <section class="comments">
                <h2>Comments</h2>
                <form id="comment-form" method="POST">
                    <textarea id="comment-text" name="commentText" placeholder="Write a Comment..." required></textarea>
                    <button type="submit" class="send-button">Submit</button>
                </form>
                <div id="comment-list">
                    <?php foreach ($comments as $comment) { ?>
                        <div class="comment">
                            <p><strong><?php echo htmlspecialchars($comment['name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            <small><?php echo htmlspecialchars($comment['created_at']); ?></small>
                        </div>
                    <?php } ?>
                </div>
                <?php if (isset($error)) { ?>
                    <p><?php echo $error; ?></p>
                <?php } ?>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const likeButton = document.querySelector('.like-button');

            likeButton.addEventListener('click', function() {
                // Toggle like status visually
                if (likeButton.style.color === 'rgb(255, 0, 0)') {
                    likeButton.style.color = '#ccc';
                } else {
                    likeButton.style.color = '#ff0000';
                }
            });
        });
    </script>
</body>
</html>
