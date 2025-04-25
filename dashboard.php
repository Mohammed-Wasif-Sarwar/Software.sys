<?php
session_start();
require_once("headers.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get user info
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get country flag
$stmt = $pdo->prepare('SELECT Flag FROM countries WHERE Country = :country');
$stmt->execute(['country' => $_SESSION['country']]);
$flag = $stmt->fetchColumn();

// Get user's projects count
$stmt = $pdo->prepare('SELECT COUNT(*) FROM projects WHERE UserName = :username');
$stmt->execute(['username' => $_SESSION['username']]);
$projectCount = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Software.sys</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <header>
        <nav>
            <a href="home.php">Software.sys</a>
            <a href="dashboard.php">Welcome <?= htmlspecialchars($_SESSION['username']) ?></a>
            <a><?= $flag ?></a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="dashboard-container">
        <div class="dashboard-header">
            <h1>Your Dashboard</h1>
            <p>Welcome back, <?= htmlspecialchars($user['first_name'] ?? htmlspecialchars($_SESSION['username'])) ?>!</p>
        </div>

        <div class="dashboard-grid">
            <section class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <span><?= strtoupper(substr($user['first_name'] ?? $_SESSION['username'], 0, 1)) ?></span>
                    </div>
                    <h2>Your Profile</h2>
                </div>
                
                <div class="profile-details">
                    <div class="detail-row">
                        <span class="detail-label">Username:</span>
                        <span class="detail-value"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </div>
                    
                    <?php if (!empty($user['first_name'])): ?>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value"><?= htmlspecialchars($user['first_name']) ?> <?= htmlspecialchars($user['last_name'] ?? '') ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?= htmlspecialchars($user['Email'] ?? 'Not provided') ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Country:</span>
                        <span class="detail-value"><?= htmlspecialchars($_SESSION['country'] ?? 'Unknown') ?> <?= $flag ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Member Since:</span>
                        <span class="detail-value"><?= date('F j, Y', strtotime($user['registration_date'] ?? 'now')) ?></span>
                    </div>
                </div>
                
            </section>

            <section class="stats-card">
                <h2>Your Activity</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value"><?= $projectCount ?></div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Active</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Completed</div>
                    </div>
                </div>
                
                <div class="quick-actions">
                    <a href="newproject.php" class="dashboard-button primary">New Project</a>
                    <a href="projects.php" class="dashboard-button">View All</a>
                </div>
            </section>

            <section class="recent-card">
    <h2>Recent Projects</h2>
    <?php
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE UserName = :username ORDER BY StartDate DESC LIMIT 3');
    $stmt->execute(['username' => $_SESSION['username']]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($projects) > 0): ?>
        <div class="project-list">
            <?php foreach ($projects as $project): ?>
                <div class="project-item">
                    <h3><?= htmlspecialchars($project['Title']) ?></h3>
                    <div class="project-meta">
                        <span class="phase-badge <?= strtolower($project['Phase_Dev']) ?>"><?= htmlspecialchars($project['Phase_Dev']) ?></span>
                        <span><?= date('M j, Y', strtotime($project['StartDate'])) ?> - <?= date('M j, Y', strtotime($project['EndDate'])) ?></span>
                    </div>
                    <form method="post" action="viewprojects.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $project['PID'] ?>">
                        <button type="submit" class="project-link" style="background: none; border: none; color: white; cursor: pointer; padding: 0; text-decoration: underline;">View Project →</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-projects">You don't have any projects yet. <a href="newproject.php">Create your first project</a> to get started!</p>
    <?php endif; ?>
</section>
        </div>
    </main>
</body>
</html>
