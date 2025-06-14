<?php
require 'db.php';
session_start(); // Start session to store success message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);
    $image = $_FILES['image'];

    // Validate the image upload
    if ($image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded images
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
        }

        // Get file information (name and extension)
        $imageInfo = pathinfo($image['name']);
        $imageName = basename($image['name']); // Keep the original name (with extension)
        $imageExtension = $imageInfo['extension']; // Get the image extension

        // Set the target file path with the original image name
        $targetFilePath = $uploadDir . $imageName;

        if (move_uploaded_file($image['tmp_name'], $targetFilePath)) {
            // Insert the product into the database
            $sql = "INSERT INTO menu_items (name, price, category, image, image_extension) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sisss", $name, $price, $category, $imageName, $imageExtension); // Store the original image name and extension

            if ($stmt->execute()) {
                // Set success message in session
                $_SESSION['message'] = 'Product added successfully!';
                $_SESSION['message_type'] = 'success'; // Optional: You can use this to style the message

                // Redirect to admin page
                header("Location: admin.php");
                exit();
            } else {
                // Set error message in session
                $_SESSION['message'] = 'Failed to add product. Please try again.';
                $_SESSION['message_type'] = 'danger'; // Optional: You can use this to style the message

                // Redirect to admin page
                header("Location: admin.php");
                exit();
            }

            $stmt->close();
        } else {
            $_SESSION['message'] = 'Failed to upload the image.';
            $_SESSION['message_type'] = 'danger';
            header("Location: admin.php");
            exit();
        }
    } else {
        $_SESSION['message'] = 'Invalid image upload.';
        $_SESSION['message_type'] = 'danger';
        header("Location: admin.php");
        exit();
    }
}
?>
