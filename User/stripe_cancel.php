<?php
require_once '../DB/connection.php';
session_start();
$propertyId = isset($_GET['purchaseId']) ? intval($_GET['purchaseId']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="purchaseform-container">
        <div class="purchase-card">
            <div class="purchase-result-card warning">
                <h4>Payment cancelled</h4>
                <p>Your payment was cancelled. You can continue the purchase process or return to the property details page.</p>
                <?php if ($propertyId > 0): ?>
                    <a href="purchaseProperty.php?purchaseId=<?php echo $propertyId; ?>" class="purchase-action">Return to Purchase</a>
                <?php else: ?>
                    <a href="property.php" class="purchase-action">View Properties</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
