<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Access denied.");
}

$user_id = (int)$_GET['user_id'];

//Prevent admin from deleting themselves//

if ($user_id == $_SESSION['user_id']) {

    $_SESSION['message'] =
        "You cannot delete your own account.";

    $_SESSION['toastClass'] = "bg-danger";

    header("Location: manage_users.php");
    exit();
}

$stmt = $conn->prepare("
    DELETE FROM users
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {

    $_SESSION['message'] =
        "User deleted successfully.";

    $_SESSION['toastClass'] = "bg-success";

} else {

    $_SESSION['message'] =
        "Failed to delete user.";

    $_SESSION['toastClass'] = "bg-danger";
}

header("Location: manage_users.php");
exit();
?>