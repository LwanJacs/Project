<?php
session_start();
include 'database\db_connect.php';

// Check if the product ID is provided
if (!isset($_GET['id'])) {
    header('Location: browse.php');
    exit;
}

$product_id = (int) $_GET['id'];

// Fetch product details from the database
$stmt = $conn->prepare("SELECT products.*, sellers.store_name FROM products LEFT JOIN sellers ON products.user_id = sellers.user_id LEFT JOIN users ON products.prod_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Product not found, redirect to browse page
if (!$product) {
    header('Location: browse.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['prod_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href= "view_details_style.css">
</head>
<body>
    <div class="container py-5">
        <div class="row-product-details">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['prod_name']) ?>">
            </div>
            <!-- Product Info -->
            <div class="col-md-6">
                <h1 class="product-title"><?= htmlspecialchars($product['prod_name']) ?></h1>
                <p class="price">R<?= htmlspecialchars($product['price']) ?></p>
                <p class="category"><?= htmlspecialchars($product['category']) ?></p>
                <p class="seller">Sold by: <?= htmlspecialchars($product['store_name'] ?? 'Unknown Seller') ?></p>
                <hr>
                <h5>Description</h5>
                <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <a href="add_to_cart.php?prod_id=<?= $product['prod_id'] ?>" class="btn btn-success btn-lg mt-3">Add to Cart</a>
                <a href="browse.php" class="btn btn-outline btn-lg mt-3"> Back </a>
            </div>
        </div>
    </div>
    
</body>
</html>