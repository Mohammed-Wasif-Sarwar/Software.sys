<?php
session_start();
require_once("headers.php");

$loggedin = isset($_SESSION['username']);
$username = $flag = $email = "";

if ($loggedin) {
    // Query for the flag
    $stmt = $pdo->prepare('SELECT Flag FROM countries WHERE Country = :coun');
    $stmt->execute(['coun' => $_SESSION['country']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $flag = $row['Flag'] ?? "";
    
    // Query for the email
    $stmt = $pdo->prepare('SELECT Email FROM users WHERE UserName = :user');
    $stmt->execute(['user' => $_SESSION['username']]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $username = htmlspecialchars($_SESSION['username']);
    $email = $userRow['Email']; 
}else{
    header("Location: login.php");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $startDate = trim($_POST['start_date'] ?? '');
    $endDate = trim($_POST['end_date'] ?? '');
    $cost = trim($_POST['cost'] ?? 0);

    if (empty($title) || empty($description) || empty($startDate) || empty($endDate)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO projects (PID, UserName, Email, Title, StartDate, EndDate, Short_Description, Phase_Dev, Rating, Feedback, Cost) 
                                   VALUES (NULL, :user, :email, :title, :start, :end, :desc, :phase, NULL, NULL, :cost)');
            $stmt->execute([
                'user' => $_SESSION['username'],
                'email' => $email,
                'title' => $title,
                'start' => $startDate,
                'end' => $endDate,
                'desc' => $description,
                'phase' => "Design",
                'cost' => $cost
            ]);
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
            <a href="login.php">Log in</a>
        </nav>
    <?php endif; ?>
</header>
<main class="project-layout">
    <div class="project-content">
        <section>
            <h1 style="font-size: 40px;">Create New Project</h1>
            
            <?php if ($error): ?>
                <div style="color: white; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="color: white; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
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
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Cost ($)</label>
                    <input type="number" name="cost" min="0" required step="0.01" style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                </div>
                             
                <button type="submit" class="check-button" style="width: auto; padding: 10px 20px;">Create Project</button>
            </form>
        </section>
    </div>
</main>
</body>
</html>