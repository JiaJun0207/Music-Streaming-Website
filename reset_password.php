<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Reset Password</h2>
            <form action="update_password.php" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" style="padding: 10px; border: none; border-radius: 5px; margin-bottom: 20px;">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="********" required style="padding: 10px; border: none; border-radius: 5px; margin-bottom: 20px;">
                <br>
                <label for="password_confirmation">Repeat New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="********" required style="padding: 10px; border: none; border-radius: 5px; margin-bottom: 20px;">
                <button type="submit" style="padding: 10px; border: none; border-radius: 5px; background-color: #6200ea; color: #fff; font-size: 16px; cursor: pointer; font-family: Poppins, sans-serif;">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>