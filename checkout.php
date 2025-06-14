<?php
session_start();
require 'db.php';

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['email']);

// Fetch user balance
$userBalance = 0;
if ($userLoggedIn) {
    $email = $_SESSION['email'];
    $result = $conn->query("SELECT balance FROM users WHERE email = '$email'");
    if ($result && $row = $result->fetch_assoc()) {
        $userBalance = $row['balance'];
    }
}

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
} else {
    $cartItems = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart & Checkout</title>
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

    <?php if (!empty($cartItems)) { ?>
        <form id="cart-form" action="update_cart.php" method="POST">
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
                        <tr>
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
        </form>

        <div class="checkout-section mt-5">
            <h2 class="text-white">Checkout</h2>
            <h4 class="text-white">Total Amount: $<?php echo number_format($total, 2); ?></h4>

            <form method="POST" action="process_payment.php">
                <div class="mb-3">
                    <label for="tableNumber" class="form-label text-white">Enter Table Number:</label>
                    <input type="text" id="tableNumber" name="table_number" class="form-control" required>
                </div>

                <!-- Option to select payment method -->
                <div class="mb-3">
                    <label for="paymentMethod" class="form-label text-white">Select Payment Method:</label>
                    <select class="form-select" id="paymentMethod" name="payment_method" required>
                        <option value="balance">Pay from Balance ($<?php echo number_format($userBalance, 2); ?>)</option>
                        <option value="flutterwave">Pay with Flutterwave</option>
                    </select>
                </div>

                <input type="hidden" name="total" value="<?php echo $total; ?>">
                <button type="submit" class="btn btn-primary">Proceed to Pay</button>
            </form>
        </div>
    <?php } else { ?>
        <p class="text-white">Your cart is empty. <a href="menu.php" class="text-warning">Shop now!</a></p>
    <?php } ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2025 Bar Management System. All rights reserved.</p>
</footer>

<script>
    $(document).ready(function () {
        // Update live total and subtotals
        $('.quantity-input').on('input', function () {
            let total = 0;
            $(this).closest('tr').each(function () {
                const price = parseFloat($(this).find('td:nth-child(2)').text().substring(1));
                const quantity = parseInt($(this).find('.quantity-input').val());
                const subtotal = price * quantity;
                $(this).find('.subtotal').text(`$${subtotal.toFixed(2)}`);
            });

            $('.subtotal').each(function () {
                total += parseFloat($(this).text().substring(1));
            });

            $('#total').text(`$${total.toFixed(2)}`);
        });

        // Remove item from cart
        $('.remove-item').on('click', function () {
            const itemId = $(this).data('id');
            $.ajax({
                url: 'remove_from_cart.php',
                type: 'POST',
                data: { item_id: itemId },
                success: function (response) {
                    const res = JSON.parse(response);
                    if (res.status === 'success') {
                        location.reload(); // Reload the page to update the cart
                    } else {
                        alert('Failed to remove the item. Please try again.');
                    }
                },
                error: function () {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
</body>
</html>
