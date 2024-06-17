<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = new mysqli('localhost', 'root', '', 'mp3_database');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $query = "SELECT name, file, mime_type FROM mp3_files WHERE id = $id";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        header("Content-Type: " . $row['mime_type']);
        header("Content-Disposition: inline; filename=\"" . $row['name'] . "\"");
        echo $row['file'];
    } else {
        echo "File not found!";
    }

    $db->close();
} else {
    echo "Invalid request!";
}
?>
