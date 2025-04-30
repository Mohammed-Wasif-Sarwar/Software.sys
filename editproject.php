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
$project = null;

// Get project ID from URL
$pid = $_GET['id'] ?? '';

if (!$pid) {
    header("Location: dashboard.php");
    exit();
}

// Fetch project data
$stmt = $pdo->prepare("SELECT * FROM projects WHERE PID = :pid");
$stmt->execute(['pid' => $pid]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if project exists and user is owner
if (!$project) {
    header("Location: dashboard.php");
    exit();
}

$isOwner = $project['UserName'] === $_SESSION['username'];
if (!$isOwner) {
    header("Location: dashboard.php");
    exit();
}

// Handle form submission
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
            $stmt = $pdo->prepare('UPDATE projects SET 
                                  Title = :title, 
                                  Short_Description = :desc, 
                                  StartDate = :start, 
                                  EndDate = :end, 
                                  Phase_Dev = :phase, 
                                  Feedback = :feedback
                                  WHERE PID = :pid');
            $stmt->execute([
                'title' => $title,
                'desc' => $description,
                'start' => $startDate,
                'end' => $endDate,
                'phase' => $phase,
                'feedback' => $feedback,
                'pid' => $pid
            ]);
            $success = 'Project updated successfully!';
            
            // Refresh project data
            $stmt = $pdo->prepare("SELECT * FROM projects WHERE PID = :pid");
            $stmt->execute(['pid' => $pid]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $error = 'Error updating project: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Project - Software.sys</title>
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
            <a href="home.html">Software.sys</a>
            <a href="register.php">Register</a>
            <a href="login.html">Log in</a>
        </nav>
    <?php endif; ?>
</header>
<main class="project-layout">
    <div class="project-content">
        <section>
            <h1 style="font-size: 40px;">Edit Project</h1>
            
            <?php if ($error): ?>
                <div style="color: red; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="color: white; padding: 10px; background: rgba(255,255,255,0.2); border-radius: 5px;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($project): ?>
            <form method="post" action="editproject.php?id=<?= urlencode($pid) ?>">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Project Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($project['Title']) ?>" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Short Description</label>
                    <textarea name="description" required style="width: 90%; padding: 8px; border-radius: 5px; border: none; min-height: 100px;"><?= htmlspecialchars($project['Short_Description']) ?></textarea>
                </div>
                
                <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">Start Date</label>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($project['StartDate']) ?>" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; margin-bottom: 5px; font-weight: bold;">End Date</label>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($project['EndDate']) ?>" required style="width: 90%; padding: 8px; border-radius: 5px; border: none;">
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Details/Feedback</label>
                    <textarea name="feedback" style="width: 90%; padding: 8px; border-radius: 5px; border: none; min-height: 150px;"><?= htmlspecialchars($project['Feedback']) ?></textarea>
                </div>
                
                <button type="submit" class="check-button" style="width: auto; padding: 10px 20px;">Update Project</button>
                <a href="projects.php" class="check-button" style="display: inline-block; width:90%;margin-left: 10px; background: #6c757d; text-decoration: none;">Cancel</a>
            </form>
            <?php endif; ?>
        </section>
    </div>
</main>
</body>
</html>