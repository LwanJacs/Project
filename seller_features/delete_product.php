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

// Check if product ID is provided
if (!isset($_GET['prod_id'])) {
    header("Location: my_products.php");
    exit();
}

$product_id = $_GET['prod_id'];
$user_id = $_SESSION['user_id'];

// Verify that the product belongs to the logged-in seller
$stmt = $conn->prepare("SELECT * FROM products WHERE prod_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$product = $result->fetch_assoc();

// if product doesn't exist or doesn't belong to the seller, redirect back to my_products.php
if (!$product) {
    header("Location: my_products.php");
    exit();
}

// Delete the product
$image_path = "../uploads/" . $product['image']; // Assuming images are stored in uploads directory

if (file_exists($image_path)) {
    unlink($image_path); // Delete the image file
}

// Delete the product from the database
$stmt = $conn->prepare("DELETE FROM products WHERE prod_id = ? AND user_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();

$_SESSION['message'] = "Product deleted successfully!";
$_SESSION['toastClass'] = "bg-danger";
header("Location: my_products.php");
exit();
?>