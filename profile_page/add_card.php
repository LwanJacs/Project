<?php
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cardholder_name = trim($_POST['cardholder_name']);
    $card_number = preg_replace('/\s+/', '', $_POST['card_number']);
    $expiry_date = trim($_POST['expiry_date']);

    // Determine card type
    if (substr($card_number, 0, 1) == "4") {
        $card_type = "Visa";
    } elseif (substr($card_number, 0, 1) == "5") {
        $card_type = "Mastercard";
    } else {
        $card_type = "Unknown";
    }

    $masked_card = "**** **** **** ".substr($card_number, -4);

    $stmt = $conn->prepare("INSERT INTO payment_cards (user_id, cardholder_name, card_number, expiry_date, card_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $cardholder_name, $masked_card, $expiry_date, $card_type);

    if ($stmt->execute()) {
        $_SESSION['meesage'] = "Card added successfully.";

        $_SESSION['toastClass'] = "bg-success";

        header("Location: profile.php");
    } else {
        $message = "Failed to add card";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="add_card_style.css">
</head>
<body>
    <div class="card-container">
        <button class="back-btn" onclick="goBack()">
            Back
        </button>
        
        <form method="POST" class="card-form">

            <h2>Add Payment Card</h2>
            <?php if($message): ?>
                <div class="error">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <label>
                Cardholder Name
                <input type="text" name="cardholder_name" required>
            </label>

            <label>
                Card Number
                <input type="text" name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" required>
            </label>

            <label>
                Expiry Date
                <input type="text" name="expiry_date" placeholder="MM/YY" required>
            </label>

            <button type="submit" class="submit-btn">
                Add Card
            </button>

        </form>
    </div>

    <script src="../back_button.js"></script>
    
</body>
</html>