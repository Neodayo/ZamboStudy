<?php
// Include database connection
include 'db_connect.php';

$message = ""; // To display success or error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Password = md5($_POST['Password']); // Hash the password
    $Course = $_POST['Course'];
    $Barangay = $_POST['Barangay'];


    // Check if the email already exists
    $check_email = "SELECT * FROM user WHERE Email = '$Email'";
    $result = $conn->query($check_email);
    
    if ($result->num_rows > 0) {
        $message = "This email is already registered.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO user (Name, Email, Password, Course, Barangay ) VALUES ('$Name', '$Email', '$Password', '$Course', '$Barangay')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Registration successful! You can now log in.";
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/signup.css">
    <title>Zambo Study - Sign Up</title>


</head>

<body>
    <div class="signup-container">
        <div class="title">
            ZamboStudy
        </div>

        <label>Name</label>
        <div class="input-group">
            <input type="text" name="Name" placeholder="Name " id="Name" require>
            
        </div>

        <label>Email</label>
        <div class="input-group">
            <input type="text" name="Email" placeholder="Email" id="Email" require>
            
        </div>

        <label>Password</label>
        <input type="text" name="Password" placeholder="Password" id="Password" class="Password" require>

        <label>What subject/s are you best in?</label>
        <input type="text" name="Course" placeholder="Course" id="Course"       require>

        <button class="submit-button">Create account</button>
    </div>
</body>

</html>
