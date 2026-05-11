<?php
session_start();
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';

if (!isset($SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get user ID
$email = $SESSION['email'];
$stmt = $conn->prepare("SELECT id, is_seller FROM userdata WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$user_id = $user['id'];

// If already a seller, redirect to dashboard
if($user['is_seller'] == 1) {
    header("Location: dashboard.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $store_name = $_POST['store_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Insert into sellers table
    $stmt = $conn->prepare("INSERT INTO sellers (user_id, store_name, phone, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $store_name, $phone, $address);
    
    if ($stmt->execute()) {

    // Update user to be a seller
    $update_stmt = $conn->prepare("UPDATE userdata SET is_seller = 1 WHERE id = ?");
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();

    $_SESSION['message'] = "You are now registered as a seller!";
    header("Location: dashboard.php");
    exit(); 
    } else {
        $message = "Something went wrong. Please try again.";

    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Seller</title>
</head>
<body>
    <form method="POST" action="form">

        <h5>Become a Seller</h5>
        
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

        <button type="submit" class=button>Register as Seller</button>

    </form>
</body>
</html>