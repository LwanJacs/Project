<?php 
session_start();
include '../database/db_connect.php';


$user_id = $_SESSION['user_id'];

$sql = "SELECT orders.order_id, orders.created_at, orders.status, users.username, products.prod_name, order_items.quantity, order_items.price 
        FROM order_items JOIN orders ON order_items.order_id = orders.order_id 
        JOIN products ON order_items.prod_id = products.prod_id
        JOIN users ON orders.user_id = users.user_id
        WHERE products.user_id = ? 
        AND orders.status = 'paid'
        ORDER BY orders.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders</title>
    <link rel="stylesheet" href="view_my_orders_style.css">
</head>
<body>
<div class="mt-3">
<div class="container">
    <h1 class="page-title">Customer Orders</h1>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="order-card">
            <h4>
                Order #<?= $row['order_id'] ?>
            </h4>

            <p>
                Product:
                <?= htmlspecialchars($row['prod_name']) ?>
            </p>

            <p>
                Buyer:
                <?= htmlspecialchars($row['username']) ?>
            </p>

            <p>
                Quantity:
                <?= $row['quantity'] ?>
            </p>

            <p>
                Amount:
                R<?= 
                number_format($row['price'] * $row['quantity'], 2) 
                ?>
            </p>

            <span class="status-paid">
                Paid
            </span>
        </div>
    <?php endwhile; ?>
</div>
  
<script src="../back_button.js"></script>
</body>
</html>