<?php
session_start(); // Ensure session is started
include("../sinan/connect.php");

if(isset($_POST['signUp'])){
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkEmail = "SELECT * FROM logins WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        $insertQuery = "INSERT INTO logins (firstName, lastName, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
        if($conn->query($insertQuery) === TRUE){
            header("Location: ../sinan/homepage.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user is an admin
    if ($email === 'admin@123' && $password === 'pass') {
        $_SESSION['user_id'] = 1; // Assuming 1 is the admin user ID
        $_SESSION['is_admin'] = true;
        header("Location: ../admin/index.php");
        exit();
    } else {
        $sql = "SELECT * FROM logins WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['is_admin'] = false;
            header("Location: ../sinan/homepage.php");
            exit();
        } else {
            echo "Not Found, Incorrect Email or Password";
        }
    }
}
?>