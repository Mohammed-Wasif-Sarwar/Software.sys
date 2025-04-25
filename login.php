<?php
session_start();
require_once('headers.php');

$email_or_username = '';
$loginerr = '';

// Redirect if already logged in
if (isset($_SESSION['username'])) {
    header("Location: home.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_or_username = htmlentities($_POST['email_or_username']);
    $password = $_POST['password'];

    // Check if input is email or username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ? OR UserName = ?");
    $stmt->execute([$email_or_username, $email_or_username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['username'] = $user['UserName'];
        $_SESSION['country'] = $user['Country']; // Assuming this is stored
        header("Location: home.php");
        exit();
    } else {
        $loginerr = "<span class='error'>&nbsp;&#x2718; Invalid email/username or password</span>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Software.sys</title>
    <link rel="stylesheet" href="registercss.css">
    <style>
        main{
            background: url('mountain.webp') center / cover no-repeat;
    </style>
</head>
<body>
<header>
    <nav>
        <a href="home.php">Software.sys</a>
        <p>Login to access your services</p>
    </nav>
</header>
<main>
    <section>
        <header>Login to Your Account</header>
        <form method="post" action="login.php">
            <p>Welcome back! Please enter your login details:</p>

            <label><p>Email or Username:</p>
            <input type="text" name="email_or_username" required value="<?= $email_or_username ?>"><br>
            </label>

            <label><p>Password:</p>
            <input type="password" name="password" id="login_password" required><br>
            </label>

            <div id="login-error"><?= $loginerr ?></div>

            <p><input type="submit" value="Login"></p>
        </form>

        <script>
            const loginPass = document.getElementById('login_password');
            loginPass.addEventListener('input', () => {
                const pass = loginPass.value;
                loginPass.style.borderColor = pass.length >= 6 ? 'green' : 'red';
            });
        </script>
    </section>
</main>
</body>
</html>
