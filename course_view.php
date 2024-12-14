<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "zambostudy"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course details based on passed ID
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM courses WHERE id = $course_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    echo "Course not found.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Page</title>
    <link rel="stylesheet" href="CSS/course_page.css">
</head>
<body>
    <div class="sidebar">
        <img src="images/ZamboStudyLogoClear.png" alt="ZamboStudy Logo" class="logo">
        <div class="navbar">
            <a href="homepage.php" class="nav-icon">🏠 Home</a>
            <a href="profile.html" class="nav-icon">👤 Profile</a>
        </div>
    </div>

    <div class="main-content">
        <div class="course-header">
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p>Role: <?php echo htmlspecialchars($course['role']); ?></p>
        </div>

        <div class="course-body">
            <div class="course-announcements">
                <h2>Announcements</h2>
                <p>Welcome to the <?php echo htmlspecialchars($course['title']); ?> course!</p>
            </div>

            <div class="course-materials">
                <h2>Course Materials</h2>
                <ul>
                    <li><a href="#">Material 1: Introduction</a></li>
                    <li><a href="#">Material 2: Syllabus</a></li>
                    <li><a href="#">Material 3: Assignments</a></li>
                </ul>
            </div>

            <div class="course-tasks">
                <h2>Upcoming Tasks</h2>
                <ul>
                    <li><strong>Quiz 1:</strong> Due on 2024-12-20</li>
                    <li><strong>Project Proposal:</strong> Due on 2024-12-25</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
