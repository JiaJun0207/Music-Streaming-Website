<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $mysqli = require __DIR__ . "/db_connection.php";

        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($_POST["password"], $user["password_hash"])) {
                session_start();
                session_regenerate_id();
                $_SESSION["user_id"] = $user["id"];
                header("Location: User_Home.php");
                exit;
            }
        }

        $is_invalid = true;
    } else {
        $is_invalid = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IKUN - Sign Up & Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="/js/validation.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="logo-image">
            <span style="color: black;">IKUN MUSIC</span>
        </div>
        <div class="form-container">
            <div class="form-toggle">
                <button id="signup-btn" onclick="showSignup()">Sign Up</button>
                <button id="login-btn" onclick="showLogin()">Log In</button>
            </div>
            <div id="signup-form" class="form-content">
                <h2>Sign Up</h2>
                <form action="process_signup.php" method="post">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your Name">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="hello@gmail.com">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********">
                    <label for="password_confirmation">Repeat password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="********">
                    <button type="submit">Sign Up</button>
                </form>
                <p>Already a member? <a href="#" onclick="showLogin()">Log in</a></p>
            </div>
            <div id="login-form" class="form-content">
                <h2>Log In</h2>

                <?php if ($is_invalid): ?>
                <em>Invalid login</em>
                <?php endif; ?>

                <form method="post">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="hello@gmail.com"
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********">
                    <button type="submit">Log In</button>
                </form>
                <p>Don’t have an account? <a href="#" onclick="showSignup()">Sign up</a></p>
            </div>
        </div>
    </div>
    <script>
        function showSignup() {
            document.getElementById('signup-form').style.display = 'block';
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('signup-btn').classList.add('active');
            document.getElementById('login-btn').classList.remove('active');
        }

        function showLogin() {
            document.getElementById('signup-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('signup-btn').classList.remove('active');
            document.getElementById('login-btn').classList.add('active');
        }

        window.onload = function() {
            showLogin();
        }
    </script>
</body>
</html>
