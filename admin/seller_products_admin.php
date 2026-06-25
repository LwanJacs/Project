<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access denied.");
}

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("Invalid seller ID.");
}

$user_id = $_GET['user_id'];

/* Get seller info */
$userStmt = $conn->prepare("
    SELECT username
    FROM users
    WHERE user_id = ?
");

$userStmt->bind_param("i", $user_id);
$userStmt->execute();

$userResult = $userStmt->get_result();
$seller = $userResult->fetch_assoc();

if (!$seller) {
    die("Seller not found.");
}

/* Get seller products */
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE user_id = ?
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seller Products</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>

<div class="admin-container">

    <a href="manage_users.php" class="back-btn">
        ← Back
    </a>

    <h1>
        Products Uploaded By
        <?= htmlspecialchars($seller['username']) ?>
    </h1>

    <?php if ($result->num_rows > 0): ?>

        <div class="product-grid">

            <?php while ($product = $result->fetch_assoc()): ?>

                <div class="product-card">

                    <img src="../uploads/<?= htmlspecialchars($product['image']) ?>"
                         alt="Product">

                    <h3>
                        <?= htmlspecialchars($product['prod_name']) ?>
                    </h3>

                    <p>
                        R<?= number_format($product['price'], 2) ?>
                    </p>

                    <p>
                        <?= htmlspecialchars($product['category']) ?>
                    </p>

                    <div class="product-actions">

                        <a href="admin_edit_products.php?prod_id=<?= $product['prod_id'] ?>"
                           class="admin-btn">
                           Edit
                        </a>

                        <a href="delete_product_admin.php?prod_id=<?= $product['prod_id'] ?>"
                           class="admin-btn delete-btn"
                           onclick="return confirm('Delete this product?')">
                           Delete
                        </a>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <p>This seller has not uploaded any products.</p>

    <?php endif; ?>

</div>

</body>
</html>