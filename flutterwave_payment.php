<?php
session_start();

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tableNumber = $_POST['table_number'];
    $total = $_POST['total'];

    // Check if the user's email exists in the session
    $customerEmail = isset($_SESSION['email']) ? $_SESSION['email'] : "customer@example.com"; // Default email if not logged in

    // Generate a unique transaction reference
    $tx_ref = uniqid("TXREF_");
    $redirect_url = "http://localhost/elmore/flutterwave_verify.php"; // Replace with your redirect URL

    // Collect order details
    $orderDetails = [];
    foreach ($_SESSION['cart'] as $itemId => $quantity) {
        $result = $conn->query("SELECT * FROM menu_items WHERE item_id = $itemId");
        $item = $result->fetch_assoc();
        $orderDetails[] = [
            'item_id' => $itemId,
            'quantity' => $quantity,
            'subtotal' => $item['price'] * $quantity
        ];
    }

    // Save transaction details to session
    $_SESSION['tx_ref'] = $tx_ref;
    $_SESSION['table_number'] = $tableNumber;
    $_SESSION['order_details'] = $orderDetails;
    $_SESSION['total'] = $total;

    // Flutterwave payment request data
    $flutterwave_data = [
        "tx_ref" => $tx_ref,
        "amount" => $total,
        "currency" => "NGN",
        "redirect_url" => $redirect_url,
        "customer" => [
            "email" => $customerEmail, // Use email from session
            "phone_number" => "08012345678", // Placeholder phone number
            "name" => "Table " . htmlspecialchars($tableNumber)
        ],
        "customizations" => [
            "title" => "Bar App Payment",
            "description" => "Payment for order at Table " . htmlspecialchars($tableNumber)
        ]
    ];

    // Initialize payment using cURL
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.flutterwave.com/v3/payments",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($flutterwave_data),
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
            // Redirect to the Flutterwave payment page
            header("Location: " . $response_data['data']['link']);
            exit;
        } else {
            echo "Error initializing payment: " . $response_data['message'];
        }
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
