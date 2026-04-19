<?php 
session_start();
// Check if the user is logged in, if not
// redirect to login page
$message = "";
$toastClass = "";

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $toastClass = $_SESSION['toastClass'];

    unset($_SESSION['message']);
    unset($_SESSION['toastClass']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/
4.7.0/css/font-awesome.min.css">

<link rel = "stylesheet" href = "dashboard_style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php  if ($message): ?>
    <div class="toast show text-white <?=  $toastClass ?> border-0 show"
        role="alert"
        aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <?=  $message ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <nav class="navbar navbar-expand-sm custom-navbar">

        <div class="container">
            <a class="navbar-brand" href="#">Symphoinc Contracts</a>

    <button class="navbar-toggler d-lg-none" type = "button" data-bs-toggle="collapse"
     data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavId">
                <ul class="navbar-nav m-auto mt-2 mt-lg-0">
                </ul>
                <form class="d-flex my-2 my-lg-0">
                    <a href="./logout.php" class="btn btn-logout"
                      type="submit">logout</a>
                </form>
            </div>
        </div>
    </nav>
    <div class="dashboard-box">
        <h2>Welcome to the Dashboard<h2>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>