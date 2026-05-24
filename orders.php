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

</head>
<body>
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
                        <?= htmlspecialchars($order['status']) ?>
                    </p>
                    <small>
                        <?= $order['created_at'] ?>
                    </small>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                You have no orders yet.
            </div>
        <?php endif; ?>
    </div>
    
</body>
</html>