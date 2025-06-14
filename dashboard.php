<?php
require 'db.php'; // Connect to database
session_start(); // Start session to access user credentials

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit();
}

// Fetch logged-in user's details
$user_name = $_SESSION['name'];
$user_email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];

// Fetch other profiles (if required)
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .bg-dark {
      background-color: #343a40 !important; /* Darker background */
      color: white; /* White text for better contrast */
    }
    .card {
      background-color: #212529; /* Darker card background */
      color: white;
    }
    .card-title {
      color: #ffc107; /* Gold accent color for titles */
    }
  </style>
</head>
<body class="bg-dark d-flex flex-column min-vh-100">
  <div class="container py-4">
    <!-- User Profile Image (Top Right) -->
    <div class="d-flex flex-row-reverse mb-4">
      <img src="img/user (1).png" class="bg-white rounded-circle border" alt="Profile Image" style="width: 40px; height: 40px;" data-bs-toggle="modal" data-bs-target="#profileModal" 
                      onclick="showDetails(
                        '<?= htmlspecialchars($row['name']) ?>',
                        '<?= htmlspecialchars($row['email']) ?>'
                      )">
    </div>

    <!-- User Profiles Section -->
    <h2 class="mb-4 text-warning">Welcome, <?php echo $user_name; ?> </h2>
    <?php


// Display success message if payment was successful
if (isset($_SESSION['payment_success'])) {
    echo "<div class='alert alert-success' role='alert'>";
    echo "<strong>Success!</strong> " . $_SESSION['payment_success'] . "<br>";
    echo "Transaction ID: " . htmlspecialchars($_SESSION['transaction_id']) . "<br>";
    echo "Amount Paid: " . htmlspecialchars($_SESSION['currency']) . " " . number_format($_SESSION['amount_paid'], 2);
    echo "</div>";

    // Clear the session variables after displaying the message
    unset($_SESSION['payment_success']);
    unset($_SESSION['transaction_id']);
    unset($_SESSION['amount_paid']);
    unset($_SESSION['currency']);
}

// Display error message if payment failed
if (isset($_SESSION['payment_error'])) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<strong>Error!</strong> " . $_SESSION['payment_error'];
    echo "</div>";

    // Clear the session variable after displaying the message
    unset($_SESSION['payment_error']);
}
// Fetch the user's balance from the database using their email
$sql = "SELECT balance FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_balance = $row['balance'];
} else {
    $user_balance = 0; // Default to 0 if no record is found
}
// Fetch user's order history using email in session
$orderHistory = [];
$sql = "SELECT order_id, total_amount, payment_method, status, table_number, order_details, created_at, customer_email FROM orders WHERE customer_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $orderHistory[] = $row;
}
?>

    <div class="row g-3">

      
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
        // Unset message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
    <?php endif; ?>
    <div class="col-md-7">
    <div class="card p-1">
        <div class="card-body">
            <span class="badge badge-warning">Balance</span>
            <h1>&#8358;<?php echo number_format($user_balance, 2); ?></h1>
<!-- Order History Button -->
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#orderHistoryModal">
    Order History
</button>

<!-- Order History Modal -->
<div class="modal fade" id="orderHistoryModal" tabindex="-1" aria-labelledby="orderHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="orderHistoryModalLabel">Order History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Price(&#8358;)</th>
                                <th>Payed from..</th>
                                <th>Status</th>
                                <th>Table No.</th>
                                <th>Items</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orderHistory)): ?>
                                <?php foreach ($orderHistory as $index => $order): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                                        <td><?php echo htmlspecialchars($order['table_number']); ?></td>
                                        <td><?php echo htmlspecialchars($order['order_details']); ?></td>
                                        
                                        <td><?php echo date("d M Y, h:i A", strtotime($order['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No order history found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        </div>
    </div>
</div>

     
      <div class="col-md-5">
        <div class="row">
<!-- Credit Modal -->
<div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-black" id="creditModalLabel">Credit Your Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="creditForm" method="POST" action="verify_payment.php">
        <div class="modal-body">
            <input type="text" class="form-control" id="userName" name="user_name" value="<?= htmlspecialchars($user_name) ?>" readonly hidden>
            <input type="email" class="form-control" id="userEmail" name="user_email" value="<?= htmlspecialchars($user_email) ?>" readonly hidden>
          
          <div class="mb-3">
            <label for="amount" class="form-label text-black">Amount (₦)</label>
            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount" required>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Proceed to Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Button to Trigger Modal -->
<section class="rounded bg-success col-7 shadowed" style="padding-top:17mm; margin-left:2mm;">
  <h4>
    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#creditModal">
      Credit <br> Account
    </button>
  </h4>
</section>

          <section class="rounded bg-danger  col-4 shadow" style="padding-top:22mm; margin-left:2mm;">
                <h4>Claim <br>coupon</h4>
          </section>
        </div>
      </div>
      <div class="col-md-12">
  <div class="card">
    <div id="eventCarousel" class="carousel slide position-relative" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner">
        <!-- Carousel Items -->
        <div class="carousel-item active">
          <img src="img/event2.jpg" class="d-block w-100" alt="Event 1">
          <div class="carousel-caption d-none d-md-block">
            <h5>Event 1</h5>
            <p>Description for Event 1.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="img/event2.jpg" class="d-block w-100" alt="Event 2">
          <div class="carousel-caption d-none d-md-block">
            <h5>Event 2</h5>
            <p>Description for Event 2.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="img/event2.jpg" class="d-block w-100" alt="Event 3">
          <div class="carousel-caption d-none d-md-block ">
            <h5>Event 3</h5>
            <p>Description for Event 3.</p>
          </div>
        </div>
      </div>

      <!-- Carousel Controls -->
      <button class="carousel-control-prev custom-control" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      
      <button class="carousel-control-next custom-control" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>

      <!-- Buy Ticket Button -->
      <button class="btn btn-warning btn-lg buy-ticket-button" type="button">
        Buy Ticket
      </button>
    </div>
  </div>
</div>

<style>
  /* Custom Styles for Slide Buttons */
  .custom-control {
    position: absolute;
    bottom: 15px;
    left: 15px;
    width: 40px;
    height: 40px;
    background-color: #ffc107; /* Yellow background */
    border-radius: 50%; /* Rounded shape */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
  }

  .custom-control-prev {
    margin-right: 10px; /* Spacing between prev and next buttons */
  }

  .custom-control span {
    display: inline-block;
    width: 16px; /* Adjust size of arrow */
    height: 16px;
  }

  /* Custom Styles for Buy Ticket Button */
  .buy-ticket-button {
    position: absolute;
    bottom: 15px;
    right: 15px;
    z-index: 1;
    font-size: 14px;
    padding: 6px 12px;
  }
</style>

    </div>
  </div>

  <!-- Footer -->
  <footer class="mt-auto py-3 text-center bg-dark text-muted">
    <p>&copy; <?= date('Y') ?> Bar Management System</p>
  </footer>

  <!-- Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profileModalLabel">Profile Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <img id="modalImage" src="img/user (1).png" alt="Profile Image" class="rounded-circle d-block mx-auto mb-3" style="width: 100px; height: 100px;">
          <h5 id="modalName" class="text-center"></h5>
          <p><strong>Email:</strong> <span id="modalEmail"></span></p>
          <div class="text-end">
            <a href="logout.php" class="btn btn-warning">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function showDetails(name, email) {
      document.getElementById('modalName').textContent = name;
      document.getElementById('modalEmail').textContent = email;
    }
  </script>
</body>
</html>
