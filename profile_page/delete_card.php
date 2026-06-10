<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$card_id = $_GET['card_id'] ?? 0;

$stmt = $conn->prepare("
    DELETE FROM user_cards
    WHERE card_id = ?
    AND user_id = ?
");

$stmt->bind_param("ii", $card_id, $user_id);
$stmt->execute();

header("Location: list_cards.php");
exit();
?>