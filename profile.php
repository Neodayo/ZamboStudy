<?php
session_start();

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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT name, subject_best_at, subject_weak_at, facebook, profile_email FROM user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_best_at = $_POST['subject_best_at'];
    $subject_weak_at = $_POST['subject_weak_at'];
    $facebook = $_POST['facebook'];
    $email = $_POST['profile_email'];

    $update_sql = "UPDATE user SET subject_best_at = ?, subject_weak_at = ?, facebook = ?, profile_email = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $subject_best_at, $subject_weak_at, $facebook, $email, $user_id);

    if ($update_stmt->execute()) {
        // Refresh the page to display updated data
        header("Location: profile.php");
        exit;
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="CSS/profile.css">
    <style>
        
        .disabled {
            background-color: #f0f0f0;
            pointer-events: none;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .logout-btn {
            display: block;
            margin: 40px auto 0; /* Center and add spacing */
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            width: fit-content; /* Adjust button width */
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <a href="homepage.php" class="back-btn">⬅ Back to Homepage</a>

        <header class="header">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        </header>

        <div class="content-wrapper">
            <div class="user-section">
                <div class="avatar"></div>
                <div class="username"><?php echo htmlspecialchars($user['name']); ?></div>
            </div>

            <div class="form-section">
                <form method="POST">
                    <div class="input-group">
                        <label>Subject Best At</label>
                        <input type="text" name="subject_best_at" class="input-field" value="<?php echo htmlspecialchars($user['subject_best_at']); ?>" disabled>
                    </div>

                    <div class="input-group">
                        <label>Subject Weak At</label>
                        <input type="text" name="subject_weak_at" class="input-field" value="<?php echo htmlspecialchars($user['subject_weak_at']); ?>" disabled>
                    </div>

                    <div class="input-group">
                        <label>Facebook</label>
                        <input type="text" name="facebook" class="input-field" value="<?php echo htmlspecialchars($user['facebook']); ?>" disabled>
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="profile_email" class="input-field" value="<?php echo htmlspecialchars($user['profile_email']); ?>" disabled>
                    </div>

                    <button id="edit-save-btn" class="edit-btn" type="button">Edit</button>
                    <button id="save-btn" class="edit-btn" type="submit" style="display: none;">Save Data</button>
                </form>
            </div>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="logout.php">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <script>
        const editButton = document.getElementById('edit-save-btn');
        const saveButton = document.getElementById('save-btn');
        const inputFields = document.querySelectorAll('.input-field');

        // Toggle between edit and save
        editButton.addEventListener('click', () => {
            inputFields.forEach(input => input.disabled = false);
            editButton.style.display = 'none';
            saveButton.style.display = 'block';
        });
    </script>
</body>
</html>
