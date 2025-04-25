<?php
session_start();
require_once('headers.php');

// Initialize variables
$firstname = $secondname = $email = $username = '';
$firstnameerr = $secondnameerr = $emailerr = $usernameerr = '';
$passworderr = $repeaterr = '';
$valid = true;

// If user already logged in, redirect
if (isset($_SESSION['username'])) {
    header("Location: home.html");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = htmlentities($_POST['firstname']);
    $secondname = htmlentities($_POST['secondname']);
    $email = htmlentities($_POST['email']);
    $username = htmlentities($_POST['username']);
    $password = $_POST['password'];
    $repeatpassword = $_POST['repeatpassword'];

    // Name format validation
    if (!preg_match("/^[a-zA-Z]+$/", $_POST['firstname'])) {
        $firstnameerr = "Wrong format of first name (only letters allowed)";
        $valid = false;
    }

    if (!preg_match("/^[a-zA-Z]+$/", $_POST['secondname'])) {
        $secondnameerr = "Wrong format of second name (only letters allowed)";
        $valid = false;
    }

    // Username check
    $stmt = $pdo->prepare('SELECT * FROM users WHERE UserName=?');
    $stmt->execute([$username]);
    if ($stmt->rowCount()) {
        $usernameerr = "<span class='error'>&nbsp;&#x2718; The username '$username' is taken</span>";
        $valid = false;
    }

    // Email check
    $stmt = $pdo->prepare('SELECT * FROM users WHERE Email=?');
    $stmt->execute([$email]);
    if ($stmt->rowCount()) {
        $emailerr = "<span class='error'>&nbsp;&#x2718; The email '$email' is taken</span>";
        $valid = false;
    }

    // Password validation
    if (strlen($password) < 6 || !preg_match('/\d/', $password) || !preg_match('/\W/', $password)) {
        $passworderr = "Password must be at least 6 characters, include a number and a special character.";
        $valid = false;
    }

    if ($password !== $repeatpassword) {
        $repeaterr = "Passwords do not match.";
        $valid = false;
    }

    // If valid, go to verification
    if ($valid) {
        $_SESSION['pending_registration'] = [
            'firstname' => $firstname,
            'secondname' => $secondname,
            'email' => $email,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'country' => $_POST['country']
        ];
        header("Location: verify.php");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>Software.sys -- Your one-stop software solution company</title>
     <link rel="stylesheet" href="registercss.css">
</head>
<body>
<header>
    <nav>
        <a href="home.php">Software.sys</a>
        <p>Offering services for the world</p>
    </nav>
</header>
<main>
    <section>
        <header>Don't hesitate any longer to register--it's free</header>
        <form method="post" action="register.php">
            <p>Please enter your details to sign up</p>

            <label><p>First Name: </p>
            <input type="text" maxlength="50" name="firstname" id="First_Name" placeholder="First Name" required value="<?= $firstname ?>"><br>
            <span class="error"><?= $firstnameerr ?></span>
            </label>

            <label><p>Second Name: </p>
            <input type="text" maxlength="50" name="secondname" id="Second_Name" placeholder="Second Name" required value="<?= $secondname ?>"><br>
            <span class="error"><?= $secondnameerr ?></span>
            </label>

            <label><p>Your preferred username: </p>
            <input type="text" maxlength="20" name="username" id="username" placeholder="Username" required value="<?= $username ?>"><br>
            <span id="used">&nbsp;<?= $usernameerr ?></span>
            </label>

            <label><p>Your desired password: </p>
            <input type="password" name="password" id="password" required><br>
            <span class="error"><?= $passworderr ?></span>
            </label>

            <div id="secure" style="margin-top: 10px;">
                <span id="length" style="color: red; font-size:14px;">✗ At least six characters</span>
                <span id="number" style="color: red; font-size:14px;">✗ At least one number</span>
                <span id="special" style="color: red; font-size:14px;">✗ At least one non-word character</span>
            </div>

            <label><p>Repeat your password: </p>
            <input type="password" name="repeatpassword" required><br>
            <span class="error" id="repeat"><?= $repeaterr ?></span>
            </label>

            <label><p>Email: </p>
            <input type="email" name="email" id="email" placeholder="Your email" required value="<?= $email ?>"><br>
            <span id="usedemail"><?= $emailerr ?></span>
            </label>

            <label for="country"><p>Country:</p></label>
            <select name="country" id="country" required>
                <option value="">Select your country</option>
                <?php
                $countries = ["China", "India", "United States", "Indonesia", "Pakistan", "Nigeria", "Brazil", "Bangladesh", "Russia", "Mexico", "Ethiopia", "Philippines", "Egypt", "Vietnam", "DR Congo", "Turkey", "Iran", "Germany", "Thailand", "United Kingdom", "France", "Italy", "South Africa", "Tanzania", "Myanmar", "South Korea", "Colombia", "Kenya", "Spain", "Argentina", "Algeria", "Sudan", "Ukraine", "Uganda", "Iraq", "Poland", "Canada", "Morocco", "Saudi Arabia", "Uzbekistan", "Peru", "Malaysia", "Afghanistan", "Venezuela", "Nepal", "Ghana", "Yemen", "Angola", "Mozambique"];
                foreach ($countries as $c) {
                    echo "<option value=\"$c\"";
                    if (isset($_POST['country']) && $_POST['country'] === $c) echo " selected";
                    echo ">$c</option>";
                }
                ?>
            </select>

            <p><label></label><input type="submit" value="Sign Up"></p>
        </form>

        <script>
            const usernameField = document.getElementById('username');
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            const repeatPasswordField = document.querySelector('input[name="repeatpassword"]');

            usernameField.onblur = () => {
                const data = new FormData();
                data.set('username', usernameField.value);
                fetch('checkuser.php', { method: 'post', body: data })
                    .then(res => res.text()).then(text => document.getElementById('used').innerHTML = text);
            };

            emailField.onblur = () => {
                const data = new FormData();
                data.set('email', emailField.value);
                fetch('checkuser.php', { method: 'post', body: data })
                    .then(res => res.text()).then(text => document.getElementById('usedemail').innerHTML = text);
            };

            passwordField.addEventListener('input', () => {
                const val = passwordField.value;
                const lengthOK = val.length >= 6;
                const numberOK = /\d/.test(val);
                const specialOK = /\W/.test(val);

                document.getElementById('length').style.color = lengthOK ? 'green' : 'red';
                document.getElementById('length').innerHTML = (lengthOK ? '✓' : '✗') + ' At least six characters';

                document.getElementById('number').style.color = numberOK ? 'green' : 'red';
                document.getElementById('number').innerHTML = (numberOK ? '✓' : '✗') + ' At least one number';

                document.getElementById('special').style.color = specialOK ? 'green' : 'red';
                document.getElementById('special').innerHTML = (specialOK ? '✓' : '✗') + ' At least one non-word character';
            });

            repeatPasswordField.addEventListener('input', () => {
                const match = repeatPasswordField.value === passwordField.value;
                document.getElementById('repeat').innerText = match ? 'Passwords match' : 'Passwords do not match.';
                document.getElementById('repeat').style.color = match ? 'green' : 'red';
            });
        </script>
    </section>
</main>
</body>
</html>
