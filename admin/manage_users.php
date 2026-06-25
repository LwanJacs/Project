<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    header("Location: ../dashboard.php");
    exit();
}


$result = $conn->query("SELECT user_id, name, surname, username, email, balance, is_seller, is_admin FROM users ORDER BY user_id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <a href="admin_dashboard.php" class="back-btn">
            ← Dashboard
        </a>

        <h1>Manage Users</h1>
        <div class="table-container">

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Seller</th>
                        <th>Admin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td>
                                <?= htmlspecialchars($user['name']) ?>
                                <?= htmlspecialchars($user['surname']) ?>
                            </td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= number_format($user['balance']) ?></td>
                            <td>
                                <?= $user['is_seller'] ? 'Yes' : 'No' ?>
                            </td>
                            <td>
                                <?= $user['is_admin'] ? 'Yes' : 'No' ?>
                            </td>
                            <td>
                                <a href="admin_delete_user.php?user_id=<?= $user['user_id'] ?>" class="admin-btn delete-btn"
                                onclick="return confirm('Delete this user?')">
                                Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>