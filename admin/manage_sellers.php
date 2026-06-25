<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    header("Location: ../dashboard.php");
    exit();

}

// Fetch sellers with product count so that we can display in the table
$sql = "SELECT sellers.seller_id, sellers.store_name, sellers.user_id, users.username, users.email, COUNT(products.prod_id) AS total_products FROM sellers JOIN users ON sellers.user_id = users.user_id LEFT JOIN products ON sellers.user_id = products.user_id GROUP BY sellers.seller_id ORDER BY sellers.store_name ASC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Sellers</title>
    <link rel="stylesheet" href="manage_sellers.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <div class="top-bar">
            <a href="admin_dashboard.php" class="back-btn">
                ← Dashboard
            </a>
        </div>

        <h1>Manage Sellers</h1>
        <table class="seller-table">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Products</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php while($seller = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($seller['store_name']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($seller['username']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($seller['email']) ?>
                    </td>

                    <td>
                        <?= $seller['total_products'] ?>
                    </td>

                    <td>
                        <a href="seller_products_admin.php?user_id=<?= $seller['user_id'] ?>" class="view-btn">
                            View
                        </a>
                        <a href="remove_seller_admin.php?seller_id=<?= $seller['seller_id'] ?>" class="remove-btn" onclick="return confirm('Are you sure you want to remove seller privileges?')">
                            Delete
                        </a>
                    </td>

                </tr>

            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>