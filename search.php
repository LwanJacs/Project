<?php
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
$query = "";
$result = null;

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    // Prepared statement (SAFELY handles user input)
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $searchTerm = "%" . $query . "%";

    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();

    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="browse_style.css">

</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">
            Search Results for "<?=  htmlspecialchars($query) ?>"
        </h2>

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card product-card h-100">
                            
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                            
                            <div class="card-body">
                                
                                <h5 class="card-title"> <?= htmlspecialchars($row['name']) ?></h5>
                                <p class="card-text"> <?= htmlspecialchars($row['description']) ?> </p>
                                <p class="card-text"><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
                            
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No products found.</p>
            <?php endif; ?>
        </div>
    </div>
    
    
</body>
</html>
