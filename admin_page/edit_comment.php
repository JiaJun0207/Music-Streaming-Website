<?php
// Include database connection
include '../db_connection.php'; // Ensure correct path to db_connection.php

// Initialize variables
$song = null;
$comments = [];

// Function to fetch comments for a song
function fetchComments($conn, $songID) {
    $comments = [];
    $commentsQuery = "SELECT c.*, u.name FROM Comments c JOIN users u ON c.user_id = u.user_id WHERE song_id = $songID ORDER BY created_at DESC";
    $commentsResult = mysqli_query($conn, $commentsQuery);

    if ($commentsResult) {
        while ($row = mysqli_fetch_assoc($commentsResult)) {
            $comments[] = $row;
        }
    }

    return $comments;
}

// Handle new comment submission
function addComment($conn, $songID, $userID, $commentText) {
    $commentText = mysqli_real_escape_string($conn, $commentText);
    $insertCommentQuery = "INSERT INTO Comments (song_id, user_id, comment_text) VALUES ($songID, $userID, '$commentText')";
    $result = mysqli_query($conn, $insertCommentQuery); 

    return $result;
}

// Handle comment deletion
function deleteComment($conn, $commentID) {
    $deleteCommentQuery = "DELETE FROM Comments WHERE id = $commentID";
    $result = mysqli_query($conn, $deleteCommentQuery);
    
    return $result;
}

// Handle comment editing
function editComment($conn, $commentID, $newCommentText) {
    $newCommentText = mysqli_real_escape_string($conn, $newCommentText);
    $editCommentQuery = "UPDATE Comments SET comment_text = '$newCommentText' WHERE id = $commentID";
    $result = mysqli_query($conn, $editCommentQuery);
    
    return $result;
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
if (isset($_GET['song_id'])) {
    $songID = $_GET['song_id'];
    $comments = fetchComments($conn, $songID);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Comments Page</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
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
</body>
</html>
