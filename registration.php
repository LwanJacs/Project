<?php
include 'C:\xampp\htdocs\PHP\loginRegistrationSystem\database';
$message = "";
$toastClass = "";
if($_SERVER["REQUEST_METHOD"] =="POST"){
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    //Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM userdata WHERE email = ?");

    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();
    //If number of rows returned is greater than 0, then email exists.
    if($checkEmailStmt->num_rows > 0){
        $message = "Email ID already exists";
        $toastClass = "#007bff"; 
    } else {
        //Prepare and bind
        $stmt = $conn->prepare("INSERT INTO userdata (first_name, last_name, username, email, password)
        VALUES (?, ?, ?, ?, ?)");
        //Safely inserts data into the table with a prepared statement. 'sssss' means all the values are string.
        $stmt->bind_param("sssss", $fname, $lname, $username, $email, $hashedPassword);
        
        // Checking if account registered successfully
        if($stmt->execute()) {
            $message = "Account created successfully";
            $toastClass = "#28a745";
        } else {
            $message = "Error: " .$stmt->error;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Registration</title>
</head>
<body>
    
</body>
</html>
