<?php
session_start();
require 'db.php';

// Handle POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $itemId = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity'] ?? 1);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    switch ($action) {
        case 'add_to_cart':
            // Add item to cart or update quantity
            $_SESSION['cart'][$itemId] = ($_SESSION['cart'][$itemId] ?? 0) + $quantity;
            echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
            break;

        case 'remove_from_cart':
            // Remove item from cart
            if (isset($_SESSION['cart'][$itemId])) {
                unset($_SESSION['cart'][$itemId]);
                echo json_encode(['status' => 'success', 'message' => 'Item removed from cart']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Item not found in cart']);
            }
            break;

        case 'update_quantity':
            // Update item quantity in cart
            if ($quantity > 0) {
                $_SESSION['cart'][$itemId] = $quantity;

                // Calculate new totals
                $newSubtotal = 0;
                $newTotal = 0;

                $result = $conn->query("SELECT price FROM menu_items WHERE item_id = $itemId");
                if ($result->num_rows > 0) {
                    $item = $result->fetch_assoc();
                    $newSubtotal = $item['price'] * $quantity;

                    foreach ($_SESSION['cart'] as $id => $qty) {
                        $res = $conn->query("SELECT price FROM menu_items WHERE item_id = $id");
                        $it = $res->fetch_assoc();
                        $newTotal += $it['price'] * $qty;
                    }
                }

                echo json_encode(['status' => 'success', 'new_subtotal' => $newSubtotal, 'new_total' => $newTotal]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid quantity']);
            }
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
