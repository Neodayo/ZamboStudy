<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your password
$dbname = "zambostudy"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses
$sql = "SELECT Id, title, role, icon, link FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZamboStudy</title>
    <link rel="stylesheet" href="CSS/homepage.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="images/ZamboStudyLogoClear.png" alt="ZamboStudy Logo" class="logo">
        <!-- Navigation Bar -->
        <div class="navbar">
            <a href="profile.php" class="nav-icon">👤 Profile</a>
            <a href="course_search_join.php" class="nav-icon">🔍 Search</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header Bar -->
        <div class="header-bar">Courses Overview</div>

        <!-- Cards Grid -->
        <div class="card-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="course_view.php?id=' . $row['Id'] . '" class="card">';
                    echo '<div class="icon">' . $row['icon'] . '</div>';
                    echo '<div class="details">';
                    echo '<div class="title">' . htmlspecialchars($row['title']) . '</div>';
                    echo '<div class="subtitle">' . htmlspecialchars($row['role']) . '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo "<p>No courses available.</p>";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Add Course Button -->
    <a href="add_course.php" class="add-course-button">➕ Add Course</a>

</body>

</html>
