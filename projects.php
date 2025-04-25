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

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filtering logic
$conditions = [];
$params = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['projectname'])) {
        $conditions[] = 'Title LIKE :pname';
        $params['pname'] = '%' . $_POST['projectname'] . '%';
    }
    if (!empty($_POST['startdate'])) {
        $conditions[] = 'StartDate >= :start';
        $params['start'] = $_POST['startdate'];
    }
    if (!empty($_POST['enddate'])) {
        $conditions[] = 'EndDate <= :end';
        $params['end'] = $_POST['enddate'];
    }
    if (!empty($_POST['phase'])) {
        $conditions[] = 'Phase_Dev = :phase';
        $params['phase'] = $_POST['phase'];
    }
}

$whereSQL = $conditions ? ('WHERE ' . implode(' AND ', $conditions)) : '';

// Count total records for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM projects $whereSQL");
$countStmt->execute($params);
$totalProjects = $countStmt->fetchColumn();
$totalPages = ceil($totalProjects / $limit);

// Fetch filtered projects
$query = "SELECT * FROM projects $whereSQL ORDER BY StartDate DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);
foreach ($params as $key => $val) {
    $stmt->bindValue(":" . $key, $val);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Software.sys -- Your one-stop software solution company</title>
    <link rel="stylesheet" href="dashstyles.css">
</head>
<body>
<header>
    <?php
    if ($loggedin) {
        echo <<<HTML
        <nav>
            <a href="home.php">Software.sys</a>
            <a href="dashboard.php">Welcome $username</a>
            <a href="dashboard.php">$flag</a>
        </nav>
        HTML;
    } else {
        echo <<<HTML
        <nav>
            <a href="home.php">Software.sys</a>
            <a href="register.php">Register</a>
            <a href="login.php">Log in</a>
        </nav>
        HTML;
    }
    ?>
</header>
<main>
    <section>
        <p>Projects</p>
        <p>A list of all projects that we are creating for a better world</p>
    </section>
    <details open>
        <summary>Filters</summary>
        <form method="post" action="projects.php">
            <p>Project Name</p>
            <input type="text" name="projectname" id="projectId" maxlength="20">
            <p>Project Start Date</p>
            <input type="date" name="startdate">
            <p>Project End Date</p>
            <input type="date" name="enddate">
            <p>Phase</p>
            <select name="phase">
                <option value="">--Select Phase--</option>
                <option value="Design">Design</option>
                <option value="Development">Development</option>
                <option value="Testing">Testing</option>
                <option value="Deployment">Deployment</option>
                <option value="Complete">Complete</option>
            </select>
            <input type="submit" value="Apply">
        </form>
    </details>

    <section>
        <h2>Project List</h2>
        <?php if ($projects): ?>
                <?php foreach ($projects as $project): ?>
                    <section class="project-card">
                        <p class="title"><strong><?= htmlspecialchars($project['Title']) ?></strong></p>
                        <p class="meta">
                            Phase: <span><?= htmlspecialchars($project['Phase_Dev']) ?></span> |
                            Start: <span><?= htmlspecialchars($project['StartDate']) ?></span> |
                            End: <span><?= htmlspecialchars($project['EndDate']) ?></span>
                        </p>
                        <p class="description"><?= htmlspecialchars($project['Short_Description']) ?></p>
                        <form method="post" action="viewprojects.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($project['PID']) ?>">
                                <button type="submit" class="check-button">Check this out</button>
                        </form>

                    </section>

                <?php endforeach; ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" <?= $i == $page ? 'style="font-weight:bold"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <p>No projects found.</p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
