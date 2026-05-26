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

$message = "";

// Handle product upload form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id'];

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    // Image upload handling
    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    // Create unique filename to prevent overwriting
    $unique_image_name = uniqid() . '_' . $image_name;
    $upload_dir = "../uploads/";
    $upload_path = $upload_dir . $unique_image_name;

    // Move uploaded file to products directory
    if (move_uploaded_file($tmp_name, $upload_path)) {
        // Insert product into database
        $stmt = $conn->prepare("INSERT INTO products (user_id, prod_name, description, price, category, image) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdss", $user_id, $name, $description, $price, $category, $unique_image_name);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Product uploaded successfully!";
            $_SESSION['toastClass'] = "bg-success";

            header("Location: ../browse.php");
            exit();
        } else {
            $message = "Error uploading product.";
        }
    } else {
        $message = "Failed to upload image.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product</title>
    <link href="upload_product_style.css" rel="stylesheet">
</head>
<body>
    <form method="POST" enctype="multipart/form-data" class="form">

        <h2 class="title">Upload Product</h2>
        <?php if($message): ?>
            <p class="error-message">
                <!-- Display error message if there is one -->
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <label>
            <span>Product Name</span>
            <input type="text" name="name" required>
        </label>

        <label>
            <span>Description</span>
            <textarea name="description" rows="5" required></textarea>
        </label>

        <label>
            <span>Price</span>
            <input type="number" step="0.01" name="price" required>
        </label>

        <label>
            <span>Category</span>
            <input type="text" name="category" required>
        </label>

        <label class="file-upload">
            <input type="file" name="image" accept="uploads/*" required>
        </label>

        <button type="submit" class="submit-btn">Upload</button>

    </form>

    <script src="../back_button.js"></script>
    
</body>
</html>