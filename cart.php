<?php 
session_start();
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
// Fetch cart from session
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="cart.css" rel="stylesheet">
</head>
<body>
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goBack()">← Back</button>
    </div>
    <div class="container">
        <h2 class="mt-4">Your Cart</h2>

        <div class="cart-box">
            <!-- If cart is empty, show message, otherwise list items -->
            <?php if (empty($cart)): ?>
                <p>Your cart is empty</p>
                <!-- Link to browse products -->
                <a href="browse.php" class="btn btn-primary mt-3">Browse Products</a>
            <?php else: ?>
                <!-- Loop through cart items -->
                <?php
                $total = 0;
                // Prepare statement to fetch product details
                $stmt = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");
                // Loop through cart items and fetch product details from database
                foreach ($cart as $product_id => $qty):
                    if(empty($product_id)) continue; // Skip if product ID is empty
                    // Fetch product details from database
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();

                    if (!$product) {
                        // If product not found, skip to next item
                        echo "<p class='text-danger'>Product with ID $product_id not found. It may have been removed.</p>";
                        continue;
                    }
                    // Calculate subtotal for this item and add to total
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>
                <!-- Display cart item -->
                <div class="cart-item d-flex justify-content-between align-items-center">

                    <div>
                        <h5><?= htmlspecialchars($product['prod_name']) ?></h5>
                        <p class="price">R<?= $product['price'] ?> x <?=  $qty ?></p>
                    </div>

                    <div>
                        <strong><?= $subtotal ?></strong>
                        <a href="remove_from_cart.php?id=<?= $product_id ?>"
                           class="btn btn-sm btn-remove ms-3">
                           Remove
                        </a>
                    </div>
                </div>
                
                <?php endforeach; ?>

                <hr>

                <h4>Total: <span class="price">R<?= $total ?></span></h4>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-success mt-3 w-100">
                        Checkout
                    </button>
                    <a href="browse.php" class="btn btn-outline-primary mt-2 w-100">
                        ← Continue Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="close_btn.js"></script>
</body>
</html>