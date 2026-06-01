<?php
session_start();
include '../database/db_connect.php';
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !=1) {
    exit("Access denied.");
}

$user_id = $_GET['user_id'];

$conn->query("DELETE FROM sellers WHERE user_id = $user_id");
$conn->query("UPDATE users SET is_seller = 0 WHERE user_id = $user_id");
header("Location: manage_sellers.php");
exit();
?>