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

session_start();

// Check if user is logged in and is a tutor
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'tutor') {
    die("Access denied: You do not have permission to view this page.");
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

// Handle form submissions for announcements, tasks, and course deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_announcement'])) {
        $new_announcement = $conn->real_escape_string($_POST['announcement']);
        $update_sql = "UPDATE courses SET announcement = '$new_announcement' WHERE id = $course_id";
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('Announcement updated successfully.'); window.location.href = '?id=$course_id';</script>";
        } else {
            echo "<script>alert('Error updating announcement: " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['add_task'])) {
        $task_name = $conn->real_escape_string($_POST['task_name']);
        $due_date = $conn->real_escape_string($_POST['due_date']);
        $insert_task_sql = "INSERT INTO tasks (course_id, task_name, due_date) VALUES ($course_id, '$task_name', '$due_date')";
        if ($conn->query($insert_task_sql) === TRUE) {
            echo "<script>alert('Task added successfully.'); window.location.href = '?id=$course_id';</script>";
        } else {
            echo "<script>alert('Error adding task: " . $conn->error . "');</script>";
        }
    }

    if (isset($_POST['delete_course'])) {
        $delete_course_sql = "DELETE FROM courses WHERE id = $course_id";
        $delete_tasks_sql = "DELETE FROM tasks WHERE course_id = $course_id";
        if ($conn->query($delete_tasks_sql) === TRUE && $conn->query($delete_course_sql) === TRUE) {
            echo "<script>alert('Course deleted successfully.'); window.location.href = 'homepage.php';</script>";
        } else {
            echo "<script>alert('Error deleting course: " . $conn->error . "');</script>";
        }
    }
}

// Fetch tasks for this course
$tasks_sql = "SELECT * FROM tasks WHERE course_id = $course_id ORDER BY due_date ASC";
$tasks_result = $conn->query($tasks_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Tutor Course Page</title>
    <link rel="stylesheet" href="CSS/course_page.css">
</head>
<body>
    <div class="sidebar">
        <img src="images/ZamboStudyLogoClear.png" alt="ZamboStudy Logo" class="logo">
        <div class="navbar">
            <a href="homepage.php" class="nav-icon">🏠 Home</a>
            <a href="profile.php" class="nav-icon">👤 Profile</a>
        </div>
    </div>

    <div class="main-content">
        <div class="course-header">
            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            <p>Role: Tutor</p>
        </div>

        <div class="course-body">
            <div class="course-announcements">
                <h2>Announcements</h2>
                <form method="POST">
                    <textarea name="announcement" rows="5" cols="50"><?php echo htmlspecialchars($course['announcement']); ?></textarea><br>
                    <button type="submit" name="update_announcement">Update Announcement</button>
                </form>
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
                    <?php while ($task = $tasks_result->fetch_assoc()) { ?>
                        <li><strong><?php echo htmlspecialchars($task['task_name']); ?>:</strong> Due on <?php echo htmlspecialchars($task['due_date']); ?></li>
                    <?php } ?>
                </ul>

                <h3>Add a New Task</h3>
                <form method="POST">
                    <label for="task_name">Task Name:</label><br>
                    <input type="text" name="task_name" required><br>
                    <label for="due_date">Due Date:</label><br>
                    <input type="date" name="due_date" required><br>
                    <button type="submit" name="add_task">Add Task</button>
                </form>
            </div>

            <div class="delete-course">
                <h2>Delete Course</h2>
                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                    <button type="submit" name="delete_course" class="delete-button">Delete Course</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
