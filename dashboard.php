<?php 
session_start();
// Check if the user is logged in, if not
// redirect to homepage
if (!isset($_SESSION['user_id'])) {

    header("Location: index.php");
    exit();
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
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
        <a class="btn btn-outline-secondary mt-3 ms-3" href="index.php">← Home</a>
        <div class="container">
            <a class="navbar-brand" href="#">Symphonic Contracts</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--Navigation links-->
            <div class="collapse navbar-collapse" id="navMenu">

                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse</a>
                    </li>

                    <li class="nav-item">
                    <?php if (isset($_SESSION['is_seller']) && $_SESSION['is_seller']): ?>
                        <!--Already a seller, show dashboard link-->
                        <a class="nav-link btn" href="seller_dashboard.php">
                            My Store
                        </a>
                    <?php else: ?>
                        <!--Not a seller, show registration link-->
                        <a class="nav-link" href="seller_register.php">
                            Become a Seller
                        </a>
                    <?php endif; ?>
                    </li>
                </ul>
                <!--Search bar-->
                <form class="search-form me-3" action="search.php" method="GET">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="search" name="query" placeholder="Search products..." value="">

                        <button type="submit">Search</button>
                    </div>
                </form>

                <!-- Account dropdown -->
                <div class="d-flex my-2 my-lg-0">

                    <div class="dropdown me-2">

                        <button class="btn btn-outline-light dropdown-toggle" 
                                type="button" 
                                id="accountDropdown" 
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <i class="fa fa-user"></i> Account
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end custom-dropdown"
                            aria-labelledby="accountDropdown">
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fa fa-user-circle"></i> My Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="orders.php">
                                    <i class="fa fa-shopping-bag"></i> My Orders
                                </a>
                            </li>
                             <li>
                                <a class="dropdown-item" href="cart.php">
                                    <i class="fa fa-shopping-cart"></i> Cart
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="messages.php">
                                    <i class="fa fa-envelope"></i> Messages
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <div class="dashboard-box">
        <h2>Welcome to the Dashboard</h2>
    </div>

    <div class="container mt-5">
    <!-- Welcome banner-->
    <div class="dashboard-box mb-4">
        <h2>Welcome back!</h2>
        <p>Here to make a symphony.</p>
    </div>

    <!--Dashboard Cards-->
    <div class="row g-4">

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Orders</h4>
                <p>0 Active Orders</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Messages</h4>
                <p>0 Unread Messages</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="dashboard-card">
                <h4>Balance</h4>
                <p>R0.00</p>
            </div>
        </div>
    
    </div>
    </div>

    <div class="modal fade" id="sellerModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content custom-modal">

                <div class="modal-header border-0">

                    <h5 class="modal-title">Seller Dashboard</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    <p>You are already registered as a seller!</p>
                    <a href="seller_dashboard.php" class="btn btn-primary w-100">
                        Go to My Store
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="dashboard-footer">
    <p>© 2026 Symphonic Contracts. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>