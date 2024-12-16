<?php 
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "zambostudy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

session_start();

// Debugging: Check the incoming URL and course ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing course ID in URL.");
}
$course_id = intval($_GET['id']); // Safely retrieve the course ID
// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied: You must be logged in to view this page.");
}

// Fetch course details
$sql = "SELECT * FROM courses WHERE Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $course = $result->fetch_assoc();
} else {
    die("Course not found.");
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// Check if user is already enrolled in the course
$sql_check = "SELECT * FROM user_courses WHERE user_id = ? AND course_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $user_id, $course_id);
$stmt->execute();
$result_check = $stmt->get_result();

if ($result_check->num_rows > 0) {
    // User already enrolled
    $user_course = $result_check->fetch_assoc();
    $role = $user_course['role'];
} else {
    // Enroll the user in the course
    $role = $course['role']; // Default role for the course
    $sql_insert = "INSERT INTO user_courses (user_id, course_id, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iis", $user_id, $course_id, $role);
    $stmt->execute();
}

// Update user's global role (temporarily reflects current course)
$sql_update_user = "UPDATE user SET role = ? WHERE user_id = ?";
$stmt = $conn->prepare($sql_update_user);
$stmt->bind_param("si", $role, $user_id);
$stmt->execute();

$stmt->close();
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Course Page</title>
    <link rel="stylesheet" href="CSS/course_page.css">
    <style>
        .bottom-right-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .bottom-right-button:hover {
            background-color: #0056b3;
        }
    </style>
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
            <h1><?php echo htmlspecialchars($course['title']); ?> </h1>
            <p>Welcome to the <?php echo htmlspecialchars($course['title']); ?> course! Signed in as a <?php echo htmlspecialchars($role) ?></p> 
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

    <!-- Button to access Tutor View -->
    <form method="POST" action="course_viewTutor.php">
        <button class="bottom-right-button" onclick="return checkTutorAccess(event);">Tutor View</button>
    </form>

    <script>
        var userRole = '<?php echo $course_role; ?>'; // Dynamically set based on the user's role in this course

        function checkTutorAccess(event) {
            if (userRole === 'Tutor') {
                return true; // Allow access
            } else {
                event.preventDefault();
                var alertBox = document.createElement('div');
                alertBox.style.position = 'absolute';
                alertBox.style.top = '10px';
                alertBox.style.left = '50%';
                alertBox.style.transform = 'translateX(-50%)';
                alertBox.style.backgroundColor = 'red';
                alertBox.style.color = 'white';
                alertBox.style.padding = '10px';
                alertBox.style.borderRadius = '5px';
                alertBox.innerText = 'Access denied: Only tutors can access this page.';
                document.body.appendChild(alertBox);
                return false;
            }
        }
    </script>
</body>
</html>
