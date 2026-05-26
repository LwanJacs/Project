<?php
include 'database\db_connect.php';
$message = "";
$toastClass = "";

if($_SERVER["REQUEST_METHOD"] =="POST"){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the password strength
    if (strlen($password) < 6 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $message = "Password must be at least 6 characters long and include at least one uppercase letter, and one number.";
        $toastClass = "bg-danger"; 
        
    } else {
        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        //Check if email already exists
        $checkEmailStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
        $checkEmailStmt->bind_param("ss", $email, $username);
        $checkEmailStmt->execute();
        $checkEmailStmt->store_result();
        //If number of rows returned is greater than 0, then email exists.
        if($checkEmailStmt->num_rows > 0){
            $message = "Username or Email ID already exists";
            $toastClass = "bg-info"; 
        } else {
            //Prepare and bind
            $stmt = $conn->prepare("INSERT INTO users (name, surname, username, email, password)
            VALUES (?, ?, ?, ?, ?)");
            //Safely inserts data into the table with a prepared statement. 'sssss' means all the values are string.
            $stmt->bind_param("sssss", $name, $surname, $username, $email, $hashedPassword);
        
            // Checking if account registered successfully
            if($stmt->execute()) {
                $_SESSION['message'] = "Account created successfully! Please login,";
                $_SESSION['toastClass'] = "bg-success";

                header("Location: login.php");
                exit();
            } else {
                $message = "Error in creating account.". $stmt->error;
                $toastClass = "bg-danger";
            }
            
            $stmt->close();
        }

        $checkEmailStmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="register_style.css">
    <title>Registration</title>
</head>
<body>
    <?php if (!empty($message)): ?>
        <div class="toast show <?php echo $toastClass; ?>">
            <span><?= htmlspecialchars($message) ?></span> <!-- inserts message-->
        </div>
    <?php endif; ?>
    <form method="post" class="form">

    <a href="index.php" 
    class="btn-close btn-close-white custom-close-btn close-form-btn"
    aria-label="Close"></a>

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
        <label class="password-box">
            <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[A-Z]).{6,}" title="Must contain at least one number, one uppercase letter, and at least 6 or more characters" required>
            <span>Password</span>
            <i class="fa fa-eye toggle-password" id="togglePassword"></i> 
        </label>

        <button type="submit" class="submit">Create Account</button>
        <p class="signin">
                Already have an account?
                <a href="./login.php">Signin</a>
        </p>
    </form>
<script src="password_toggle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
