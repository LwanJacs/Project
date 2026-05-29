<?php
session_start();
include '../database/db_connect.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Must be a seller
if (!isset($_SESSION['is_seller']) || $_SESSION['is_seller'] != 1) {
    header("Location: ../dashboard.php");
    exit();
}

// Get product ID from query parameter
if (!isset($_GET['prod_id'])) {
    header("Location: my_products.php");
    exit();
}

// Sanitize product ID to prevent SQL injection
$product_id = (int)$_GET['prod_id'];
$user_id = $_SESSION['user_id'];

// Fetch product details to pre-fill the form
$stmt = $conn->prepare("SELECT * FROM products WHERE prod_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$results = $stmt->get_result();
$product = $results->fetch_assoc();

// if the product doesn't exist or doesn't belong to the seller, redirect back to my_products.php
if (!$product) {
    header("Location: my_products.php");
    exit();
}


// Handle form submission for editing the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['prod_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    // Update product details in the database
    $stmt = $conn->prepare("UPDATE products SET prod_name = ?, description = ?, price = ?, category = ? WHERE prod_id = ? AND user_id = ?");
    $stmt->bind_param("ssdsii", $name, $description, $price, $category, $product_id, $user_id);
    // Check if the update was successful
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
        $_SESSION['toastClass'] = "bg-success";
        header("Location: my_products.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="edit_product_style.css" rel="stylesheet">
</head>
<body>
    <form method="POST" class="container">
        <h1 class="title">Edit Product</h1>
        <label>
            <span>Product Name:</span>
            <input type="text" name="prod_name" value="<?= htmlspecialchars($product['prod_name']) ?>" required>
        </label>
        <label>
            <span>Description:</span>
            <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
        </label>
        <label>
            <span>Price:</span>
            <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>
        </label>
        <label>
            <span>Category:</span>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>" required>
        </label>
        <button type="submit" class="buttons">Update Product</button>
    </form>
    

    
</body>
</html>