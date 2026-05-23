<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symphonic Contracts</title>
    <link rel="stylesheet" href="index_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
        <div class="container">
            <!--logo-->
            <a class="navbar-brand" href="index.php">Symphonic Contracts</a>
            
            <!--Mobile menu toggle-->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!--Navigation links-->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="browse.php">Browse</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['is_seller']) && $_SESSION['is_seller'] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="seller_dashboard.php">My Store</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="seller_registration.php">Become a Seller</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <!-- Search-->
                <form class="d-flex me-3" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="query" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-search" type="submit">Search</button>
                </form>
                <br>
                <!-- Authentication Links-->
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a class="btn btn-outline-primary me-2" href="dashboard.php">Dashboard</a>
                        <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-outline-primary me-2" href="login.php">Login</a>
                        <a class="btn btn-outline-success" href="registration.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Homepage content-->
    <section class="hero">
        <div class="container hero-content text-center">
            <h1>Buy, Sell, and Manage Contracts</h1>
            <p>Symphonic Contracts is the premier platform for buying, selling, and managing contracts.</p>
            <div class="hero-buttons">
                <a href="browse.php" class="btn btn-primary btn-lg me-3">Browse Contracts</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn btn-outline-primary btn-lg">Go to Dashboard</a>
                <?php else: ?>
                    <a href="registration.php" class="btn btn-outline-success btn-lg">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features section-->
    <section class="features">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <!-- Feature card 1_- Buy Contracts-->
                    <div class="feature-card">
                        <i class="fa fa-shopping-cart fa-3x mb-3"></i>
                        <h3>Buy Contracts</h3>
                        <p>Explore a wide range of contracts available for purchase. Find the perfect contract to meet your needs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Feature card 2_- Sell Contracts-->
                    <div class="feature-card">
                        <i class="fa fa-upload fa-3x mb-3"></i>
                        <h3>Sell Contracts</h3>
                        <p>List your contracts for sale and reach a large audience of potential buyers. Manage your listings with ease.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Feature card 3_- Manage Contracts-->
                    <div class="feature-card">
                        <i class="fa fa-cogs fa-3x mb-3"></i>
                        <h3>Manage Contracts</h3>
                        <p>Keep track of your purchases and sales with our intuitive dashboard. Stay organized and in control of your contracts.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER-->
     <footer class="footer">
        <div class="container">
            <p>
                &copy; <?= date('Y') ?> Symphonic Contracts. 
                All rights reserved.
            </p>
        </div>
     </footer>
<!-- Bootstrap JS Bundle (includes Popper)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>