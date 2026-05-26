<?php
session_start();
include 'database/db_connect.php';

// if user is not logged in then go to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int) $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Verify if order belongs to user
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Only allow pending/paid cancellation
if (
    $order['status'] !== 'pending' &&
    $order['status'] !== 'paid'
) {
    die("This order can no longer be cancelled.");
}

// Update the status of the order
$update = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE order_id = ?");

$update->bind_param("i", $order_id);
$update->execute();

$_SESSION['message'] = "Order cancelled.";
$_SESSION['toastClass'] = "bg-danger";

header("Location: orders.php");
exit();


?>