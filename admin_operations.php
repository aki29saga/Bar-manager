<?php
// Connect to the database
require 'db.php';



// Check if admin is logged in
if (!isset($_SESSION['email'])) {
    header('Location: adminlog.php');
    exit();
}

// Fetch logged-in admin details
$admin_name = $_SESSION['name'];
$admin_email = $_SESSION['email'];

// Fetch all users and orders
$users = fetchAllUsers($conn);
$orders = fetchAllOrders($conn);

// Handle form submissions for updating user balance or order status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_balance'])) {
        // Update user balance
        $user_id = $_POST['user_id'];
        $new_balance = $_POST['new_balance'];
        if (updateUserBalance($conn, $user_id, $new_balance)) {
            $_SESSION['message'] = "Balance updated successfully!";
        } else {
            $_SESSION['message'] = "Failed to update balance.";
        }
    }

    if (isset($_POST['update_order_status'])) {
        // Update order status
        $order_id = $_POST['order_id'];
        $new_status = $_POST['new_status'];
        if (updateOrderStatus($conn, $order_id, $new_status)) {
            $_SESSION['message'] = "Order status updated successfully!";
        } else {
            $_SESSION['message'] = "Failed to update order status.";
        }
    }

    if (isset($_POST['delete_user'])) {
        // Delete user
        $user_id = $_POST['user_id'];
        if (deleteUser($conn, $user_id)) {
            $_SESSION['message'] = "User deleted successfully!";
        } else {
            $_SESSION['message'] = "Failed to delete user.";
        }
    }

    if (isset($_POST['delete_order'])) {
        // Delete order
        $order_id = $_POST['order_id'];
        if (deleteOrder($conn, $order_id)) {
            $_SESSION['message'] = "Order deleted successfully!";
        } else {
            $_SESSION['message'] = "Failed to delete order.";
        }
    }
}
// Fetch the sum of total_amount from the orders table for all users
$sql = "SELECT SUM(balance) AS total_balance2 FROM users";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_balance2 = $row['total_balance2']; // Get the sum of total_amount for all users
} else {
    $total_balance2 = 0; // Default to 0 if no records are found
}
// Fetch the sum of total_amount from the orders table for all users
$sql = "SELECT SUM(total_amount) AS total_balance FROM orders";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_balance = $row['total_balance']; // Get the sum of total_amount for all users
} else {
    $total_balance = 0; // Default to 0 if no records are found
}

// Fetch the sum of total_amount from the orders table for all users
$sql = "SELECT COUNT(name) AS total_i FROM menu_items";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_ = $row['total_i']; // Get the sum of total_amount for all users
} else {
    $total_balance = 0; // Default to 0 if no records are found
}

// Check if a delete request exists
if (isset($_GET['item_id'])) {
    $delete_id = $_GET['item_id'];
    $delete_sql = "DELETE FROM menu_items WHERE item_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch data from the database
$sql = "SELECT * FROM menu_items"; // Replace with your table name
$result = $conn->query($sql);
$menu_items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row; // Store records in an array
    }
}

// No closing of connection here



// Fetch logged-in admin details
$admin_name = $_SESSION['name'];
$admin_email = $_SESSION['email'];

// Fetch all users
function fetchAllUsers($conn) {
    $sql = "SELECT id, name, email, balance, created_at FROM users";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch all orders
function fetchAllOrders($conn) {
    $sql = "SELECT order_id, customer_email, total_amount, payment_method, status, table_number, order_details, created_at 
            FROM orders ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update user balance
function updateUserBalance($conn, $user_id, $new_balance) {
    $sql = "UPDATE users SET balance = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $new_balance, $user_id);
    return $stmt->execute();
}

// Update order status
function updateOrderStatus($conn, $order_id, $status) {
    $sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    return $stmt->execute();
}

// Delete a user
function deleteUser($conn, $user_id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

// Delete an order
function deleteOrder($conn, $order_id) {
    $sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    return $stmt->execute();
}
?>
