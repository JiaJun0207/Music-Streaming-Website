<?php
session_start();

if (isset($_SESSION["user_id"])) {
    $conn = require __DIR__ . "/db_connection.php";
    
    $sql = "SELECT name, profile_image FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($name, $profile_image);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ikun Music Dashboard</title>
    <link rel="stylesheet" href="assets/css/Home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="navbar-logo">
            <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="navbar-image"><span>IKUN MUSIC</span>
        </div>
        <div class="navbar-links-container">
            <a href="#" class="navbar-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="#" class="navbar-link"><i class="fas fa-search"></i> Discover</a>
            <a href="#" class="navbar-link"><i class="fas fa-music"></i> My Playlist</a>
            <a href="#" class="navbar-link"><i class="fas fa-th-large"></i> Categories</a>
            <a href="#" class="navbar-link"><i class="fas fa-envelope"></i> Message</a>
            <a href="User_Profile.html" class="navbar-link"><i class="fas fa-user"></i> My Profile</a>
            <a href="#" class="navbar-link"><i class="fas fa-cog"></i> Settings</a>
            <a href="#" class="navbar-link"><i class="fas fa-question-circle"></i> Help & Support</a>
        </div>
        <div class="navbar-user">
            <img src="<?php echo $profile_image ? 'uploads/' . htmlspecialchars($profile_image) : 'assets/pic/default.jpg'; ?>" alt="User Image">
            <span><a href="User_Profile.php" class="profile-link"><?php echo htmlspecialchars($name); ?></a></span>
        </div>
    </div>
    <div class="main-content">
        <div class="header">
            <input type="text" class="search-bar" placeholder="Artist, Album, Song, etc ...">
            <img src="assets/pic/ikun-coin.png" alt="Coins" width="50" height="50">
            <span><div class="counter">100</div></span>
        </div>
        <div class="banner">
            <h1>No need to upgrade just Support what you like!</h1>
            <p>Donate to your favorite artist to support them ヾ(•ω•`)o</p>
            <div class="banner-buttons">
                <button class="donate-button">Donate!</button>
                <button class="learn-more-button">Learn more</button>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content">
                <div class="content-header">
                    <button class="content-button">For You</button>
                    <button class="content-button">Hot</button>
                    <button class="content-button">Trend</button>
                    <button class="content-button">Shuffle</button>
                    <button class="content-button">Following</button>
                </div>
                <div class="albums">
                    <div class="album">
                        <img src="assets/pic/download (1).jpg" alt="Album Image">
                        <span>JiaJun IN PARIS</span>
                        <span>4 Albums</span>
                        <a href="#" class="see-details">See Details</a>
                    </div>
                    <div class="album">
                        <img src="assets/pic/ermm.jpg" alt="Album Image">
                        <span>Yael Amari</span>
                        <span>2 Albums</span>
                        <a href="#" class="see-details">See Details</a>
                    </div>
                    <div class="album">
                        <img src="assets/pic/eva (@evawxsh) • Instagram photos and videos.jpg" alt="Album Image">
                        <span>1+1=2</span>
                        <span>4 Albums</span>
                        <a href="#" class="see-details">See Details</a>
                    </div>
                    <div class="album">
                        <img src="assets/pic/Rose.jpg" alt="Album Image">
                        <span>BlackPink</span>
                        <span>4 Albums</span>
                        <a href="#" class="see-details">See Details</a>
                    </div>
                    <div class="album">
                        <img src="assets/pic/bruh.jpg" alt="Album Image">
                        <span>Larana Group</span>
                        <span>3 Albums</span>
                        <a href="#" class="see-details">See Details</a>
                    </div>
                </div>
            </div>
            <div class="trending">
                <h1>Popular and Trending</h1>
                <ul>
                    <hr>
                    <li><a href="#">Keep it simple - Alfredo Torres</a></li>
                    <hr>
                    <li><a href="#">You're wonderful - Avery Davis</a></li>
                    <hr>
                    <li><a href="#">You got this - Cahaya Dewi</a></li>
                    <hr>
                    <li><a href="#">Ji Ni Tai Mei - kunkun</a></li>
                    <hr>
                    <li><a href="#">Kindness - Yael Amari</a></li>
                    <hr>
                    <li><a href="#">You're my sunshine - Juliana Silva</a></li>
                    <hr>
                    <li><a href="#">Love - Itsuki Takahashi</a></li>
                    <hr>
                    <li><a href="#">More - Jien Sheng</a></li>
                    <hr>
                    <li><a href="#">Depression - Melvin</a></li>
                    <hr>
                    <li><a href="#">See you - JiaJun</a></li>

                    <h4 id="ads">Upload your production and become the next Trending! 🥳 </h4>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>