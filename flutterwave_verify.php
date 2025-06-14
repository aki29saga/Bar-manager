<?php
session_start();
require 'db.php';

if (isset($_GET['status']) && $_GET['status'] === 'successful' && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];
    $tx_ref = $_SESSION['tx_ref'];
    $tableNumber = $_SESSION['table_number'];
    $orderDetails = $_SESSION['order_details'];
    $total = $_SESSION['total'];
    $customerEmail = isset($_SESSION['email']) ? $_SESSION['email'] : "unknown@example.com"; // Default email if not logged in

    // Verify payment with Flutterwave
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: FLWSECK_TEST-d2042c9dbf77144ac18f2b02980da131-X", // Replace with your Flutterwave secret key
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        die("cURL Error: " . $err);
    } else {
        $response_data = json_decode($response, true);
        if ($response_data['status'] === "success") {
            // Payment successful, save order to the database
            $orderDetailsSerialized = json_encode($orderDetails);
            $paymentMethod = "Flutterwave";
            $status = "Completed";

            $stmt = $conn->prepare("INSERT INTO orders (total_amount, payment_method, status, table_number, order_details, customer_email) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ississ", $total, $paymentMethod, $status, $tableNumber, $orderDetailsSerialized, $customerEmail);

            if ($stmt->execute()) {
                // Clear the cart and session data
                unset($_SESSION['cart'], $_SESSION['tx_ref'], $_SESSION['table_number'], $_SESSION['order_details'], $_SESSION['total']);
                echo "<script>alert('Payment successful! Order placed.'); window.location.href='menu.php';</script>";
            } else {
                echo "Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Payment verification failed: " . $response_data['message'];
        }
    }
} else {
    echo "Invalid payment callback.";
    exit;
}
?>
