<?php
session_start();
include 'database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    header("Location: orders.php");
    exit();
}

// Getting the current order and logged in user 
$order_id = (int) $_GET['order_id'];
$user_id = $_SESSION['user_id'];

// Get order
$order_stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE order_id = ?
    AND user_id = ?
");

$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();

$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}


// Get order items from database
$items_stmt = $conn->prepare("SELECT order_items.*, products.prod_name, products.image FROM order_items JOIN products ON order_items.prod_id = products.prod_id WHERE order_items.order_id = ?");
if (!$items_stmt) {
    die("Prepare failed: " . $conn->error);
}
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();

$items = $items_stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goBack()">
            ← Back
        </button>
    </div>
    <div class="container mt-5">

        <h2>Order #<?= $order['order_id'] ?></h2>

        <p class="form-control">
            Status:
            <strong><?= ucfirst($order['status']) ?></strong>
        </p>

        <p class="form-control">
            Total:
            <strong>R<?= number_format($order['total_price'],2) ?></strong>
        </p>

        <hr>
        <?php while ($item = $items->fetch_assoc()): ?>
            <div class="card mb-3 p-3">
                
                <div class="row align-items-center">
                    
                    <div class="col-md-2">
                        <img src="uploads/<?= htmlspecialchars($item['image']) ?>"
                        class="img-fluid rounded">

                    </div>

                    <div class="col-md-10">
                        <h5>
                            <?= htmlspecialchars($item['prod_name']) ?>
                        </h5>

                        <p class="card-form">
                            Quantity:
                            <?= $item['quantity'] ?>
                        </p>
                        <p class="card-form">
                            Price:
                            R<?= number_format($item['price'], 2) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="back_button.js"></script>

</body>
</html>