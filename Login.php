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
    <style>
        /* Style for OTP Popup */
        .otp-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
        }
        .otp-popup h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        .otp-popup input[type="text"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .otp-popup button {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 1em;
            font-weight: bold;
            color: #fff;
            background-color: #6912db;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .otp-popup button:hover {
            background-color: #b991ec;
        }
    </style>
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
            <form id="signup-form" action="process_signup.php" method="post">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="hello@gmail.com" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="********" required>
                <label for="password_confirmation">Repeat password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="********" required>
                <button type="button" onclick="sendOTP();">Send OTP</button>
            </form>
                <p>Already a member? <a href="#" onclick="showLogin()">Log in</a></p>
            </div>
            <div id="login-form" class="form-content">
                <h2>Log In</h2>
                <form action="process_login.php" method="post">
                    <label for="login_email">Email</label>
                    <input type="email" id="login_email" name="email" placeholder="hello@gmail.com" required>
                    <label for="login_password">Password</label>
                    <input type="password" id="login_password" name="password" placeholder="********" required>
                    <button type="submit">Log In</button>
                </form>
                <p>Don’t have an account? <a href="#" onclick="showSignup()">Sign up</a></p>
            </div>
        </div>
    </div>

    <!-- OTP Popup -->
    <div id="otp-popup" class="otp-popup">
        <h2>Enter OTP</h2>
        <form id="otp-form" onsubmit="verifyOTP(); return false;">
            <input type="text" id="otp-input" name="otp" placeholder="Enter OTP" required>
            <button type="submit"><a href="#" onclick="showLogin()">Verify OTP</a></button>
        </form>
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

        function sendOTP() {
        let name = document.getElementById('name').value;
        let email = document.getElementById('email').value;
        let password = document.getElementById('password').value;
        let password_confirmation = document.getElementById('password_confirmation').value;
        
        // Validate inputs (simplified, add more validation as needed)
        if (!name || !password || !password_confirmation) {
            alert("All fields are required");
            return;
        }
        if (password !== password_confirmation) {
            alert("Passwords must match");
            return;
        }
        
        
        // AJAX request to process_signup.php for database insertion
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'process_signup.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Database insertion successful, now send OTP
                    sendOTPToEmail(email);
                } else {
                    alert('Error: ' + xhr.responseText);
                }
            }
        };
        xhr.send('name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
    }

    function sendOTPToEmail(email) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_otp.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText.trim()); // Alert message from send_otp.php
                document.getElementById('otp-popup').style.display = 'block'; // Display OTP popup
            }
        };
        xhr.send('email=' + encodeURIComponent(email));
    }

    function validateEmail(email) {
        // Simplified email validation, adjust as per your requirements
        return /\S+@\S+\.\S+/.test(email);
    }

    </script>
</body>
</html>
