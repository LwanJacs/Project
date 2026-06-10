<?php 
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM payment_cards WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cards</title>
    <link rel="stylesheet" href="list_cards_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="cards-container">
        <button class="back-btn" onclick="goBack()">
            Back
        </button>
        <a href="add_card.php" class="add-btn">
            + Add Card
        </a>
    </div>
    
    <h1>My Payment Cards</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while($card = $result->fetch_assoc()): ?>

            <div class="card-box">
                <div class="card-header">
                    <h3>
                        <?= htmlspecialchars($card['card_type']) ?>
                    </h3>
                </div>

                <div class="card-details">
                    <p>
                        <strong>Cardholder:</strong>
                        <?= htmlspecialchars($card['cardholder_name']) ?>
                    </p>

                    <p>
                        <strong>Number:</strong>
                        <?= htmlspecialchars($card['card_number']) ?>
                    </p>

                    <p>
                        <strong>Expiry:</strong>
                        <?= htmlspecialchars($card['expiry_date']) ?>
                    </p>
                </div>
                <div class="card-actions">
                    <a href="delete_card.php?card_id=<?= $card['card_id'] ?>" class="delete-btn" onclick="return confirm('Delete this card?')">
                        Delete
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        
        <div class="empty-box">
            <p>No payment cards added yet.</p>
            <a href="add_card.php" class="add-first-btn">
                Add Your First Card
            </a>
        </div>
    <?php endif; ?>

    <script src="../back_button.js"></script>

</body>
</html>