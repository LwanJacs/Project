<?php
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database\db_connect.php';
$message = "";
$toastClass = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Prepare and execute
    $stmt = $conn->prepare("SELECT password FROM userdata WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0) {
        $stmt->bind_result($db_password);
        $stmt->fetch();

        if(password_verify($password, $db_password)){
            //Start the session and rediret to the dashboard or homepage
            session_start();
            $_SESSION['email'] = $email;

            $_SESSION['message'] = "Login successful";
            $_SESSION['toastClass'] = "bg-success";

            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['message'] = "Incorrect password";
            $_SESSION['toastClass'] = "bg-danger";
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