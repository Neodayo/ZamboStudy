<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Desktop</title>
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
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Me</h1>
        </header>

        <div class="content-wrapper">
            <div class="user-section">
                <div class="avatar"></div>
                <div class="username">Name of User</div>
            </div>

            <div class="form-section">
                <div class="input-group">
                    <label>Subject best at</label>
                    <input type="text" class="input-field" disabled>
                </div>

                <div class="input-group">
                    <label>Subject weak at</label>
                    <input type="text" class="input-field" disabled>
                </div>

                <div class="input-group">
                    <label>Social</label>
                    <div class="social-section">
                        <input type="text" class="input-field" placeholder="Facebook" disabled>
                        <input type="text" class="input-field" placeholder="Email" disabled>
                    </div>
                </div>

                <button id="edit-save-btn" class="edit-btn">Edit</button>
            </div>
        </div>

        <!-- Back Button -->
        <a href="homepage.php" class="back-btn">Back to Homepage</a>
    </div>

    <script>
        const editSaveButton = document.getElementById('edit-save-btn');
        const inputFields = document.querySelectorAll('.input-field');

        // Toggle edit/save functionality
        editSaveButton.addEventListener('click', () => {
            const isEditing = editSaveButton.textContent === 'Edit';

            // Toggle input field enable/disable
            inputFields.forEach(input => {
                input.disabled = !isEditing;
                if (!isEditing) {
                    input.classList.add('disabled');
                } else {
                    input.classList.remove('disabled');
                }
            });

            // Update button text
            editSaveButton.textContent = isEditing ? 'Save Data' : 'Edit';
        });
    </script>
</body>
</html>
