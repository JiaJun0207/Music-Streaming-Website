<?php
// Include database connection
include 'db_connection.php'; // Ensure correct path to db_connection.php

// Initialize variables
$song = null;
$comments = [];

// Function to fetch song details
function fetchSongDetails($conn, $songID) {
    $songQuery = "SELECT * FROM Songs WHERE id = $songID";
    $songResult = mysqli_query($conn, $songQuery);

    if ($songResult && mysqli_num_rows($songResult) > 0) {
        return mysqli_fetch_assoc($songResult);
    } else {
        return null;
    }
}

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

