<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload MP3</title>
</head>
<body>
    <h1>Upload MP3 File</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="mp3_file" accept="audio/mp3" required>
        <input type="submit" name="submit" value="Upload">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $db = new mysqli('localhost', 'root', '', 'mp3_database');

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $fileName = $_FILES['mp3_file']['name'];
        $fileTmpName = $_FILES['mp3_file']['tmp_name'];
        $fileType = $_FILES['mp3_file']['type'];
        $fileContent = addslashes(file_get_contents($fileTmpName));

        $query = "INSERT INTO mp3_files (name, file, mime_type) VALUES ('$fileName', '$fileContent', '$fileType')";
        if ($db->query($query) === TRUE) {
            echo "File uploaded successfully!";
        } else {
            echo "Error: " . $query . "<br>" . $db->error;
        }

        $db->close();
    }
    ?>
</body>
</html>
