<?php
session_start();
include '../database/db_connect.php';

// Admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    header("Location: ../dashboard.php");
    exit();
}

$sql = "SELECT orders.order_id, orders.total_price, orders.status, orders.created_at, users.username FROM orders JOIN users ON orders.user_id = users.user_id ORDER BY orders.created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="manage_orders.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="top-bar">
            <a href="admin_dashboard.php" class="back-btn">
                ← Dashboard
            </a>
        </div>

        <h1>Manage Orders</h1>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= $order['order_id'] ?>
                        </td>

                        <td>
                            <?= $order['username'] ?>
                        </td>

                        <td>
                            R<?= number_format($order['total_price'], 2) ?>
                        </td>

                        <td>
                            <span class="status">
                                <?= ucfirst($order['status']) ?>
                            </span> 
                        </td>

                        <td>
                            <?= $order['created_at'] ?>
                        </td>

                        <td>
                            <a href="edit_order.php?id=<?= $order['order_id'] ?>" class="edit-btn">Edit</a>
                            <a href="delete_order.php?id=<?= $order['order_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>
    
</body>
</html>