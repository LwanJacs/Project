<?php
session_start();
require_once __DIR__ . '/../database/db_connect.php';

// Admin only
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../dashboard.php");
    exit();
}

if (!isset($_GET['prod_id'])) {
    die("Product not specified.");
}

$prod_id = (int)$_GET['prod_id'];

$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE prod_id = ?
");

$stmt->bind_param("i", $prod_id);
$stmt->execute();

$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['prod_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    $update = $conn->prepare("
        UPDATE products
        SET prod_name = ?,
            description = ?,
            price = ?,
            category = ?
        WHERE prod_id = ?
    ");

    $update->bind_param(
        "ssdsi",
        $name,
        $description,
        $price,
        $category,
        $prod_id
    );

    if ($update->execute()) {

        header("Location: manage_products.php");
        exit();

    } else {
        $message = "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

    <link rel="stylesheet" href="admin_edit_products.css">
</head>
<body>

<div class="edit-container">

    <a href="manage_products.php" class="back-btn">
        ← Back
    </a>

    <h1>Edit Product</h1>

    <?php if($message): ?>
        <div class="error-message">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Product Name</label>
        <input
            type="text"
            name="prod_name"
            value="<?= htmlspecialchars($product['prod_name']) ?>"
            required
        >

        <label>Description</label>
        <textarea
            name="description"
            rows="6"
            required><?= htmlspecialchars($product['description']) ?></textarea>

        <label>Price</label>
        <input
            type="number"
            step="0.01"
            name="price"
            value="<?= htmlspecialchars($product['price']) ?>"
            required
        >

        <label>Category</label>
        <input
            type="text"
            name="category"
            value="<?= htmlspecialchars($product['category']) ?>"
            required
        >

        <button type="submit" class="save-btn">
            Save Changes
        </button>

    </form>

</div>

</body>
</html>