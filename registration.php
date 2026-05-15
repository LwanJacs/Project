<?php
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
$message = "";
$toastClass = "";

if($_SERVER["REQUEST_METHOD"] =="POST"){
    $fname = $_POST['name'];
    $lname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");

    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();
    //If number of rows returned is greater than 0, then email exists.
    if($checkEmailStmt->num_rows > 0){
        $message = "Email ID already exists";
        $toastClass = "#007bff"; 
    } else {
        //Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (name, surname, username, email, password)
        VALUES (?, ?, ?, ?, ?)");
        //Safely inserts data into the table with a prepared statement. 'sssss' means all the values are string.
        $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);
        
        // Checking if account registered successfully
        if($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#28a745";
        } else {
            $message = "Error ";
            $toastClass = "#dc3545";
        }

        $stmt->close();
    }
    $checkEmailStmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Registration</title>
</head>
<body>
    <?php if ($message): ?>
        <div class="toast" style="background-color: <?php echo $toastClass; ?>;">
            <span><?php echo $message; ?></span> <!-- inserts message-->
            <button onclick="closeToast()">X</button><!-- inserts color-->
        </div>
    <?php endif; ?>
    <form method="post" class="form">

    <button type="button" 
    class="btn-close btn-close-white custom-close-btn close-form-btn"
    aria-label="Close"></button>

    <div class="title">
        <h5>Create Your Account</h5>
    </div>
    <div class="form-group">
        <label>
            <input type="text" name="name" required>      
            <!---this for a float labeling effect to create a clean animation.-->
            <span>Firstname</span>
        </label>
        <label>
            <input type="text" name="surname" required>
            <span>Surname</span>
        </label>
    </div>
        <label>
            <input type="text" name="username" required>
            <span>Username</span>
        </label>
        <label>
            <input type="email" name="email" required>
            <span>Email</span>
        </label>
        <label>
            <input type="password" name="password" required>
            <span>Password</span>
        </label>
        <button class="submit">Create Account</button>
        <p class="signin">
                Already have an account?
                <a href="./login.php">Signin</a>
        </p>
        
    </form>
<script src="close_btn.js"></script>
</body>
</html>
