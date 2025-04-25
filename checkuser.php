<?php
require_once 'headers.php';
if (isset($_POST['username'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE UserName=?');
    $stmt->execute([$_POST['username']]);
    $user_html_entities=htmlentities($_POST['username']);
    if ($stmt->rowCount()) {
        echo "<span class='error'>&nbsp;&#x2718; The username '$user_html_entities' is taken</span>";
    } else {
        echo "<span class='available' style = 'color:green;'>&nbsp;&#x2714; The username '$user_html_entities' is available</span>";
    }
}

if (isset($_POST['email'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE Email=?');
    $stmt->execute([$_POST['email']]);
    $user_html_entities=htmlentities($_POST['email']);
    if ($stmt->rowCount()) {
        echo "<span class='error'>&nbsp;&#x2718; The email '$user_html_entities' is taken</span>";
    } else {
        echo "<span class='available' style = 'color:green;'>&nbsp;&#x2714; The email '$user_html_entities' is available</span>";
    }
}

if (isset($_POST['password']) && isset($_POST['repeatpassword'])) {
    $password = $_POST['password'];
    $repeat = $_POST['repeatpassword'];
    if ($password !== $repeat) {
        echo "<span class='error' style='color:red;'>&nbsp;&#x2718; Passwords do not match</span>";
    } else {
        echo "<span class='available' style='color:green;'>&nbsp;&#x2714; Passwords match</span>";
    }
}

?>