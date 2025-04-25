<?php
session_start();
require_once("headers.php");

$loggedin = isset($_SESSION['username']);
$username = $flag = "";

if ($loggedin) {
    $stmt = $pdo->prepare('SELECT Flag FROM countries WHERE Country = :coun');
    $stmt->execute(['coun' => $_SESSION['country']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = htmlspecialchars($_SESSION['username']);
    $flag = $row['Flag'] ?? "";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Software.sys -- Your one-stop software solution company</title>
        <link rel="stylesheet" href="homestyles.css">
    </head>
    <body>
        <header>
        <?php if ($loggedin): ?>
        <nav>
            <a href="home.php">Software.sys</a>
            <a href="dashboard.php">Welcome <?= $username ?></a>
            <a href="dashboard.php"><?= $flag ?></a>
        </nav>
    <?php else: ?>
        <nav>
            <a href="home.php">Software.sys</a>
            <a href="register.php">Register</a>
            <a href="login.php">Log in</a>
        </nav>
    <?php endif; ?>
        </header>
        <section id="main">
            <section>
                <section><img src="computer.jpg" alt="Computers"></section>
                <section>
                    <header>Specialized professional coding at your service</header>
                    <header>Wish top-notch software solutions for your company? Fast websites? <br>
                    <button><a href="projects.php">Explore our website now</a></button>
                    </header>
                </section>    
            </section>
            <header>Services we provide for you to help your company stand out</header>
            <section>
                <aside>
                    <img src="automation.png" style="width:75px; display: block; margin:20px auto;">
                    <p>Get optimized code solutions for what you need: Just state your goals and our professional coders will give <em>wings to your dreams</em></p>
                </aside>
                <aside>
                    <img src="updated.png" style="width:75px; display: block; margin:20px auto;">
                    <p>Are you one of our previous customers and need an update to make your previous code modernized and <em>stand out from the crowd?</em> Get 
                    what you wish at discounted prices</p>
                </aside>
                <aside>
                    <img src="cashback.png" style="width:75px; display: block; margin:20px auto;">
                    <p>And what's more, you can avail a refund should you give us a 2-star rating-- and the next
                    project you request for has a 20% discount if you give us a full five-star rating!</p>
                </aside>
                <aside>
                    <img src="programming.png" style="width:75px; display: block; margin:20px auto;">
                    <p>Proficient in a wide variety of languages: from Node.js to Python for AI and developing customised Android and 
                        IOS apps, we can give you what you need <em>to succeed.</em>
                    </p>
                </aside>
                <aside>
                    <img src="popularity.png" style="width:75px; display: block; margin:20px auto;">
                    <p>Struggling your website to gain popularity by search engines? We can offer customer-centric advice to help you
                        get noticed by others and help your startup grow quickly!
                    </p>
                </aside>
            </section>
        </section>
        <section>
              <header></header>
        </section>

    </body>
</html>
