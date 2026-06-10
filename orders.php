<?php 
session_start();
include 'database/db_connect.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="orders_style.css" rel="stylesheet">
</head>
<body>
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goBack()">
            ← Back
        </button>
    </div>
    <div class="container mt-5">
        <h2>My Orders</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($order = $result->fetch_assoc()): ?>
                <div class="card mb-3 p-3">

                    <h5>Order #<?= $order['order_id'] ?></h5>
                    <p>
                        Total:
                        R<?= number_format($order['total_price'], 2) ?>
                    </p>
                    <p>
                        Status:
                        <?php if ($order['status'] == 'paid'): ?>
                            <span class="status-badge status-paid">
                                Paid
                            </span>
                        <?php elseif ($order['status'] == 'cancelled'): ?>
                            <span class="status-badge status-cancelled">
                                Cancelled
                            </span>
                        <?php elseif ($order['status'] == 'pending'): ?>
                            <span class="status-badge status-pending">
                                Pending
                            </span>
                        <?php else: ?>
                            <span class="status-badge">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        <?php endif; ?>
                    </p>
                    <small class="d-fkex gap-2">
                        <?= $order['created_at'] ?>
                    </small>
                
                    <div class="d-flex gap-2">
                        <!--View Details -->
                        <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary btn-sm">View Details</a>

                        <!-- Cancel Order -->
                        <?php if (
                            $order['status'] == 'pending'
                        ): ?>
                            <a href="cancel_order.php?order_id=<?= $order['order_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this order?')">Cancel Order</a>

                        <?php endif; ?>
                    </div>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                You have no orders yet.
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="back_button.js"></script>
    
</body>
</html>