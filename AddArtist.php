<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include 'db_connection.php';

    // Escape user inputs for security
    $newArtistName = mysqli_real_escape_string($conn, $_POST['newArtistName']);
    $newArtistEmail = mysqli_real_escape_string($conn, $_POST['newArtistEmail']);
    $newArtistYouTube = mysqli_real_escape_string($conn, $_POST['newArtistYouTube']);
    $newArtistPhoto = null;

    // File upload handling for artist photo (optional)
    if ($_FILES['newArtistPhoto']['name']) {
        $newArtistPhoto = 'uploads/artist/' . basename($_FILES['newArtistPhoto']['name']);
        move_uploaded_file($_FILES['newArtistPhoto']['tmp_name'], $newArtistPhoto);
    }

    // Insert query
    $sql = "INSERT INTO artist (artist_name, artist_email, artist_youtube, artist_photo)
            VALUES ('$newArtistName', '$newArtistEmail', '$newArtistYouTube', '$newArtistPhoto')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Artist added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}
?>