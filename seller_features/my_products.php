<?php 
session_start();
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';

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

$user_id = $_SESSION['user_id'];

// Get seller's products
$stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC");
// Check if prepare() succeeded
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="my_products_style.css" rel="stylesheet">
</head>
<body>
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goBack()">← Back</button>
    </div>
    <div class="container">
        <h1>My Products</h1>

        <div class="product-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['prod_name']) ?>">
                    <h3><?= htmlspecialchars($row['prod_name']) ?></h3>
                    <p class="price">R<?= htmlspecialchars($row['price']) ?></p>

                    <div class="buttons">
                        <!-- Edit button links to edit_product.php with the product id as a parameter -->
                        <a href="edit_product.php?prod_id=<?= htmlspecialchars($row['prod_id']) ?>" class="edit-btn">
                            Edit
                        </a>

                        <!-- Delete button links to delete_product.php with the product id as a parameter -->
                        <a href="delete_product.php?prod_id=<?= htmlspecialchars($row['prod_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">
                            Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../back_button.js"></script>
    
</body>
</html>