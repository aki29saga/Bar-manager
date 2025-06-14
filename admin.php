<?php
require 'db.php'; // Database connection
session_start(); // Start session to access admin credentials
include 'admin_operations.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<style>

      table{
        ma
      }
          th, td {
            text-align: center;
            vertical-align: middle;
          }
          th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
          }
          tr:nth-child(even) {
            background-color: #f2f2f2;
          }
          tr:hover {
            background-color: #f1f1f1;
          }
          .image-preview {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            margin-top: 15px;
        }
</style>
<body class="bg-dark text-white">
  <div class="container py-4">
    <!-- Admin Welcome Message -->
    <h1 class="mb-4 text-warning">Welcome, <?php echo $admin_name; ?> </h1>

    <!-- Display Session Message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <?php
    if (isset($_SESSION['message'])) {
        // Check message type (success or danger)
        $messageType = $_SESSION['message_type'] ?? 'info'; // Default type is info
        echo "<div class='alert alert-$messageType'>" . $_SESSION['message'] . "</div>";

        // Clear message from session after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <div class="row">


<div class="col-md-7">
<div class="card p-1">
    <div class="card-body">
        <span class="badge badge-warning text-black">Stock Balance</span>
        <h1>&#8358;<?php echo number_format($total_balance, 2); ?></h1>
<!-- Order History Button -->
<button class="btn btn-warning btn-sm" href="logout.php" >
logout
</button>

</div>
</div>
</div>
<div class="col-md-5 mt-1">
<div class="row">
    <!-- Button to Trigger Modal -->
<section class="rounded bg-success col-7 shadowed" style="padding-top:20mm; margin-left:2mm;">
<span class="badge badge-warning">Users Balance</span>
  <h1>
  &#8358;<?php echo number_format($total_balance2, 2); ?>
  </h1>
</section>

<section class="rounded bg-danger  col-4 shadow" style="padding-top:22mm; margin-left:2mm;">
<span class="badge badge-warning">Total stock</span>
<h2>
<?php echo $total_; ?>
</h2>
</section>
</div>
</div>
</div>

<div class="btn-group btn-group-toggle col-12 mt-4" data-toggle="buttons">
  <!-- View Stock Button -->
  <label class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewStockModal">
    <h6>View Stock</h6>
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
      <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
    </svg>
  </label>
  
  <!-- Users Button -->
  <label class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#usersModal">
    <h6>Users</h6>
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
      <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
    </svg>
  </label>

  <!-- Generate Coupon Button -->
  <label class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#couponModal">
    <h6>Generate Coupon</h6>
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16">
      <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/>
      <path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/>
    </svg>
  </label>
</div>


<!-- Modal for View Stock -->
<div class="modal fade" id="viewStockModal" tabindex="-1" aria-labelledby="viewStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewStockModalLabel">View Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content for View Stock -->
        <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">All Stocks</button>
    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Add stock</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
  <div class="" >
    <div class="table-container">
   
      <div class="table-responsive">
      <h1 class="text-center">Menu Items</h1>
        <table class="table table-striped table-bordered">
            <thead>
                <tr >
                    <th class="text-dark">#</th>
                    <th class="text-dark">Name</th>
                    <th class="text-dark">Price</th>
                    <th class="text-dark">Category</th>
                    <th class="text-dark">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menu_items)): ?>
                    <?php $counter = 1; ?>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?= $counter++; ?></td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td><?= htmlspecialchars($item['price']); ?></td>
                            <td><?= htmlspecialchars($item['category']); ?></td>
                            <td>
                                <a href="?item_id=<?= $item['item_id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this record?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No records found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


      </div>
    </div>
  </div>

  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
  <h1 class="text-center">Add New Product</h1>
        <form action="item.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter product price" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control" id="category" name="category" placeholder="Enter product category" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)" required>
                <img id="preview" class="image-preview d-none" alt="Image Preview">
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>
  </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Users -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usersModalLabel">Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content for Users -->
        <p>Here you can manage your users.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Generate Coupon -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="couponModalLabel">Generate Coupon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Content for Generate Coupon -->
        <p>Here you can generate a new coupon for users.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    <!-- Users Table -->
    <h2 class="text-warning">Manage Users</h2>
    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Balance</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>₦<?php echo number_format($user['balance'], 2); ?></td>
                    <td>
                        <!-- Update Balance Form -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateBalanceModal" 
                                data-id="<?php echo $user['id']; ?>"
                                data-balance="<?php echo $user['balance']; ?>">Update Balance</button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" 
                                data-id="<?php echo $user['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Orders Table -->
    <h2 class="text-warning">Manage Orders</h2>
    <table class="table table-dark table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th><span class="badge badge-success">Table</span></th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $index => $order): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                    <td>₦<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['table_number']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <td>
                        <!-- Update Order Status Form -->
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#updateOrderStatusModal" 
                                data-id="<?php echo $order['order_id']; ?>"
                                data-status="<?php echo $order['status']; ?>">Update Status</button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOrderModal" 
                                data-id="<?php echo $order['order_id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>

  <!-- Modal for Updating Balance -->
  <div class="modal fade" id="updateBalanceModal" tabindex="-1" aria-labelledby="updateBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateBalanceModalLabel">Update User Balance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="admin.php">
          <div class="modal-body">
            <input type="hidden" name="user_id" id="user_id" value="">
            <div class="mb-3">
              <label for="new_balance" class="form-label">New Balance (₦)</label>
              <input type="number" class="form-control" name="new_balance" id="new_balance" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="update_balance" class="btn btn-warning">Update</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Deleting User -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="admin.php">
          <div class="modal-body">
            <input type="hidden" name="user_id" id="delete_user_id" value="">
            <p>Are you sure you want to delete this user?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Updating Order Status -->
  <div class="modal fade" id="updateOrderStatusModal" tabindex="-1" aria-labelledby="updateOrderStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateOrderStatusModalLabel">Update Order Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="admin.php">
          <div class="modal-body">
            <input type="hidden" name="order_id" id="order_id" value="">
            <div class="mb-3">
              <label for="new_status" class="form-label">New Status</label>
              <select class="form-control" name="new_status" id="new_status" required>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="update_order_status" class="btn btn-warning">Update Status</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Deleting Order -->
  <div class="modal fade" id="deleteOrderModal" tabindex="-1" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteOrderModalLabel">Delete Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="admin.php">
          <div class="modal-body">
            <input type="hidden" name="order_id" id="delete_order_id" value="">
            <p>Are you sure you want to delete this order?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" name="delete_order" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
          
          function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function() {
        preview.src = reader.result;
        preview.classList.remove('d-none');
    };
    
    if (file) {
        reader.readAsDataURL(file);
    }
}

    // Modal pre-fill logic
    var updateBalanceModal = document.getElementById('updateBalanceModal');
    updateBalanceModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var userId = button.getAttribute('data-id');
      var balance = button.getAttribute('data-balance');
      
      var userIdInput = updateBalanceModal.querySelector('#user_id');
      var balanceInput = updateBalanceModal.querySelector('#new_balance');
      
      userIdInput.value = userId;
      balanceInput.value = balance;
    });

    var deleteUserModal = document.getElementById('deleteUserModal');
    deleteUserModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var userId = button.getAttribute('data-id');
      
      var userIdInput = deleteUserModal.querySelector('#delete_user_id');
      userIdInput.value = userId;
    });

    var updateOrderStatusModal = document.getElementById('updateOrderStatusModal');
    updateOrderStatusModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var orderId = button.getAttribute('data-id');
      var currentStatus = button.getAttribute('data-status');
      
      var orderIdInput = updateOrderStatusModal.querySelector('#order_id');
      var statusInput = updateOrderStatusModal.querySelector('#new_status');
      
      orderIdInput.value = orderId;
      statusInput.value = currentStatus;
    });

    var deleteOrderModal = document.getElementById('deleteOrderModal');
    deleteOrderModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      var orderId = button.getAttribute('data-id');
      
      var orderIdInput = deleteOrderModal.querySelector('#delete_order_id');
      orderIdInput.value = orderId;
    });
    
  </script>
</body>
</html>
