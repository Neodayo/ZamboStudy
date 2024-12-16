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

// Fetch tasks for this course
$task_sql = "SELECT * FROM task WHERE course_id = $course_id";
$task_result = $conn->query($task_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Student View</title>
    <link rel="stylesheet" href="CSS/course_page_student.css">

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
            <h1><?php echo htmlspecialchars($course_id['title']); ?></h1>
        </div>

        <div class="course-body">
            <h2>Tasks</h2>
            <ul class="task-list">
                <?php
                if ($task_result->num_rows > 0) {
                    while ($task = $task_result->fetch_assoc()) {
                        echo '<li class="task-item">';
                        echo '<strong>Task:</strong> ' . htmlspecialchars($task['task_name']) . '<br>';
                        echo '<strong>Due Date:</strong> ' . htmlspecialchars($task['due_date']) . '<br>';
                        echo '<a href="submit_task_student.php?task_id=' . htmlspecialchars($task['id']) . '" class="submit-task-button">Submit Task</a>';
                        echo '</li>';
                    }
                } else {
                    echo '<p class="no-tasks">No tasks available for this course.</p>';
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>