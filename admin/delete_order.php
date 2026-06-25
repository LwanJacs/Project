<?php
session_start();
include '../database/db_connect.php';

// Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access denied.");
}

// Check if order ID exists
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid order ID.");
}

$order_id = $_GET['order_id'];

// Delete the order
$stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);

if ($stmt->execute()) {

    $_SESSION['message'] = "Order deleted successfully.";
    $_SESSION['toastClass'] = "bg-success";

    header("Location: manage_orders.php");
    exit();

} else {

    $_SESSION['message'] = "Failed to delete order.";
    $_SESSION['toastClass'] = "bg-danger";

    header("Location: manage_orders.php");
    exit();
}
?>