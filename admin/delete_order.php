<?php 
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    exit("Access denied.");
}

$order_id = $_GET['order_id'];
$stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
if ($stmt->execute()) {
    header("Location: manage_orders.php");
    exit();
} else {
    die("Error deleting order.");
}
?>