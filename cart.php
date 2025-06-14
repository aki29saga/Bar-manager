<?php
session_start();
require 'db.php';

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['email']);

// Calculate total
$total = 0;
$cartItems = [];
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $itemId => $quantity) {
        $result = $conn->query("SELECT * FROM menu_items WHERE item_id = $itemId");
        $item = $result->fetch_assoc();
        $item['quantity'] = $quantity;
        $item['subtotal'] = $item['price'] * $quantity;
        $total += $item['subtotal'];
        $cartItems[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <style>
        body {
            background-color: black;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="img/logo.jpg" alt="Logo" height="46" width="46"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="text-white">Your Cart</h2>

    <?php if ($userLoggedIn) { ?>
        <p class="text-white">Logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    <?php } else { ?>
        <p class="text-white">You are not logged in. Please <a href="login.php" class="text-warning">log in</a> or <a href="signup.php" class="text-warning">create an account</a> to save your cart and proceed to checkout.</p>
    <?php } ?>

    <?php if (!empty($cartItems)) { ?>
        <form id="cart-form">
            <table class="table table-warning rounded">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) { ?>
                        <tr id="item-<?php echo $item['item_id']; ?>">
                            <td><?php echo $item['name']; ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <input 
                                    type="number" 
                                    name="quantity[<?php echo $item['item_id']; ?>]" 
                                    value="<?php echo $item['quantity']; ?>" 
                                    class="form-control quantity-input" 
                                    min="1">
                            </td>
                            <td class="subtotal">$<?php echo number_format($item['subtotal'], 2); ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-item" data-id="<?php echo $item['item_id']; ?>">Remove</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <h4 class="text-white">Total: <span id="total">$<?php echo number_format($total, 2); ?></span></h4>
            <button type="submit" class="btn btn-success">Update Cart</button>
            <?php if ($userLoggedIn) { ?>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            <?php } else { ?>
                <p class="text-warning mt-3">Log in or create an account to proceed to checkout.</p>
            <?php } ?>
        </form>
    <?php } else { ?>
        <p class="text-white">Your cart is empty. <a href="menu.php" class="text-warning">Shop now!</a></p>
    <?php } ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2025 Bar Management System. All rights reserved.</p>
</footer>

<script>
    $(document).ready(function () {
        // Unified AJAX Handler for Cart Actions
        function updateCart(action, itemId, quantity = null) {
            const data = { action: action, item_id: itemId };
            if (quantity !== null) data.quantity = quantity;

            $.ajax({
                url: 'cart_handler.php',
                type: 'POST',
                data: data,
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        if (action === 'update_quantity') {
                            $(`#item-${itemId} .subtotal`).text(`$${res.new_subtotal.toFixed(2)}`);
                            $('#total').text(`$${res.new_total.toFixed(2)}`);
                        } else {
                            location.reload(); // Reload to reflect changes
                        }
                    } else {
                        alert(res.message);
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        }

        // Remove from Cart
        $('.remove-item').on('click', function () {
            const itemId = $(this).data('id');
            updateCart('remove_from_cart', itemId);
        });

        // Update Quantity
        $('.quantity-input').on('change', function () {
            const itemId = $(this).attr('name').replace('quantity[', '').replace(']', '');
            const quantity = $(this).val();
            updateCart('update_quantity', itemId, quantity);
        });
    });
</script>
</body>
</html>
