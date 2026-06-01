<?php
session_start();
include '../database/db_connect.php';

// Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    exit("Access denied.");
}

$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $status, $order_id);
    if ($stmt->execute()) {
        header("Location: manage_orders.php");
        exit();
    } else {
        $error = "Error updating order status.";
    }

    $order = $stmt->get_result()->fetch_assoc();
}
?>

<form method="POST">
    <h2>Edit Order #<?= $order_id ?></h2>
    <select name="status" required>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
    </select>

    <button type="submit">Update Status</button>
</form>
