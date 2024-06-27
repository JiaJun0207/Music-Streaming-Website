<?php
// image.php

if (isset($_GET['path'])) {
    $path = urldecode($_GET['path']);
    
    // Ensure the path is within the allowed directories
    $allowedDirectories = [
        '/absolute/path/to/your/images/directory',
        '/absolute/path/to/your/mp3/directory'
    ];

    $isAllowed = false;
    foreach ($allowedDirectories as $directory) {
        if (strpos(realpath($path), realpath($directory)) === 0) {
            $isAllowed = true;
            break;
        }
    }

    if ($isAllowed && file_exists($path)) {
        $mimeType = mime_content_type($path);
        header('Content-Type: ' . $mimeType);
        readfile($path);
    } else {
        // Output debug information
        http_response_code(404);
        echo 'File not found or access denied. Path: ' . $path . ' Real Path: ' . realpath($path);
    }
} else {
    http_response_code(400);
    echo 'No file specified';
}
?>
