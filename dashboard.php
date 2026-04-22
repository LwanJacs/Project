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
    <div class="toast align-items-center text-white <?=  $toastClass ?> border-0"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="3000">

        <div class="d-flex">
            <div class="toast-body">
                <?=  $message ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <nav class="navbar navbar-expand-sm navbar-dark custom-navbar">

        <div class="container">
            <a class="navbar-brand" href="#">Symphoinc Contracts</a>
            <!--Mobile menu toggler-->
            <button class="navbar-toggler d-lg-none" 
                    type = "button" 
                    data-bs-toggle="collapse"
                    data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--Navigation links-->
            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Search</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">Become a Seller</a>
                    </li>

                </ul>
                <div class="d-flex my-2 my-lg-0">
                    <a href="dashboard.php" class="btn btn-outline-light me-2">Account</a>

                    <a href="./logout.php" class="btn btn-logout">logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="dashboard-box">
        <h2>Welcome to the Dashboard<h2>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script> docuument.addEventListener("DOMContentLoaded", function() {
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
}); 
</script>
</body>
</html>