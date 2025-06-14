<?php
require 'db.php'; // Include database connection

// Initialize variables for error/success messages
$emailError = $passwordError = $successMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate passwords match
    if ($password !== $confirm_password) {
        $passwordError = "Passwords do not match.";
    } else {
        // Check if email exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $emailError = "This email is already registered.";
        } else {
            $stmt->close();

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $successMessage = "User registered successfully!";
            } else {
                $successMessage = "Failed to register user.";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Bar Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #343a40; /* Matches your dark background */
            color: white;
        }

        .card {
            background-color: #212529; /* Darker background for card */
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            background-color: #ffc107; /* Gold accent color */
            color: black;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #e0a800; /* Darker gold on hover */
        }

        a {
            color: #ffc107; /* Gold for links */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>
<body>
    <!-- Home Button -->
    <a href="index.php" class="btn btn-custom home-button">Home</a>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <img src="img/logo.jpg" alt="" height="80" width="80">
            <h2 class="text-white mb-4">Sign Up</h2>
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <form action="signup.php" method="POST" id="signupForm">
                <div class="mb-3">
                    <label for="name" class="form-label text-white">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-white">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    <small class="text-danger"><?php echo $emailError; ?></small>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-white">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm-password" class="form-label text-white">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    <small class="text-danger"><?php echo $passwordError; ?></small>
                </div>
                <button type="submit" class="btn btn-custom btn-block w-100">Sign Up</button>
            </form>
            <p class="text-center mt-3">
                Already have an account? <a href="login.php">Login here</a>.
            </p>
        </div>
    </div>

    <script>
        // Add JavaScript validation for immediate feedback
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Reset error messages
            let emailError = '';
            let passwordError = '';

            // Check if passwords match
            if (password !== confirmPassword) {
                passwordError = 'Passwords do not match.';
            }

            // Display errors
            if (emailError || passwordError) {
                event.preventDefault(); // Prevent form submission
                document.querySelector('small.text-danger').textContent = passwordError;
            }
        });
    </script>
</body>
</html>
