<?php
session_start();
include 'database/db_connect.php';

if (!isset($_SESSION['pending_order_id'])) {
    header("Location: cart.php");
    exit();
}

$order_id = $_SESSION['pending_order_id'];
$user_id = $_SESSION['user_id'];

// Get order info
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? and user_id = ?");

$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

$total  = $order['total_price'];

// Get user balance
$balance_stmt = $conn->prepare("SELECT balance FROM users WHERE user_id = ?");
$balance_stmt->bind_param("i", $user_id);
$balance_stmt->execute();

$balance_result = $balance_stmt->get_result();
$user = $balance_result->fetch_assoc();

$current_balance = $user['balance'];


// Check if user has enough money
if ($current_balance < $total) {
    $_SESSION['message'] = "Insufficient funds.";
    $_SESSION['toastClass'] = "bg-danger";

    header("Location: cart.php");
    exit();
}


//Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Deduct balance
    
    $new_balance = $current_balance - $total;
    $update_balance = $conn->prepare("UPDATE users SET balance = ? WHERE user_id = ?");
    $update_balance->bind_param("di", $new_balance, $user_id);
    $update_balance->execute();

    
    //Update order status
    $update = $conn->prepare("UPDATE orders SET status = 'paid' WHERE order_id = ?");

    $update->bind_param("i", $order_id);
    $update->execute();

    // Clear cart
    unset($_SESSION['cart']);
    unset($_SESSION['pending_order_id']);

    $_SESSION['message'] = "Payment successfully!";
    $_SESSION['toastClass'] = "bg-success";

    header("Location: orders.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="payment_style.css" rel="stylesheet">
</head>
<body>
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goBack()">
            ← Back
        </button>
    </div>
    <div class="payment-container">
        <h2>Complete Payment</h2>
        <div class="order-summary">
            <p><strong>Order ID:</strong> #<?= $order['order_id'] ?></p>

            <p>
                <strong>Total:</strong>
                R<?= number_format($order['total_price'], 2) ?>
            </p>

            <p>
                <strong>Wallet Balance</strong>
                R<?= number_format($current_balance, 2) ?>
            </p>
        </div>

        <form method="POST">
            <div class="mb-3">
                <label>Cardholder Name</label>
                <input type="text" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Card Number</label>
                <input type="text" class="form-control" maxlength="16" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Expiry Date</label>
                    <input type="text" class="form-control" placeholder="MM/YY" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>CVV</label>
                    <input type="password" class="form-control" maxlength="3" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-100">Pay Now</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="back_button.js"></script>
</body>
</html>