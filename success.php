<?php
require 'db.php';
session_start();

// Check if the transaction ID exists in the query string
if (!isset($_GET['transaction_id'])) {
    echo "Invalid request. Transaction ID is missing.";
    exit();
}

$transaction_id = $_GET['transaction_id'];

// Flutterwave API details
$api_key = "FLWSECK_TEST-d2042c9dbf77144ac18f2b02980da131-X";
$verify_url = "https://api.flutterwave.com/v3/transactions/$transaction_id/verify";

// Verify transaction using Flutterwave API
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $verify_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo "cURL Error: " . $err;
    exit();
}

// Decode the response
$response_data = json_decode($response, true);

if ($response_data['status'] === 'success') {
    // Payment verification successful
    $amount_paid = $response_data['data']['amount'];
    $currency = $response_data['data']['currency'];
    $status = $response_data['data']['status'];
    $user_email = $response_data['data']['customer']['email'];

    // Check if the transaction is already recorded (prevent duplication)
    $sql_check = "SELECT * FROM transactions WHERE transaction_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $transaction_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Redirect with success message
        $_SESSION['payment_success'] = "Transaction already processed.";
        header("Location: dashboard.php");
        exit();
    }

    // Insert the transaction record into the database
    $sql_insert = "INSERT INTO transactions (transaction_id, user_email, amount, currency, status) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssdss", $transaction_id, $user_email, $amount_paid, $currency, $status);

    if ($stmt_insert->execute()) {
        // Update the user's account balance
        $sql_update = "UPDATE users SET balance = balance + ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ds", $amount_paid, $user_email);
        $stmt_update->execute();

        // Set session variables for success message
        $_SESSION['payment_success'] = "Payment successful!";
        $_SESSION['transaction_id'] = $transaction_id;
        $_SESSION['amount_paid'] = $amount_paid;
        $_SESSION['currency'] = $currency;

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Failed to record the transaction. Please contact support.";
    }
} else {
    // Payment verification failed
    $_SESSION['payment_error'] = "Payment failed: " . $response_data['message'];
    header("Location: dashboard.php");
    exit();
}
?>
