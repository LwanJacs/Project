<?php
session_start();
include 'database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from email in users table
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT user_id, is_seller FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$user_id = $user['user_id'];

// If already a seller, redirect to dashboard
if($user['is_seller'] == 1) {
    $_SESSION['message'] = "You are already registered as a seller.";
    $_SESSION['toastClass'] = "bg-success";
    header("Location: seller_dashboard.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Cleaner input handling by removing accidental spacing
    $store_name = trim($_POST['store_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Checking if the store name is already taken
    $check = $conn->prepare("SELECT seller_id FROM sellers WHERE store_name = ?");

    $check->bind_param("s", $store_name);
    $check->execute();

    $exists = $check->get_result();
    if ($exists->num_rows > 0) {
        $message ="Store name already taken.";
    } else{
        // Insert into sellers table
        $stmt = $conn->prepare("INSERT INTO sellers (user_id, store_name, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $store_name, $phone, $address);

    
        if ($stmt->execute()) {

        // Update user to be a seller
        $update_stmt = $conn->prepare("UPDATE users SET is_seller = 1 WHERE user_id = ?");
    
        $update_stmt->bind_param("i", $user_id);
        $update_stmt->execute();
        $_SESSION['is_seller'] = 1; // Update session variable to reflect new seller status

        $_SESSION['message'] = "You are now registered as a seller!";
        $_SESSION['toastClass'] = "bg-success";
        header("Location: seller_dashboard.php");
        exit(); 
        } else {
            $message = "Something went wrong. Please try again.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="seller_register_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Become a Seller</title>
</head>
<body>
    <form method="POST" action="seller_register.php">

        <h2 class="title">Become a Seller</h2>
        
        <!-- Store Name -->
        <?php if ($message): ?>
            <p class="text-danger text-center"><?= $message ?></p>
        <?php endif; ?>

        <label>
            <input type="text" name="store_name" required>
            <span>Store Name</span>
        </label>

        <label>
            <input type="text" name="phone" required>
            <span>Phone Number</span>
        </label>

        <label>
            <input type="text" name="address" required>
            <span>Address</span>
        </label>

        <label> 
            <textarea name="bio" rows="4"></textarea>
            <span>Store Bio (optional)</span>
        </label>

        <button type="submit" class=button>Register as Seller</button>

    </form>
</body>
</html>