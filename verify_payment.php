<?php
require 'db.php';
session_start();

// Validate form inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $amount = $_POST['amount'];
   

    // Flutterwave API details
    $flutterwave_url = "https://api.flutterwave.com/v3/payments";
    $api_key = "FLWSECK_TEST-d2042c9dbf77144ac18f2b02980da131-X";

    // Payment data
    $payment_data = [
        'tx_ref' => uniqid(),
        'amount' => $amount,
        'currency' => 'NGN',
        'redirect_url' => 'http://localhost/elmore/success.php',
        'customer' => [
            'email' => $user_email,
            'name' => $user_name,
        ],
    ];

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $flutterwave_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($payment_data),
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

    // Decode response
    $response_data = json_decode($response, true);
    if (isset($response_data['status']) && $response_data['status'] === 'success') {
        header('Location: ' . $response_data['data']['link']);
        exit();
    } else {
        echo "Payment initiation failed. Please try again.";
    }
}
?>
