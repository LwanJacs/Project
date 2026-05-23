<?php 
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
// Fetches from the db 
$sql = "SELECT * FROM products WHERE 1=1";

// Adding filters
if (!empty($_GET['search'])) {
    // Prevent SQL injection
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " AND prod_name LIKE '%$search%'";
}

// check if category filter is set and valid
if (!empty($_GET['category'])) {
    // validating category input to prevent SQL injection
    $category = $conn->real_escape_string($_GET['category']);
    
    $sql .= " AND category = '$category'";
}
// check if max price filter is set and valid
if (!empty($_GET['max_price'])) {
    // validating max price input to prevent SQL injection
    $price = (int) $_GET['max_price'];
    $sql .= " AND price <= $price";
}

// Sorting
$sort = $_GET['sort'] ?? 'newest';
// default sorting is by newest, if 'cheapest' is selected, sort by price
if ($sort === 'cheapest') {
    $sql .= " ORDER BY price ASC";
} else {
    $sql .= " ORDER BY created_at DESC";
}

$result = $conn->query($sql);

if (!$result) {
    die("Error fetching products: " . $conn->error);
}
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
    <div class="mt-3">
        <button class="btn btn-outline-secondary mt-3 ms-3" onclick="goHome()">← Back</button>
    </div>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2>Browse Products</h2>

            <form class="d-flex" method="GET" action="search.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search...">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>

        <form method="GET" class="filter-bar mb-5">
            <div class="row g-3 align-items-end">

            <!-- Search -->
             <div class="col-md-3">
                <label class="form-label text-light">Search</label>
                <input type="text" name="search" class="form-control"
                       value="<?=  isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
             </div>

            <!-- Category -->
            <div class="col-md-3">
                <label class="form-label text-light">Category</label>
                <select name="category" class="form-control">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="books">Books</option>
                    <!-- Add category options here -->
                </select>
            </div>

            <!-- Sort -->
            <div class="col-md-2">
                <label class="form-label text-light">Sort By</label>
                    <select name="sort" class="form-control">
                    <option value="newest">Newest</option>
                    <option value="cheapest">Cheapest</option>
                    </select>
            </div>

            <!-- Price Range -->
            <div class="col-md-2">
                <label class="form-label text-light">Max Price</label>
                <input type="number" name="max_price" class="form-control"
                        placeholder="e.g. 500">
            </div>

            <!-- Filter button -->
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Apply Filters</button>
            </div> 

            </div>
            </div>
        </form>


        <!-- Product Grid -->
        <div class="row g-4">
            <?php if($result && $result->num_rows == 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <!-- This is the product card, it displays the product image, name, price, description and seller name -->
                        <div class="card product-card h-100">
                            <img src="uploads/<?=  htmlspecialchars($row['image']) ?>" class="card-img-top" alt="Product Image">
                            <!-- Card body with product details and buttons -->
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['prod_name']) ?></h5>

                                <p class="price">R<?= htmlspecialchars($row['price']) ?></p>

                                <p class="card-text small text-muted">
                                    <?= substr(htmlspecialchars($row['description']), 0, 60) ?>...
                                </p>
                                <!-- Display seller name, if available, otherwise show 'Unknown' -->
                                <p class="seller_name">
                                    Sold by: <?=  htmlspecialchars($row['seller_name'] ?? 'Unknown') ?>
                                </p>

                                <!-- Buttons -->
                                <div class="mt-auto">
                                    <a href="view_details.php?prod_id=<?=  htmlspecialchars($row['prod_id']) ?>" class="btn btn-primary btn-sm w-100 mb-2">
                                    View Details
                                    </a>
                                    <!-- Add to cart button, it links to add_to_cart.php with the product id as a parameter -->
                                    <a href="add_to_cart.php?prod_id=<?=  htmlspecialchars($row['prod_id']) ?>" class="btn btn-success btn-sm w-100">
                                        Add to Cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="alert alert-warning text-center">No products matching your search.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="close_btn.js"></script>
</body>
</html>