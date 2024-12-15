<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "zambostudy"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $role = $_POST['role'];
    $icon = $_POST['icon']; 
    $link = strtolower(str_replace(' ', '_', $title)) . ".html"; // Generate course link

    $sql = "INSERT INTO courses (title, role, icon, link) VALUES ('$title', '$role', '$icon', '$link')";
    if ($conn->query($sql) === TRUE) {
        echo "New course added successfully!";
        header("Location: homepage.php"); // Redirect to the homepage
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="CSS/homepage.css">
    <script>
        // JavaScript to auto-generate the course link in real-time
        function generateCourseLink() {
            const titleInput = document.getElementById('title');
            const linkDisplay = document.getElementById('generated-link');
            const linkValue = titleInput.value.toLowerCase().replace(/ /g, '_') + ".html";
            linkDisplay.textContent = "Generated Link: " + linkValue;
            document.getElementById('link').value = linkValue; // Set the hidden input value
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h1>Add a New Course</h1>
        <form method="POST" action="">
            <label for="title">Course Title:</label>
            <input type="text" id="title" name="title" oninput="generateCourseLink()" required>
            
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Tutor">Tutor</option>
                <option value="Student">Student</option>
            </select>
            
            <label for="icon">Icon (Optional):</label>
            <input type="file" id="icon" name="icon" placeholder="Enter icon name or path">
            
            <label for="link">Generated Link:</label>
            <p id="generated-link" style="font-weight: bold; color: green;">Generated Link: </p>
            <input type="hidden" id="link" name="link" required>
            
            <button type="submit">Add Course</button>
        </form>
    </div>
</body>
</html>
