<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MP3 Files</title>
</head>
<body>
    <h1>MP3 Files</h1>
    <ul>
        <?php
        $db = new mysqli('localhost', 'root', '', 'mp3_database');

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $query = "SELECT id, name FROM mp3_files";
        $result = $db->query($query);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><a href='play.php?id=" . $row['id'] . "'>" . $row['name'] . "</a></li>";
            }
        } else {
            echo "<li>No MP3 files found!</li>";
        }

        $db->close();
        ?>
    </ul>
</body>
</html>
