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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $phase = trim($_POST['phase'] ?? 'Design');
    $feedback = trim($_POST['feedback'] ?? '');

    if (empty($title) || empty($description) || empty($startDate) || empty($endDate)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO projects (Title, Short_Description, StartDate, EndDate, Phase_Dev, Feedback, UserName) 
                                   VALUES (:title, :desc, :start, :end, :phase, :feedback, :user)');
            $stmt->execute([
                'title' => $title,
                'desc' => $description,
                'start' => $startDate,
                'end' => $endDate,
                'phase' => $phase,
                'feedback' => $feedback,
                'user' => $_SESSION['username']]
            );
            $success = 'Project created successfully!';
        } catch (PDOException $e) {
            $error = 'Error creating project: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Project - Software.sys</title>
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
            <a href="login.html">Log in</a>
        </nav>
    <?php endif; ?>
</header>
<main class="project-layout">
    <div class="project-content">
        <section>
            <h1 style="font-size: 40px;">Create New Project</h1>
            
            <?php if ($error): ?>
                <div style="color: red; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="color: green; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <form method="post" action="newproject.php">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Project Title</label>
                    <input type="text" name="title" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Short Description</label>
                    <textarea name="description" required style="width: 90%; padding: 8px; border-radius: 5px; border: none; min-height: 100px;"></textarea>
                </div>
                
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Start Date</label>
                        <input type="date" name="start_date" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">End Date</label>
                        <input type="date" name="end_date" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Phase</label>
                    <select name="phase" style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                        <option value="Design">Design</option>
                        <option value="Development">Development</option>
                        <option value="Testing">Testing</option>
                        <option value="Deployment">Deployment</option>
                        <option value="Complete">Complete</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Details/Feedback</label>
                    <textarea name="feedback" style="width: 90%; padding: 8px; border-radius: 5px; border: none; min-height: 150px;"></textarea>
                </div>
                
                <button type="submit" class="check-button" style="width: auto; padding: 10px 20px;">Create Project</button>
            </form>
        </section>
    </div>
</main>
</body>
</html>
