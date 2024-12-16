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

// Handle form submissions for announcements, tasks, materials, and events
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update announcement
    if (isset($_POST['update_announcement'])) {
        $new_announcement = $conn->real_escape_string($_POST['announcement']);
        $update_sql = "UPDATE courses SET announcement = '$new_announcement' WHERE id = $course_id";
        if ($conn->query($update_sql) === TRUE) {
            echo "<script>alert('Announcement updated successfully.'); window.location.href = '?id=$course_id';</script>";
        } else {
            echo "<script>alert('Error updating announcement: " . $conn->error . "');</script>";
        }
    }

    // Add task
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

    // Add material
    if (isset($_POST['add_material'])) {
        if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['material_file']['tmp_name'];
            $file_name = basename($_FILES['material_file']['name']);
            $target_dir = "uploads/materials/";
            $target_file = $target_dir . $file_name;

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($file_tmp, $target_file)) {
                $insert_material_sql = "INSERT INTO materials (course_id, file_name, file_path) VALUES ($course_id, '$file_name', '$target_file')";
                if ($conn->query($insert_material_sql) === TRUE) {
                    echo "<script>alert('Material uploaded successfully.'); window.location.href = '?id=$course_id';</script>";
                } else {
                    echo "<script>alert('Error saving material: " . $conn->error . "');</script>";
                }
            } else {
                echo "<script>alert('Error uploading file.');</script>";
            }
        } else {
            echo "<script>alert('No file selected or an error occurred.');</script>";
        }
    }

    // Add event
    if (isset($_POST['add_event'])) {
        $event_name = $conn->real_escape_string($_POST['event_name']);
        $event_date = $conn->real_escape_string($_POST['event_date']);
        $insert_event_sql = "INSERT INTO events (course_id, event_name, event_date) VALUES ($course_id, '$event_name', '$event_date')";
        if ($conn->query($insert_event_sql) === TRUE) {
            echo "<script>alert('Event added successfully.'); window.location.href = '?id=$course_id';</script>";
        } else {
            echo "<script>alert('Error adding event: " . $conn->error . "');</script>";
        }
    }
}

// Fetch tasks, materials, and events for this course
$tasks_sql = "SELECT * FROM tasks WHERE course_id = $course_id ORDER BY due_date ASC";
$tasks_result = $conn->query($tasks_sql);

$materials_sql = "SELECT * FROM materials WHERE course_id = $course_id ORDER BY id ASC";
$materials_result = $conn->query($materials_sql);

$events_sql = "SELECT * FROM events WHERE course_id = $course_id ORDER BY event_date ASC";
$events_result = $conn->query($events_sql);

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
            <!-- Announcements Section -->
            <div class="course-announcements">
                <h2>Announcements</h2>
                <form method="POST">
                    <textarea name="announcement" rows="5" cols="50"><?php echo htmlspecialchars($course['announcement']); ?></textarea><br>
                    <button type="submit" name="update_announcement">Update Announcement</button>
                </form>
            </div>

            <!-- Materials Section (Add it here) -->
            <div class="course-materials">
                <h2>Course Materials</h2>
                <ul>
                    <?php while ($material = $materials_result->fetch_assoc()) { ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank">
                                <?php echo htmlspecialchars($material['file_name']); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>

                <h3>Add a New Material</h3>
                <form method="POST" enctype="multipart/form-data">
                    <label for="material_file">Upload File:</label><br>
                    <input type="file" name="material_file" required><br>
                    <button type="submit" name="add_material">Upload Material</button>
                </form>
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

            <div class="course-events">
                <h2>Upcoming Events</h2>
                <ul>
                    <?php while ($event = $events_result->fetch_assoc()) { ?>
                        <li>
                            <strong><?php echo htmlspecialchars($event['event_name']); ?>:</strong> 
                            On <?php echo htmlspecialchars($event['event_date']); ?>
                        </li>
                    <?php } ?>
                </ul>

                <h3>Add a New Event</h3>
                <form method="POST">
                    <label for="event_name">Event Name:</label><br>
                    <input type="text" name="event_name" required><br>
                    <label for="event_date">Event Date:</label><br>
                    <input type="date" name="event_date" required><br>
                    <button type="submit" name="add_event">Add Event</button>
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

