<?php
// webhook.php
// Webhook endpoint to receive payment status updates from Monnify

// Set timezone
date_default_timezone_set("Africa/Lagos");

// Database config
include 'dbconfig.php';

// Secret Key for validation
define('MONNIFY_SECRET_KEY', 'D4FNAZSJQZTJ9D7XTNHWQJ99BBAHCLRQ');

// Retrieve POST data
$requestBody = file_get_contents('php://input');
$event = json_decode($requestBody, true);

// Validate Monnify signature
$signature = $_SERVER['HTTP_MONNIFY_SIGNATURE'] ?? '';

$calculatedSignature = hash_hmac('sha512', $requestBody, MONNIFY_SECRET_KEY);

if ($signature !== $calculatedSignature) {
    http_response_code(401);
    echo json_encode(['status' => 'Invalid signature']);
    exit;
}

// Ensure it's a payment successful event
if (
    isset($event['eventType']) &&
    $event['eventType'] === 'SUCCESSFUL_TRANSACTION'
) {
    $paymentRef = mysqli_real_escape_string($con, $event['eventData']['paymentReference']);
    $amountPaid = mysqli_real_escape_string($con, $event['eventData']['paidAmount']);
    $customerEmail = mysqli_real_escape_string($con, $event['eventData']['customer']['email']);
    $customerName = mysqli_real_escape_string($con, $event['eventData']['customer']['name']);
    $transactionDate = date('Y-m-d H:i:s', strtotime($event['eventData']['paymentDate']));

    // Check if already recorded
    $check = mysqli_query($con, "SELECT * FROM studentpayment WHERE paymentreference='$paymentRef'");
    if (mysqli_num_rows($check) == 0) {
        // Insert into studentpayment
        mysqli_query($con, "
            INSERT INTO studentpayment (paymentreference, amount, email, fullname, paymentdate, status)
            VALUES ('$paymentRef', '$amountPaid', '$customerEmail', '$customerName', '$transactionDate', 'Paid')
        ");

        // Optionally, update failpayment table if needed
        mysqli_query($con, "
            UPDATE failpayment SET status='Paid' WHERE paymentreference='$paymentRef'
        ");
    }

    http_response_code(200);
    echo json_encode(['status' => 'Payment recorded successfully']);
    exit;
}

// If not handled
http_response_code(400);
echo json_encode(['status' => 'Unhandled event']);
