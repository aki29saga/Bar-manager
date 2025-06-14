<?php
require 'db.php'; // Database connection
session_start();

// Pagination setup
$itemsPerPage = 12; // Number of items per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Current page
$offset = ($page - 1) * $itemsPerPage;

// Fetch total items count for pagination
$totalItemsResult = $conn->query("SELECT COUNT(*) AS total FROM menu_items");
$totalItems = $totalItemsResult->fetch_assoc()['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch menu items for the current page
$menuItems = $conn->query("SELECT * FROM menu_items LIMIT $itemsPerPage OFFSET $offset");

// Get cart item count
$cartCount = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <style>
        .toast {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            min-width: 250px;
            z-index: 1055;
            display: none;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .toast.show {
            display: block;
        }
      
    </style>
</head>

<body class="bg-dark">
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.jpg" alt="Logo" height="46" width="46">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Cart (<span id="cartCount"><?= $cartCount; ?></span>)</a></li>
            </ul>
        </div>
        <!-- Logout Button -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="btn btn-danger ms-3">Logout</a>
        <?php endif; ?>
    </div>
</nav>
    <!-- Menu Section -->
    <div class="container mt-5">
        <h2 class="mb-4">Menu</h2>
        <div class="row">
            <?php
            if ($menuItems->num_rows > 0) {
                while ($item = $menuItems->fetch_assoc()) {
                    // Construct the image path using both the image name and extension
                    $imagePath = 'uploads/' . $item['image'];
                    $imageExtension = $item['image_extension'];
                    $fullImagePath = $imagePath; // Full image path with extension
                    
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card h-100 shadow-lg rounded'>
                            <img src='$fullImagePath' class='card-img-top rounded-top' alt='{$item['name']}' style='height: 200px; object-fit: cover;'>
                            <div class='card-body d-flex flex-column'>
                                <h5 class='card-title'>{$item['name']}</h5>
                                <p class='card-text text-muted'>Category: {$item['category']}</p>
                                <p class='card-text fw-bold'>Price: {$item['price']} USD</p>
                                <button class='btn btn-warning mt-auto' onclick='addToCart({$item['item_id']})'>Add to Cart</button>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>No items available in the menu.</p>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center mt-4">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2025 Bar Management System. All rights reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script>
        function addToCart(itemId) {
            fetch('cart_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'add_to_cart', item_id: itemId, quantity: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update cart count
                    const cartCount = document.getElementById('cartCount');
                    cartCount.innerText = data.cart_count;

                    // Show toast notification
                    const toast = document.getElementById('toast');
                    toast.innerText = data.message;
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 3000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
