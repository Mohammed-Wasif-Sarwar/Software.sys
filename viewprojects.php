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

if (isset($_POST['id'])) {
    $_SESSION['pid'] = $_POST['id'];
}

$pid = $_SESSION['pid'] ?? '';



$stmt = $pdo->prepare("SELECT * FROM projects WHERE PID = :pid");
$stmt->execute(['pid' => $pid]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);


$isOwner = $loggedin && $project['UserName'] === $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Software.sys</title>
    <link rel="stylesheet" href="viewproject.css">
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
<main class="project-layout">
    <div class="project-content">
        <section>
            <p style="font-weight:bold; font-size: 40px;"><?= htmlspecialchars($project['Title']) ?></p>
        </section>
        <section style="font-size:20px">
            <p><strong>Start Date:</strong> <?= htmlspecialchars($project['StartDate']) ?></p>
            <p><strong>End Date:</strong> <?= htmlspecialchars($project['EndDate']) ?></p>
            <p><strong>Phase:</strong> <?= htmlspecialchars($project['Phase_Dev']) ?></p>
            <p><strong>Budget to be completed in:</strong> <?= htmlspecialchars($project['Cost']) ?></p>
            <p><strong>Details:</strong></p>
            <p style="font-weight:bold; font-size:24px;"><?= htmlspecialchars($project['Short_Description']) ?></p>
            <p><strong>Feedback:</strong></p>
            <p><?= nl2br(htmlspecialchars($project['Feedback'])) ?></p>
        </section>
    </div>

    <aside class="project-aside">
        <?php if (!$loggedin): ?>
            <p style="color:blue;"><strong>Register now</strong> to start your own project!</p>
        <?php elseif ($isOwner): ?>
            <a class="check-button" href="newproject.php">Add New Project</a>
            <a class="check-button" href="editproject.php?id=<?= urlencode($project['PID']) ?>">Edit Project</a>
        <?php else: ?>
            <a class="check-button" href="newproject.php">Add New Project</a>
        <?php endif; ?>
    </aside>
</main>
<?php @require_once("footers.php"); ?>
</body>
</html>
