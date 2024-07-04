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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="<?php echo htmlspecialchars($song['profile_picture_upload']); ?>" alt="Song Cover">
            <h1><?php echo htmlspecialchars($song['song_title']); ?></h1>
            <p><?php echo htmlspecialchars($song['artist_name']); ?></p> <!-- Updated to display artist_name -->
            <p><?php echo htmlspecialchars($song['categories']); ?></p>
            <button class="like-button" id="like-button"><i class="far fa-heart"></i></button>
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
        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.querySelector(".message").innerText = message;
            toast.className = "toast show";
            setTimeout(function(){ hideToast(); }, 4000); // Show toast for 4 seconds
        }

        function hideToast() {
            var toast = document.getElementById("toast");
            toast.className = toast.className.replace("show", "");
        }

        document.getElementById('like-button').addEventListener('click', function() {
            const likeButton = this;

            fetch('like_song.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ songID: <?php echo $songID; ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeButton.style.color = data.liked ? '#ff0000' : '#ccc';
                    showToast(data.message);
                } else {
                    showToast(data.message);
                }
            });
        });

        document.getElementById('comment-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const commentText = document.getElementById('comment-text').value;

            fetch('add_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ songID: <?php echo $songID; ?>, commentText: commentText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentList = document.getElementById('comment-list');
                    const newComment = document.createElement('div');
                    newComment.className = 'comment';
                    newComment.innerHTML = `
                        <div class="comment-header">
                            <img src="${data.comment.profile_image}" alt="Profile Picture" class="profile-image">
                            <p><strong>${data.comment.name}:</strong> ${data.comment.comment_text}</p>
                        </div>
                        <small>${data.comment.created_at}</small>
                    `;
                    commentList.insertBefore(newComment, commentList.firstChild);
                    showToast(data.message);
                } else {
                    showToast(data.message);
                }
            });
        });
    </script>
</body>
</html
