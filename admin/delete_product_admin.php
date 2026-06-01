<?php
session_start();
include '../database/dbconnect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    exit("Access denied.");
}

$prod_id = $_GET['prod_id'];
$stmt = $conn->prepare("DELETE FROM products WHERE prod_id = ?");
$stmt->bind_param("i", $prod_id);
if ($stmt->execute()) {
    header("Location: manage_products.php");
    exit();
} else {
    die("Error deleting product.");
}
?>