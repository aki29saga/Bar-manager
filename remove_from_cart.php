<?php
session_start();

// Check if the item_id is provided
if (isset($_POST['item_id'])) {
    $itemId = $_POST['item_id'];

    // Remove the item from the cart session
    if (isset($_SESSION['cart'][$itemId])) {
        unset($_SESSION['cart'][$itemId]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Item not found in cart']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid item ID']);
}
?>
