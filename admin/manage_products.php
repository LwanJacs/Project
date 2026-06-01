<?php
session_start();
include '../database/db_connect.php';

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !=1) {
    header("Location: ../dashboard.php");
    exit();
}

$search = $_GET['search'] ?? '';

// Select products with user info for display
$sql = "SELECT products.*, users.username FROM products JOIN users ON products.user_id = users.user_id WHERE prod_name LIKE ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$search_param = "%{$search}%";
$stmt->bind_param("s", $search_param);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="manage_products.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">

        <div class="top-bar">
            <a href="admin_dashboard.php" class="back-btn">
                ← Dashboard
            </a>
        </div>
        <h1>Manage Products</h1>
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>

        <div class="product-grid">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['prod_name']) ?>">
                    <h3><?= htmlspecialchars($product['prod_name']) ?></h3>
                    <p>By: <?= htmlspecialchars($product['username']) ?></p>
                    <p>R<?= number_format($product['price'], 2) ?></p>
                    <p><?= htmlspecialchars($product['category']) ?></p>

                    <div class="actions">
                        <a href="edit_product_admin.php?prod_id=<?= $product['prod_id'] ?>" class="edit-btn">
                            Edit
                        </a>
                        <a href="delete_product_admin.php?prod_id=<?= $product['prod_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">
                            Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
</body>
</html>