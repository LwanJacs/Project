<?php 
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];
// Get current user's email
$stmt = $conn->prepare("SELECT email FROM users WHERE user_id = ?");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$email = $user['email'];

// Finding all accounts that have the same email
$stmt = $conn->prepare("SELECT user_id, username, is_seller, is_admin FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$accounts = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linked Accounts</title>
    <link rel="stylesheet" href="link_account_style.css">
</head>
<body>
    <div class="accounts-container">

    <div class="top-bar">

        <button class="back-btn" onclick="goBack()">
            Back
        </button>

        <a href="../coming_soon.php" class="add-btn">
            + Link Account
        </a>

    </div>

    <h1>Linked Accounts</h1>

    <?php while($account = $accounts->fetch_assoc()): ?>

        <div class="account-card">

            <div class="account-info">

                <h3>
                    <?= htmlspecialchars($account['username']) ?>
                </h3>

                <p>

                    <?php
                    if ($account['is_admin']) {
                        echo "Administrator";
                    }
                    elseif ($account['is_seller']) {
                        echo "Seller";
                    }
                    else {
                        echo "Customer";
                    }
                    ?>

                </p>

            </div>

            <div class="account-actions">

                <?php if ($account['user_id'] == $current_user_id): ?>

                    <span class="current-badge">
                        Current Account
                    </span>

                <?php else: ?>

                    <a href="switch_account.php?id=<?= $account['user_id'] ?>" class="switch-btn">
                        Switch
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php endwhile; ?>

</div>

<script src="../back_button.js"></script>

</body>
</html>