<?php
session_start();
include '../database/db_connect.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get current password hash from database
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $message = "User not found.";
        $toastClass = "bg-danger";
    } elseif (!password_verify($current_password, $user['password'])) {
        $message = "Current password is incorrect.";
        $toastClass = "bg-danger";
    } elseif ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
        $toastClass = "bg-danger";
    } elseif (strlen($new_password) < 6 || !preg_match('/[A-Z]/' , $new_password) || !preg_match('/[0-9]/', $new_password)) {
        $message = "New password must be at least 6 characters long and include at least one uppercase letter and one number.";
        $toastClass = "warning";
    } else {
        // Hash the new password and update in database
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $update_stmt->bind_param("si", $new_password_hash, $user_id);
        // Execute the update statement
        if ($update_stmt->execute()) {
            $message = "Password changed successfully.";
            $toastClass = "bg-success";
        } else {
            $message = "Error updating password. Please try again.";
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
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="change_password_style.css" rel="stylesheet">
</head>
<body>
    <div class="password-container">
        <button class="back-btn" onclick="goBack()">
            Back
        </button> 

        <form method="POST" class="password-card">
            <h2>Change Password</h2>

            <?php if($message): ?>
                <div class="alert alert-<?= $toastClass ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <label>
                Current Password:
                <input type="password" name="current_password" required>
            </label>
            <label>
                New Password:
                <input type="password" name="new_password" required>
            </label>
            <label>
                Confirm New Password:
                <input type="password" name="confirm_password" required>
            </label>
            <button type="submit" class="submit-btn">Update Password</button>
        </form>
    </div>
    <script src="../back_button.js"></script>
</body>
</html>