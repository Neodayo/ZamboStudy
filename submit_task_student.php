<?php
$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "zambostudy");

    $student_name = $_POST['student_name'];
    $submission_file = $_FILES['submission_file']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($submission_file);

    if (move_uploaded_file($_FILES['submission_file']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO submissions (task_id, student_name, submission_file) VALUES ('$task_id', '$student_name', '$target_file')";
        if ($conn->query($sql)) {
            echo "Submission successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload file.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/course_page_student.css">

    <title>Submit Task</title>
</head>
<body>
    <h1>Submit Your Task</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="student_name">Your Name:</label>
        <input type="text" name="student_name" required><br><br>

        <label for="submission_file">Upload File:</label>
        <input type="file" name="submission_file" required><br><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>
