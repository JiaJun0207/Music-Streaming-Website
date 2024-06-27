<?php
session_start();

// Include database connection
$conn = require_once 'db_connection.php'; // Adjust path as necessary

// Initialize variables for storing login status
$email = '';
$password = '';
$login_error = '';

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and sanitize input (not shown here for brevity)

    // Query to check if the user exists with the provided credentials
    $sql = "SELECT admin_id, admin_email, admin_password FROM admins WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['admin_password'])) {
            // Password is correct, start a session
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_email'] = $row['admin_email'];
            
            // Redirect to dashboard or another authenticated page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            $login_error = "Invalid password. Please try again.";
        }
    } else {
        // User with provided email not found
        $login_error = "User not found. Please check your credentials.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
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
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="assets/pic/Inspirational_Quote_Instagram_Post_1.png" alt="Logo" class="logo-image">
            <span style="color: black;">IKUN MUSIC</span>
        </div>
        <div class="form-container">
            <div class="form-toggle">
            </div>
            <div id="signup-form" class="form-content">   
            </div>
            <div id="login-form" class="form-content">
                <h2>Admin Login</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="hello@gmail.com" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="********" required>
                    <button type="submit">Log In</button>
                    <?php if (!empty($login_error)) : ?>
                        <p class="error-message"><?php echo $login_error; ?></p>
                    <?php endif; ?>
                </form>
                
            </div>
        </div>
    </div>
    <script>

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
