<?php
session_start();

// Include database connection
$conn = require __DIR__ . "/../db_connection.php"; // Adjust the path to db_connection.php as needed

// Check if song ID is provided
if (!isset($_GET['id'])) {
    exit("Song ID not provided");
}

$song_id = $_GET['id'];

// Debugging: Output the received song ID
echo "Song ID received: " . $song_id . "<br>";

// Fetch song details to verify existence
$songQuery = "SELECT * FROM Songs WHERE id = ?";
$stmt = $conn->prepare($songQuery);
$stmt->bind_param("i", $song_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit("Song with ID $song_id not found");
}

// Function to fetch comments for a song
function fetchComments($conn, $songID) {
    $comments = [];
    $commentsQuery = "SELECT c.*, u.name FROM Comments c JOIN users u ON c.user_id = u.user_id WHERE song_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($commentsQuery);
    $stmt->bind_param("i", $songID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
    }
    
    return $comments;
}

// Handle comment deletion
function deleteComment($conn, $commentID) {
    $deleteCommentQuery = "DELETE FROM Comments WHERE id = ?";
    $stmt = $conn->prepare($deleteCommentQuery);
    $stmt->bind_param("i", $commentID);
    return $stmt->execute();
}

// Handle comment editing
function editComment($conn, $commentID, $newCommentText) {
    $newCommentText = $conn->real_escape_string($newCommentText);
    $editCommentQuery = "UPDATE Comments SET comment_text = ? WHERE id = ?";
    $stmt = $conn->prepare($editCommentQuery);
    $stmt->bind_param("si", $newCommentText, $commentID);
    return $stmt->execute();
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $commentID = $_POST['comment_id'];
        deleteComment($conn, $commentID);
    } elseif (isset($_POST['edit'])) {
        $commentID = $_POST['comment_id'];
        $newCommentText = $_POST['comment_text'];
        editComment($conn, $commentID, $newCommentText);
    }
}

// Fetch comments for the specified song
$comments = fetchComments($conn, $song_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Comments Page</title>
    <link rel="stylesheet" href="upload.css"> <!-- Adjust the path to your CSS file -->
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
                    <a href="playlist_list.php" class="navbar-link">Playlist List</a>
                    <a href="song_list.php" class="navbar-link">Song List</a>
                    <a href="edit_comment.php" class="navbar-link">Comment List</a>
                    <a href="artist_list.php" class="navbar-link">Artist List</a>
                    <a href="user_list.php" class="navbar-link">Users List</a>
                </div>
                <a href="#" class="logout">Logout</a>
            </div>
        </aside>
        <main class="main-content">
            <h1>Admin Comments Page</h1>
            <table>
                <thead>
                    <tr>
                        <th>Comment ID</th>
                        <th>User Name</th>
                        <th>Comment Text</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?php echo $comment['id']; ?></td>
                            <td><?php echo $comment['name']; ?></td>
                            <td><?php echo $comment['comment_text']; ?></td>
                            <td><?php echo $comment['created_at']; ?></td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <button type="submit" name="delete">Delete</button>
                                </form>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                    <input type="text" name="comment_text" value="<?php echo htmlspecialchars($comment['comment_text']); ?>">
                                    <button type="submit" name="edit">Edit</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
