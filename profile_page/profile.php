<?php
session_start();
include '../database/db_connect.php';

// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$stmt = $conn->prepare("
    SELECT user_id, name, surname, username, email,
           is_seller, is_admin, balance
    FROM users
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="profile_style.css" rel="stylesheet">

</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <button class="back-btn" onclick="goBack()">
                <i class="fa fa-arrow-left"></i> Back
            </button>
            <h1>My Profile</h1>
        </div>

        <!-- Display user information -->
         <div class="profile-card">
            <h3>Account Information</h3>
            <p>
                <strong>Name:</strong> <?= htmlspecialchars($user['name']) ?> 
            </p>
            <p>
                <strong>Surname:</strong> <?= htmlspecialchars($user['surname']) ?>
            </p>
            <p>
                <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?>
            </p>
            <p>
                <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?>
            </p>

            <p>
                <strong>Role:</strong>
                <?php
                if ($user['is_admin']) {
                    echo "Admin";
                } elseif ($user['is_seller']) {
                    echo "Seller";
                } else {
                    echo "Buyer";
                }
                ?>
            </p>
         </div>

         <!-- Wallet information -->
        <div class="profile-card">
            <h3>Wallet Information</h3>
            <p>
                <strong>Balance:</strong> $<?= number_format($user['balance'], 2) ?>
            </p>
        </div>

        <!-- Mock Cards -->
        <div class="profile-card">
            <h3>Payment Methods</h3>
            <div class="mock-card">
                <p>Visa Ending in 1234</p>
                <small>Expires 12/25</small>
            </div>

            <a href="add_card.php" class="action-btn">
                Add New Card
            </a>
        </div>

        <!-- Linked Accounts -->
        <div class="profile-card">
            <h3>Linked Accounts</h3>
            <p>No linked accounts.</p>

            <a href="link_account.php" class="action-btn">
                Add Account
            </a>
        </div>

        <!-- Security Settings -->
        <div class="profile-card">
            <h3>Security Settings</h3>

            <a href="change_password.php" class="action-btn">
                Change Password
            </a>
        </div>

        <!-- Danger Zone -->
        <div class="profile-card danger-zone">
            <h3>Danger Zone</h3>

            <a href="delete_account.php" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                Delete Account
            </a>
        </div>
    </div>

    <script src="../back_button.js"></script>
    
</body>
</html>