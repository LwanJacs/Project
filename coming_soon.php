<?php
session_start();
$title = $_GET['feature'] ?? "Feature";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="coming_soon_style.css" rel="stylesheet">
</head>
<body>
    <div class="coming-container">

        <div class="coming-card">   

            <div class="icon">
                🚧
            </div>
            <h1>
                <?= htmlspecialchars($title) ?>
            </h1>
            <h3>Coming Soon!</h3>
            <p>
                We're currently building this feature and it will be available in a future update.
            </p>
            <a href="dashboard.php" class="home-btn">
                Return to Dashboard
            </a>
        </div>
    </div>
</body>
</html>