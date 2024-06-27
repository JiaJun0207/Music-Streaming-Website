<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Artist List</title>
    <link rel="stylesheet" href="list.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="navbar">
                <div class="navbar-logo">
                    <img src="../assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image"><span>IKUN MUSIC</span>
                </div>
                <div class="navbar-links-container">
                    <a href="" class="navbar-link"></i> Dashboard</a>
                    <a href="song_list.html" class="navbar-link"></i> Song List</a>
                    <a href="artist_list.html" class="navbar-link"></i> Artist</a>
                    <a href="user_list.html" class="navbar-link"></i> Users</a>
                </div>
                <a href="index.html" class="logout">Logout</a>
            </div>   
        </aside>
        <main class="main-content">
            <header>
                <input type="text" placeholder="Artist, Album, Song, etc...">
            </header>
            <h1>Artist List</h1>
            <button id="addNewBtn">Add New</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Ikun Coins</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="artistList">
                    
                </tbody>
            </table>
        </main>
    </div>
    <script>
        function editArtist(id) {
    // Logic to edit a artist
    alert(`Edit artist with ID: ${id}`);
}

function deleteArtist(id) {
    fetch(`/api/artists/${id}`, {
        method: 'DELETE'
    })
    .then(response => {
        if (response.ok) {
            alert('Artist deleted successfully!');
            window.location.reload();
        } else {
            alert('Failed to delete artist.');
        }
    });
}

document.getElementById('addNewBtn').addEventListener('click', function() {
    window.location.href = 'upload_artist.html';
});
    </script>
</body>
</html>