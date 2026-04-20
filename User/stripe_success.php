<?php
require_once 'stripe_config.php';
require_once '../DB/connection.php';
session_start();

if (!isset($_SESSION['client_ID'])) {
    header('Location: login.php');
    exit;
}

$errorMessage = '';
$successMessage = '';

$propertyId = 0;
if (empty($_GET['session_id'])) {
    $errorMessage = 'No Stripe session was provided.';
} else {
    $session_id = preg_replace('/[^A-Za-z0-9_\-]/', '', $_GET['session_id']);
    $session = stripe_api_get('https://api.stripe.com/v1/checkout/sessions/' . urlencode($session_id));

    if (!$session || empty($session['id'])) {
        $errorMessage = 'Unable to verify the Stripe session.';
        if (!empty($session['error'])) {
            $errorMessage .= ' ' . (is_string($session['error']) ? $session['error'] : json_encode($session['error']));
        }
        if (!empty($session['stripe_response']) && is_array($session['stripe_response']) && !empty($session['stripe_response']['error'])) {
            $stripeError = $session['stripe_response']['error'];
            $errorMessage .= ' ' . ($stripeError['message'] ?? json_encode($stripeError));
        }
    } elseif ($session['payment_status'] !== 'paid') {
        $errorMessage = 'The payment was not completed successfully.';
    } else {
        $propertyId = intval($session['metadata']['property_id'] ?? 0);
        $metadata = $session['metadata'] ?? [];
        $depositMmk = intval($metadata['deposit_amount'] ?? 0);
        $amountPaidUsd = intval($session['amount_total'] ?? 0) / 100;

        $fallbackResult = stripe_ensure_purchase_record($dbconid, $propertyId, intval($_SESSION['client_ID']), $depositMmk, $amountPaidUsd);
        if ($fallbackResult['success']) {
            $successMessage = 'Payment completed successfully. Your invoice record has been created.';
        } else {
            $successMessage = 'Payment completed successfully. We are waiting for Stripe webhook confirmation to finalize your invoice.';
            error_log('[Stripe Success] Fallback invoice creation error: ' . $fallbackResult['message']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="purchaseform-container">
        <div class="purchase-card">
            <div class="purchase-result-card <?php echo $errorMessage ? 'error' : 'success'; ?>">
                <?php if ($errorMessage): ?>
                    <h4>Payment failed</h4>
                    <p><?php echo htmlspecialchars($errorMessage); ?></p>
                    <a href="purchaseProperty.php?purchaseId=<?php echo intval($propertyId); ?>" class="purchase-action">Try again</a>
                <?php else: ?>
                    <h4>Payment successful</h4>
                    <p><?php echo htmlspecialchars($successMessage); ?></p>
                    <div class="purchase-actions-row">
                        <a href="property.php" class="purchase-action">Back to Properties</a>
                        <a href="invoice.php" class="purchase-action">View Invoices</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
