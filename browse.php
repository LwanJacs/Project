<?php 
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="browse_style.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Browse Products</h2>
        <div class="row g-4">

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-12 col-sm6 col-md-4 col-lg-3">
                
                <div class="card product-card h-100">
                    <img src="<?=  $row['image'] ?>" class="card-img-top" alt="Product Image">

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $row['name'] ?></h5>

                        <p class="price">R<?= $row['price'] ?></p>

                        <p class="card-text small text-muted">
                            <?= substr($row['description'], 0, 60) ?>...
                        </p>

                        <!-- Buttons -->
                        <div class="mt-auto">
                            <a href="product.php?id=<?=  $row['id'] ?>" class="btn btn-primary btn-sm w-100 mb-2">
                                View Details
                            </a>
                            <a href="add_to_cart.php?id=<?=  $row['id'] ?>" class="btn btn-success btn-sm w-100">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        </div>
    </div>
    
</body>
</html>