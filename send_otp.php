<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPmailer/src/Exception.php';
require 'PHPmailer/src/PHPMailer.php';
require 'PHPmailer/src/SMTP.php';

// Validate and sanitize email input
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $conn = require __DIR__ . "/db_connection.php";

        // Check if email already exists in database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
        // Generate random OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP via email using PHPMailer
        try {
            $mail = new PHPMailer(true);
            
        //Server settings
        $mail->SMTPDebug = 0;                                        // Enable verbose debug output
        $mail->isSMTP();                                             // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                        // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
        $mail->Username   = 'chanjiajun321@gmail.com';               // SMTP username
        $mail->Password   = 'ivdg inba cphd pmlp';                   // SMTP password
        $mail->SMTPSecure = 'tls';                                   // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                     // TCP port to connect to

        // Recipients
        $mail->setFrom('chanjiajun321@gmail.com', 'IKUN MUSIC');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for IKUN MUSIC';
        $mail->Body    = 'Your OTP is: ' . $otp;

        $mail->send();
        echo 'OTP sent successfully to ' . $email;

        

        } catch (Exception $e) {
                echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "Invalid email";
    }
} else {
    echo "Invalid request";
}
?>