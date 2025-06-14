<?php
session_start();

// Check if the quantity data is posted
if (isset($_POST['quantity'])) {
    // Update the cart quantities
    foreach ($_POST['quantity'] as $itemId => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$itemId] = $quantity;
        }
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
} else {
    echo "No quantity data provided.";
}
?>
