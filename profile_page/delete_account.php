<?php 
session_start();
include '../database/db_connect.php';

$message =  "";
$toastClass = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];

    // Get current password hash from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $message = "User not found.";
        $toastClass = "bg-danger";
    } elseif (!password_verify($password, $user['password'])) {
        $message = "Password is incorrect.";
        $toastClass = "bg-danger";
    } else {
        // Start transaction
        $conn->begin_transaction();
        
        // try to delete user 
        try {
            // Delete seller account if user is a seller
            $seller_stmt = $conn->prepare("DELETE FROM sellers WHERE user_id = ?");
            $seller_stmt->bind_param("i", $user_id);
            $seller_stmt->execute();

            // Delete products associated with the user
            $product_stmt = $conn->prepare("DELETE FROM products WHERE user_id = ?");
            $product_stmt->bind_param("i", $user_id);
            $product_stmt->execute();

            // Delete user orders
            $orders_stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
            $orders_stmt->bind_param("i", $user_id);  
            $orders_stmt->execute();

            // Delete user account
            $delete_stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $delete_stmt->bind_param("i", $user_id);
            $delete_stmt->execute();

            // Commit transaction
            $conn->commit();
            session_destroy();
            header("Location: ../index.php");
            exit();

        } catch (Exception $e) {
            
            $conn->rollback();
            $message = "Error deleting account: " . $e->getMessage();
            $toastClass = "bg-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="delete_account_style.css">
</head>
<body>
    <div class="delete-container">
        <button class="back-btn" onclick="goBack()">
            Back
        </button>

        <div class="delete-card">
            <h2>Delete Account</h2>

            <p class="warning">
                Warning: This action cannot be undone.
            </p>

            <?php if($message): ?>
                <div class="alert alert-<?= $toastClass ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>
                    Enter Password
                    <input type="password" name="password" required>
                </label>

                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to permanently delete your account?')">
                    Delete My Account
                </button>
            </form>
        </div>

    </div>

    <script src="../back_button.js"></script>
    
</body>
</html>