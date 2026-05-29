<?php 
session_start();
include '../database/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_FILES['banner']) && $_FILES['banner']['error'] === 0) {

        $file_name = time() . "_" . basename(($_FILES['banner']['name']));

        $target_dir = "../uploads/banners/";

        // Create folder if it doesn's exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
         
        $target_file = $target_dir . $file_name;
        
        move_uploaded_file(
            $_FILES['banner']['tmp_name'],
            $target_file
        );

        // Svae filename in database
        $stmt = $conn->prepare("UPDATE sellers SET banner_image = ? WHERE user_id = ?");
        $stmt->bind_param("si", $file_name, $user_id);
        $stmt->execute();

        header("Location: ../seller_dashboard.php");
        exit();

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Banner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Upload Store Banner</h2>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <input type="file" name="banner" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Upload Banner</button>
        </form>
    </div>
    
</body>
</html>