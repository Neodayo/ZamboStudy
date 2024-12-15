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

// Handle search query
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$courses = [];
if ($search_query !== '') {
    $sql = "SELECT * FROM courses WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_term = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    $stmt->close();
}

// Handle course join request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);
    $user_id = 1; // Replace with the logged-in user's ID

    // Check if user already joined
    $check_sql = "SELECT * FROM course_memberships WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $course_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows === 0) {
        // Add user to course
        $join_sql = "INSERT INTO course_memberships (user_id, course_id) VALUES (?, ?)";
        $stmt = $conn->prepare($join_sql);
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            echo "<p>Successfully joined the course!</p>";
        } else {
            echo "<p>Failed to join the course. Please try again later.</p>";
        }
    } else {
        echo "<p>You are already a member of this course.</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search and Join Courses</title>
    <link rel="stylesheet" href="CSS/course_search.css">
</head>
<body>
    <div class="container">
        <h1>Search for Courses</h1>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Enter course name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($courses)): ?>
            <h2>Search Results</h2>
            <ul class="course-list">
                <?php foreach ($courses as $course): ?>
                    <li class="course-item">
                        <strong><?php echo htmlspecialchars($course['title']); ?></strong><br>
                        <em><?php echo htmlspecialchars($course['description']); ?></em><br>
                        <form method="POST" action="">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit">Join Course</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($search_query !== ''): ?>
            <p>No courses found for "<?php echo htmlspecialchars($search_query); ?>".</p>
        <?php endif; ?>
    </div>
</body>
</html>
