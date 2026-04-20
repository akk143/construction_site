<?php include 'stripe_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Property</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <?php
    $propertyId = isset($_GET['purchaseId']) ? intval($_GET['purchaseId']) : 0;
    $errorMessage = '';
    $property = null;
    $depositAmount = 0;

    if ($propertyId <= 0) {
        $errorMessage = 'No property was selected for purchase.';
    } else {
        $property_sql = "SELECT property_name, property_price, property_location, property_status FROM Property WHERE property_ID='$propertyId' LIMIT 1";
        $property_qry = mysqli_query($dbconid, $property_sql);
        if ($property_qry && mysqli_num_rows($property_qry) > 0) {
            $property = mysqli_fetch_assoc($property_qry);
            $depositAmount = max(1, intval(ceil($property['property_price'] * 0.10)));
            $usdDeposit = max(0.5, round($depositAmount / MMK_TO_USD_RATE, 2));

            $purchaseCheckSql = "SELECT pp_ID FROM Purchase_Property WHERE property_ID='$propertyId' LIMIT 1";
            $purchaseCheckQry = mysqli_query($dbconid, $purchaseCheckSql);
            $hasExistingPurchase = $purchaseCheckQry && mysqli_num_rows($purchaseCheckQry) > 0;
            if ($hasExistingPurchase) {
                $errorMessage = 'A deposit has already been paid for this property. Please contact support for next steps.';
            } elseif (strcasecmp(trim($property['property_status']), 'Sold Out') === 0) {
                $errorMessage = 'This property is sold out and cannot be purchased.';
            }
        } else {
            $errorMessage = 'Property not found.';
        }
    }
    ?>

    <section class="purchaseform-container">
        <div class="purchase-card">
            <?php if ($errorMessage): ?>
                <div class="purchase-result-card error">
                    <h4>Purchase blocked</h4>
                    <p><?php echo htmlspecialchars($errorMessage); ?></p>
                    <a href="property.php" class="purchase-action">Back to Properties</a>
                </div>
            <?php else: ?>
                <div class="purchase-detail-card">
                    <h4>Deposit payment</h4>
                    <div class="purchase-detail-row">
                        <span>Property</span>
                        <strong><?php echo htmlspecialchars($property['property_name']); ?></strong>
                    </div>
                    <div class="purchase-detail-row">
                        <span>Location</span>
                        <strong><?php echo htmlspecialchars($property['property_location']); ?></strong>
                    </div>
                    <div class="purchase-detail-row">
                        <span>Total price</span>
                        <strong><?php echo number_format($property['property_price']); ?> MMK</strong>
                    </div>
                    <div class="purchase-detail-row highlight">
                        <span>Deposit due</span>
                        <strong><?php echo number_format($depositAmount); ?> MMK</strong>
                    </div>
                    <div class="purchase-detail-row">
                        <span>Stripe payment</span>
                        <strong>$<?php echo number_format($usdDeposit, 2); ?> USD</strong>
                    </div>
                    <p class="payment-note">Site price: MMK. Stripe charges the USD equivalent.</p>
                    <p class="payment-note">Additional currencies displayed by Stripe are for reference only and are converted based on your location; the transaction is processed in the original currency(USD).</p>
                    <button id="checkout-button" class="purchase-btn">Pay deposit in USD</button>
                    <p id="checkout-message" class="purchase-status"></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        <?php if ($property): ?>
        const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');
        const checkoutButton = document.getElementById('checkout-button');
        const messageEl = document.getElementById('checkout-message');

        checkoutButton.addEventListener('click', async () => {
            checkoutButton.disabled = true;
            messageEl.textContent = 'Creating Stripe checkout session...';

            const response = await fetch('create_checkout_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({propertyId: <?php echo json_encode($propertyId); ?>})
            });

            const data = await response.json();
            if (response.ok && data.url) {
                window.location = data.url;
                return;
            }

            messageEl.textContent = data.error || 'Unable to start payment. Please try again.';
            checkoutButton.disabled = false;
        });
        <?php endif; ?>
    </script>
</body>
</html>
