<?php
session_start();
require_once('headers.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";

// Handle code verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['firstletter'])) {
    $enteredCode = strtoupper($_POST['firstletter'] . $_POST['secondletter'] . $_POST['thirdletter'] . $_POST['fourthletter'] . $_POST['fifthletter']);

    if ($enteredCode === ($_SESSION['verification_code'] ?? '')) {
        // Code matches, insert into DB
        $user = $_SESSION['pending_registration'];
        $stmt = $pdo->prepare("INSERT INTO users (First_Name, Second_Name, Email, UserName, Password, Country) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user['firstname'],
            $user['secondname'],
            $user['email'],
            $user['username'],
            $user['password'],
            $user['country']
        ]);

        unset($_SESSION['pending_registration'], $_SESSION['verification_code']);
        $_SESSION['username'] = $user['username'];
        $_SESSION['country'] = $user['country'];
        $_SESSION['email'] = $user['email'];    
        header("Location: projects.php");
        exit();
    } else {
        $error = "Incorrect code. Please try again.";
    }
}

// Generate and send email if not already sent
if (!isset($_SESSION['verification_code']) && isset($_SESSION['pending_registration'])) {
    $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
    $_SESSION['verification_code'] = $code;

    $userEmail = $_SESSION['pending_registration']['email'];
    $userName = $_SESSION['pending_registration']['firstname'];

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kamalsoma2005@gmail.com';
        $mail->Password = 'reul ybif wmhj zcwy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('your-email@gmail.com', 'Software.sys');
        $mail->addAddress($userEmail, $userName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Software.sys Verification Code';
        $mail->Body = "<p>Hello <strong>$userName</strong>,<br>Your verification code is: <strong>$code</strong><br><br>Thank you for registering with Software.sys!</p>";

        $mail->send();
    } catch (Exception $e) {
        $error = "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Email Verification | Software.sys</title>
     <link rel="stylesheet" href="registercss.css">
     <style>
        input{
            width:15%;
            display:inline;
            height:60px;
            margin-right:10px;
            font-size: 30px;
            text-align: center;
        }
        .error {
            color: red;
        }
     </style>
</head>
<body>
<header>
    <nav>
        <a href="home.php">Software.sys</a>
        <p>Offering services for the world</p>
    </nav>
</header>
<main>
    <section id="emailverify" style="background-color: rgba(14, 40, 100, 0.8); padding: 20px;">
        <p style="font-size: 30px; font-weight:bold; color:white;">We have sent a verification code to your email:<br><br><?= htmlentities($_SESSION['pending_registration']['email'] ?? 'Unknown email') ?></p>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post" action="verify.php" style="color:white;">
            <p style="font-size: 18px;">Type your verification code below:</p>
            <input type="text" maxlength="1" name="firstletter" required>
            <input type="text" maxlength="1" name="secondletter" required>
            <input type="text" maxlength="1" name="thirdletter" required>
            <input type="text" maxlength="1" name="fourthletter" required>
            <input type="text" maxlength="1" name="fifthletter" required><br><br>
            <input type="submit" value="Verify" style="height: 40px; font-size: 16px;">
        </form>
    </section>
</main>
</body>
</html>
