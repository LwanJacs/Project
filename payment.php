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


$card_stmt = $conn->prepare("SELECT * FROM payment_cards WHERE user_id = ? LIMIT 1");

$card_stmt->bind_param("i", $user_id);
$card_stmt->execute();

$card_result = $card_stmt->get_result();
$card = $card_result->fetch_assoc();

if (!$card) {
    $no_card = true;
} else {
    $no_card = false;
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

    $password = $_POST['password'];

    $pw_stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $pw_stmt->bind_param("i", $user_id);
    $pw_stmt->execute();

    $pw_result =$pw_stmt->get_result();
    $user_data = $pw_result->fetch_assoc();

    if (!password_verify($password, $user_data['password'])) {
        $error = "Incorrect password.";
    } else {
        // Continue payment
    }

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
        <?php if ($no_card): ?>
            <div class="alert alert-warning">
                No payment methods found.
            </div>
            <a href="profile_page/add_card.php" class="btn btn-primary w-100">
                Add Card
            </a>
        <?php else: ?>
            <div class="saved-card">
                <h5>
                    <?= htmlspecialchars($card['card_type']) ?>
                </h5>
                <p>
                    <?= htmlspecialchars($card['card_number']) ?>
                </p>
                <p>
                    Expires:
                    <?= htmlspecialchars($card['expiry_date']) ?>
                </p>
            </div>

            <form method="POST">
                
                <div class="mb-3">
                    <label>Confirm Account Password</label>
                    <input type="password" name="password" class="form-control" required>

                </div>
                <button type="submit" class="btn btn-success w-100">
                    Pay R<?= number_format($total, 2) ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="back_button.js"></script>
</body>
</html>