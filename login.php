<?php
require 'db.php'; // Ensure this file connects to your database

// Start session to manage user login state
session_start();

$message = ""; // To store success or error messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // If credentials are correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name']; // Save user's name to personalize dashboard
        $message = '<div class="alert alert-success">Login successful! Redirecting...</div>';
        header("refresh:2;url=dashboard.php"); // Redirect to dashboard after 2 seconds
    } else {
        // Invalid email or password
        $message = '<div class="alert alert-danger">Invalid email or password.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bar Management System</title>
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
            <img src="img/logo.jpg" alt="Logo" height="80" width="80">
            <h2 class="text-white mb-4">Login</h2>

            <!-- Display success or error message -->
            <?php if (!empty($message)) echo $message; ?>

            <form action="login.php" method="POST" id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label text-white">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-white">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-custom btn-block w-100">Login</button>
            </form>
            <p class="text-center mt-3">
                Don't have an account? <a href="signup.php">Sign up here</a>.
            </p>
        </div>
    </div>
</body>
</html>