<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Forgot Password</h2>
            <form action="send_reset_link.php" method="post">
                <label for="email">Enter your email address</label>
                <input type="email" id="email" name="email" placeholder="hello@gmail.com" required style="padding: 10px; border: none; border-radius: 5px; margin-bottom: 20px;">
                <button type="submit" style="padding: 10px; border: none; border-radius: 5px; background-color: #6200ea; color: #fff; font-size: 16px; cursor: pointer; font-family: Poppins, sans-serif;">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>