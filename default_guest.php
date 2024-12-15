<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the course search page if logged in
    header("Location: course_search_join.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to ZamboStudy</title>
    <link rel="stylesheet" href="CSS/default_guest.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Welcome to ZamboStudy</h1>
        </header>

        <div class="content">
            <p>To explore courses and start learning, please log in or sign up.</p>

            <div class="button-group">
                <a href="login.php" class="btn">Log In</a>
                <a href="signup.php" class="btn">Sign Up</a>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; 2024 ZamboStudy. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
