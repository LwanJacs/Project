<?php
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
$message = "";
$toastClass = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Prepare and execute
    $stmt = $conn->prepare("SELECT user_id, password, is_seller FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_password, $is_seller);
        $stmt->fetch();

        //If statement will run through the password verification process, 
        //which checks if the password entered by the user matches 
        //the hashed password stored in the db. 
        //If the verification is successful, it starts a session and stores 
        //the user's ID, email, and seller status in session variables. 
        //It also sets a success message and redirects the user to the dashboard page. 
        //If the verification fails, it sets an error message indicating that the password is incorrect.
        if ($db_password !== null && password_verify($password, $db_password)) {
            //Start the session and rediret to the dashboard or homepage
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['is_seller'] = $is_seller;

            $_SESSION ['message'] = "Login successful";
            $_SESSION ['toastClass'] = "bg-success";

            header("Location: dashboard.php");
            exit();
        } else {
            $message = "Incorrect password";
            $toastClass = "bg-danger";
        } 
    } else {
        $message = "Email not found";
        $toastClass = "bg-warning";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <title>Login Page</title>
</head>
<body>
    <form action="" method="post" class="form">
     <div class="row">
        <h5 class="title">Login Into Your Account</h5>
     </div>
     <?php if ($message): ?>
        <div class="alert <?= $toastClass ?> text-white text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
     <div class="row_1">
        <label>
            <input type="text" name="email" required>
            <span>Email</span>
        </label>
    </div> 
     <div class="row_2">
        <label>
            <input type="password" name="password" required>
            <span>Password</span>
        </label>
    </div>
    <button type="submit" class="button">Login</button>
     
     <div class="create">
        <p><a href="./registration.php">Create Account</p>
     </div>
    </form>
    
</body>
</html>