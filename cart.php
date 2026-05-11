<?php 
session_start();
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';

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
    <div class="container">
        <h2 class="mt-4">Your Cart</h2>

        <div class="cart-box">
            <?php if (empty($cart)): ?>
                <p>Your cart is empty</p>
            <?php else: ?>
                <?php
                $total = 0;

                foreach ($cart as $product_id => $qty):

                    $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
                    $product = $result->fetch_assoc();

                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                ?>

                <div class="cart-item d-flex justify-content-between aligh-items-center">

                    <div>
                        <h5><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="price">R<?= $product['price'] ?> x <?=  $qty ?></p>
                    </div>

                    <div>
                        <strong><?= $subtotal ?></strong>
                        <a href="remove_from_cart.php?id=<?= $product_id ?>"
                           class="btn btn-sm btn-remoe ms-3">
                           Remove
                        </a>
                    </div>
                </div>
                
                <?php endforeach; ?>

                <hr>

                <h4>Total: <span class="price">R<?= $total ?></span></h4>

                <button class="btn btn-success mt-3 w-100">
                    Checkout
                </button>
            
            <?php endif; ?>
        </div>
    </div>
</body>
</html>