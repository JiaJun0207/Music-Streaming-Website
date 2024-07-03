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

// Function to get profile image path
function getProfileImagePath($profile_image) {
    if (!empty($profile_image)) {
        
        if (strpos($profile_image, 'uploads/') === 0) {
            $image_path = $profile_image;
        } elseif (strpos($profile_image, 'uploads/') === 0) {
            $image_path = substr($profile_image, 3); 
        } else {
            $image_path = 'uploads/profile/' . $profile_image;
        }
    } else {
        $image_path = 'assets/pic/default.jpg'; // Default image path
    }
    return $image_path;
}

// Function to fetch comments for a song
function fetchComments($conn, $songID) {
    $comments = [];
    $commentsQuery = "SELECT c.*, u.name, u.profile_image FROM Comments c JOIN users u ON c.user_id = u.user_id WHERE song_id = $songID ORDER BY created_at DESC";
    $commentsResult = mysqli_query($conn, $commentsQuery);

    if ($commentsResult) {
        while ($row = mysqli_fetch_assoc($commentsResult)) {
            // Process the profile image path
            $row['profile_image'] = getProfileImagePath($row['profile_image']);
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
