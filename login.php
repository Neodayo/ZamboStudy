<?php
// Start session
session_start();

// Include database connection
include 'db_connect.php';

$message = ""; // To display login error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Email = $_POST['Email'];
    $Password = md5($_POST['Password']); // Password hashed with MD5  

    // Check user in the database
    $sql = "SELECT * FROM user WHERE Email = '$Email' AND Password = '$Password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['Name'] = $user['Name'];

    
        //just for debugging
        echo "Session Debug: <br>";
        echo "Id: " . $_SESSION['user_id'] . "<br>";
        echo "First Name: " . $_SESSION['Name'] . "<br>";
     
        header("Location: homepage.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/login.css">    
    <title>ZamboStudy Login</title>
 
</head>

<body>
    <div class="login-container">
        <div class="logo">
            ZAMBO<br>
            <!-- <img src="images/ZamboStudyLogoClear.png" alt="ZamboStudy Logo" class="logo"> -->
        </div>

        <form method="POST" action="">
        <input type="text" name="Email" placeholder="Email" id="Email">
        <input type="password" name="Password" placeholder="Password" id="Password">
        <p>Don't have an account? <a href="signup.php   ">Signup here</a>.</p>
        <button type="submit" class="login-button">Log In</button>
        
        
        
    </div>
</body>
</html>
