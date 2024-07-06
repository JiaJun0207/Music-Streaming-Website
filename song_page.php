<?php
session_start(); // Start the session

include 'db_connection.php';
include 'song.php';

$songID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$song = fetchSongDetails($conn, $songID);

if (!$song) {
    header("Location: error.php");
    exit;
}

$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userID) {
    header("Location: login.php");
    exit;
}

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
            position: relative;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
            outline: none;
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
        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .toast {
            visibility: hidden;
            min-width: 300px;
            margin-left: -150px;
            background-color: #d4edda;
            color: #155724;
            text-align: left;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 1001;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        .toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 3.5s;
            animation: fadein 0.5s, fadeout 0.5s 3.5s;
        }
        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;} 
            to {bottom: 30px; opacity: 1;}
        }
        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;} 
            to {bottom: 0; opacity: 0;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        .toast .icon {
            margin-right: 10px;
            font-size: 20px;
        }
        .toast .close {
            margin-left: auto;
            cursor: pointer;
        }
        .audio-player {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            border-radius: 10px;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .audio-controls {
            display: flex;
            align-items: center;
        }
        .audio-controls button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            margin: 0 10px;
            color: #333;
        }
        .audio-progress {
            flex-grow: 1;
            margin: 0 20px;
        }
        .audio-progress input {
            width: 100%;
        }
        .audio-time {
            display: flex;
            justify-content: space-between;
            width: 50px;
            font-size: 14px;
            color: #666;
        }
        .volume-control {
            display: flex;
            align-items: center;
        }
        .volume-control input {
            width: 100px;
            margin-left: 10px;
        }
        .download-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="close-button" onclick="goBack()">&times;</button>
        <header>
            <img src="<?php echo htmlspecialchars($song['profile_picture_upload']); ?>" alt="Song Cover">
            <h1><?php echo htmlspecialchars($song['song_title']); ?></h1>
            <p><?php echo htmlspecialchars($song['artist_name']); ?></p>
            <p><?php echo htmlspecialchars($song['categories']); ?></p>
            <form method="POST">
                <button type="submit" name="like" class="like-button"><i class="far fa-heart"></i></button>
            </form>
        </header>
        <main>
            <div class="audio-player">
                <div class="audio-controls">
                    <button onclick="togglePlayPause()"><i id="play-pause-icon" class="fas fa-play"></i></button>
                </div>
                <div class="audio-progress">
                    <input type="range" id="progress-bar" value="0" max="100">
                </div>
                <div class="audio-time">
                    <span id="current-time">00:00</span>
                </div>
                <div class="volume-control">
                    <i class="fas fa-volume-up"></i>
                    <input type="range" id="volume-bar" min="0" max="1" step="0.01" value="0.4">
                </div>
                <a href="<?php echo htmlspecialchars($song['mp3_upload']); ?>" download class="download-button">
                    <i class="fas fa-download"></i>
                </a>
            </div>
            <audio id="audio-player" volume="0.4">
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
                            <div class="comment-header">
                                <img src="<?php echo htmlspecialchars($comment['profile_image']); ?>" alt="Profile Picture" class="profile-image">
                                <p><strong><?php echo htmlspecialchars($comment['name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            </div>
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

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <span class="icon"><i class="fas fa-check-circle"></i></span>
        <span class="message"></span>
        <span class="close" onclick="hideToast()">&times;</span>
    </div>

    <script>
        const audioPlayer = document.getElementById('audio-player');
        const playPauseIcon = document.getElementById('play-pause-icon');
        const progressBar = document.getElementById('progress-bar');
        const currentTimeElem = document.getElementById('current-time');
        const volumeBar = document.getElementById('volume-bar');

        audioPlayer.volume = 0.4;

        audioPlayer.addEventListener('timeupdate', () => {
            const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.value = progress;
            currentTimeElem.textContent = formatTime(audioPlayer.currentTime);
        });

        audioPlayer.addEventListener('ended', () => {
            playPauseIcon.className = 'fas fa-play';
            progressBar.value = 0;
            currentTimeElem.textContent = formatTime(0);
        });

        progressBar.addEventListener('input', () => {
            audioPlayer.currentTime = (progressBar.value / 100) * audioPlayer.duration;
        });

        volumeBar.addEventListener('input', () => {
            audioPlayer.volume = volumeBar.value;
        });

        function togglePlayPause() {
            if (audioPlayer.paused) {
                audioPlayer.play();
                playPauseIcon.className = 'fas fa-pause';
            } else {
                audioPlayer.pause();
                playPauseIcon.className = 'fas fa-play';
            }
        }

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
        }

        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.querySelector(".message").innerText = message;
            toast.className = "toast show";
            setTimeout(function(){ hideToast(); }, 4000);
        }

        function hideToast() {
            var toast = document.getElementById("toast");
            toast.className = toast.className.replace("show", "");
        }

        function goBack() {
            window.history.back();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const likeButton = document.querySelector('.like-button');

            likeButton.addEventListener('click', function() {
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
