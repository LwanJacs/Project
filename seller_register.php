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
    
    }
    }
?>