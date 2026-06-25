<?php
session_start();
include '../database/db_connect.php';

// Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Access denied.");
}


if (!isset($_GET['order_id'])) {
    die("Order ID missing.");
}

$order_id = (int)$_GET['order_id'];
$error = "";

// Get current order
$order_stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE order_id = ?
");

$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();

$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// Update order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE orders
        SET status = ?
        WHERE order_id = ?
    ");

    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {

        header("Location: manage_orders.php");
        exit();

    } else {
        $error = "Error updating order status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>

    <link rel="stylesheet" href="edit_order.css">
</head>
<body>

<div class="edit-container">

    <a href="manage_orders.php" class="back-btn">
        ← Back
    </a>

    <h2>Edit Order #<?= $order_id ?></h2>

    <?php if (!empty($error)): ?>
        <div class="error-box">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="order-info">
        <p>
            <strong>Total:</strong>
            R<?= number_format($order['total_price'], 2) ?>
        </p>

        <p>
            <strong>Current Status:</strong>
            <?= ucfirst($order['status']) ?>
        </p>
    </div>

    <form method="POST">

        <label>Status</label>

        <select name="status" required>

            <option value="pending"
                <?= $order['status']=='pending' ? 'selected' : '' ?>>
                Pending
            </option>

            <option value="paid"
                <?= $order['status']=='paid' ? 'selected' : '' ?>>
                Paid
            </option>

            <option value="shipped"
                <?= $order['status']=='shipped' ? 'selected' : '' ?>>
                Shipped
            </option>

            <option value="delivered"
                <?= $order['status']=='delivered' ? 'selected' : '' ?>>
                Delivered
            </option>

            <option value="cancelled"
                <?= $order['status']=='cancelled' ? 'selected' : '' ?>>
                Cancelled
            </option>

        </select>

        <button type="submit">
            Update Status
        </button>

    </form>

</div>

</body>
</html>