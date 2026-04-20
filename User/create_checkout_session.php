<?php
require_once 'stripe_config.php';
require_once '../DB/connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit;
}

if (!isset($_SESSION['client_ID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to continue.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$propertyId = isset($input['propertyId']) ? intval($input['propertyId']) : 0;

if ($propertyId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Property not specified.']);
    exit;
}

$property_sql = "SELECT property_name, property_price, property_status FROM property WHERE property_ID='$propertyId' LIMIT 1";
$property_qry = mysqli_query($dbconid, $property_sql);
if (!$property_qry || mysqli_num_rows($property_qry) === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Property not found.']);
    exit;
}

$property = mysqli_fetch_assoc($property_qry);
if (strcasecmp(trim($property['property_status']), 'Sold Out') === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'This property has already been sold out and cannot be purchased.']);
    exit;
}

$price = intval($property['property_price']);
$deposit = max(1, intval(ceil($price * 0.10)));
$usdDeposit = max(50, intval(ceil($deposit / MMK_TO_USD_RATE * 100)));
$amount = $usdDeposit;
$clientId = intval($_SESSION['client_ID']);

$baseUrl = stripe_get_base_url();
$successUrl = $baseUrl . '/stripe_success.php?session_id={CHECKOUT_SESSION_ID}';
$cancelUrl = $baseUrl . '/stripe_cancel.php?purchaseId=' . urlencode($propertyId);

$checkoutPayload = [
    'payment_method_types[]' => 'card',
    'mode' => 'payment',
    'line_items[0][price_data][currency]' => STRIPE_CURRENCY,
    'line_items[0][price_data][product_data][name]' => STRIPE_COMPANY_NAME . ' deposit for ' . $property['property_name'],
    'line_items[0][price_data][product_data][description]' => '10% property deposit payment for ' . STRIPE_COMPANY_NAME,
    'line_items[0][price_data][unit_amount]' => $amount,
    'line_items[0][quantity]' => 1,
    'payment_intent_data[statement_descriptor]' => STRIPE_STATEMENT_DESCRIPTOR,
    'payment_intent_data[description]' => 'Deposit payment to ' . STRIPE_COMPANY_NAME . ' for ' . $property['property_name'],
    'success_url' => $successUrl,
    'cancel_url' => $cancelUrl,
    'metadata' => [
        'property_id' => $propertyId,
        'client_id' => $clientId,
        'deposit_amount' => $deposit
    ],
];

$session = stripe_api_post('https://api.stripe.com/v1/checkout/sessions', $checkoutPayload);

if (!$session || empty($session['url'])) {
    $errorMessage = 'Unable to create Stripe checkout session.';
    if (!empty($session['error'])) {
        $errorMessage .= ' ' . (is_string($session['error']) ? $session['error'] : json_encode($session['error']));
    }
    if (!empty($session['stripe_response']) && is_array($session['stripe_response']) && !empty($session['stripe_response']['error'])) {
        $stripeError = $session['stripe_response']['error'];
        $errorMessage .= ' ' . ($stripeError['message'] ?? json_encode($stripeError));
    }
    http_response_code(500);
    echo json_encode(['error' => $errorMessage]);
    exit;
}

echo json_encode(['url' => $session['url']]);
