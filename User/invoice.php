<?php
require_once '../DB/connection.php';
session_start();

if (!isset($_SESSION['client_ID'])) {
    header('Location: login.php');
    exit;
}

$clientId = intval($_SESSION['client_ID']);
$invoiceSql = "SELECT pp.pp_ID, pp.purchase_note, pp.remaining_amount, pp.purchase_status, pp.property_ID, pp.payment_ID,
    p.property_name, p.property_location, p.property_price, p.property_area, p.no_of_bedroom, p.no_of_bathroom, p.built_year, p.land_size, p.property_profile,
    payment.amount_paid AS deposit_paid, payment.payment_status AS payment_status, payment_method.pay_name,
    client.client_name, client.client_email, client.client_phone
    FROM purchase_property pp
    JOIN property p ON p.property_ID = pp.property_ID
    JOIN payment payment ON payment.payment_ID = pp.payment_ID
    JOIN payment_method payment_method ON payment_method.pm_ID = payment.pm_ID
    JOIN client client ON client.client_ID = pp.client_ID
    WHERE pp.client_ID = '$clientId'
    ORDER BY pp.pp_ID DESC";

$invoiceQry = mysqli_query($dbconid, $invoiceSql);

// Log any database errors
if (!$invoiceQry && defined('DEBUG_MODE')) {
    error_log('Invoice query error: ' . mysqli_error($dbconid));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Invoices</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="invoice-container">
        <div class="invoice-header">
            <div>
                <h2>Invoices</h2>
                <p>Review your deposit payments and purchase records. Once a deposit is paid, the invoice is saved here for follow-up and document confirmation.</p>
            </div>
            <a href="property.php" class="invoice-action">Browse properties</a>
        </div>
        <div class="invoice-summary">
            <div><strong><?php echo mysqli_num_rows($invoiceQry); ?></strong> invoice(s) found</div>
            <div>Click a card to view the related property details and next steps.</div>
        </div>

        <?php if (!$invoiceQry || mysqli_num_rows($invoiceQry) === 0): ?>
            <div class="invoice-empty">
                <h3>No invoices yet</h3>
                <p>You have not completed any deposit payments. Start by selecting a property and paying the deposit to generate an invoice.</p>
            </div>
        <?php else: ?>
            <div class="invoice-list">
                <?php while ($invoice = mysqli_fetch_assoc($invoiceQry)): 
                    $propertyImg = '../Admin/imgUpload/' . $invoice['property_profile'];
                    // Mask phone number: 0911****23 format
                    $phone = isset($invoice['client_phone']) ? $invoice['client_phone'] : '';
                    $maskedPhone = '';
                    if (!empty($phone)) {
                        $len = strlen($phone);
                        if ($len >= 4) {
                            $maskedPhone = substr($phone, 0, 4) . str_repeat('*', max(0, $len - 6)) . substr($phone, -2);
                        } else {
                            $maskedPhone = $phone;
                        }
                    }
                    $paymentDate = date('M d, Y');
                ?>
                <article class="invoice-card real-invoice">
                    <!-- Invoice Header -->
                    <div class="invoice-header-section">
                        <div class="invoice-title-header">
                            <div>
                                <h2><i class="bi bi-receipt"></i> Invoice</h2>
                                <p class="invoice-subtitle">Property Deposit Payment</p>
                            </div>
                            <div class="invoice-status-info">
                                <span class="invoice-badge <?php echo strtolower(str_replace(' ', '-', $invoice['purchase_status'])); ?>">
                                    <?php echo htmlspecialchars($invoice['purchase_status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Details Row -->
                    <div class="invoice-details-header">
                        <div class="invoice-detail-item">
                            <span class="detail-label">Invoice #</span>
                            <span class="detail-value"><?php echo str_pad(intval($invoice['pp_ID']), 5, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        <div class="invoice-detail-item">
                            <span class="detail-label">Payment Date</span>
                            <span class="detail-value"><?php echo $paymentDate; ?></span>
                        </div>
                        <div class="invoice-detail-item">
                            <span class="detail-label">Payment Status</span>
                            <span class="detail-value"><?php echo htmlspecialchars($invoice['payment_status']); ?></span>
                        </div>
                        <div class="invoice-detail-item">
                            <span class="detail-label">Payment Method</span>
                            <span class="detail-value"><?php echo htmlspecialchars($invoice['pay_name']); ?></span>
                        </div>
                    </div>

                    <!-- Bill To Section -->
                    <div class="invoice-content">
                        <div class="invoice-section bill-to">
                            <h4>Bill To</h4>
                            <div class="customer-info-box">
                                <div class="customer-field">
                                    <span class="field-label">Name</span>
                                    <span class="field-value"><?php echo htmlspecialchars($invoice['client_name']); ?></span>
                                </div>
                                <div class="customer-field">
                                    <span class="field-label">Email</span>
                                    <span class="field-value"><?php echo htmlspecialchars($invoice['client_email']); ?></span>
                                </div>
                                <div class="customer-field">
                                    <span class="field-label">Phone</span>
                                    <span class="field-value"><?php echo htmlspecialchars($maskedPhone); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Property Details Section -->
                        <div class="invoice-section property-section">
                            <h4>Property Details</h4>
                            <div class="property-info-box">
                                <div class="property-img-small">
                                    <img src="<?php echo htmlspecialchars($propertyImg); ?>" alt="<?php echo htmlspecialchars($invoice['property_name']); ?>">
                                </div>
                                <div class="property-info-details">
                                    <h5><?php echo htmlspecialchars($invoice['property_name']); ?></h5>
                                    <p class="property-location"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($invoice['property_location']); ?></p>
                                    <div class="property-specs">
                                        <span><i class="bi bi-border-all"></i> <?php echo htmlspecialchars($invoice['property_area']); ?> sqm</span>
                                        <span><i class="bi bi-segmented-nav"></i> <?php echo intval($invoice['no_of_bedroom']); ?> beds</span>
                                        <span><i class="bi bi-water"></i> <?php echo intval($invoice['no_of_bathroom']); ?> baths</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Summary Table -->
                    <div class="invoice-table">
                        <table class="summary-table">
                            <tbody>
                                <tr class="summary-row">
                                    <td class="summary-label">Property Price (MMK)</td>
                                    <td class="summary-value"><?php echo number_format($invoice['property_price']); ?></td>
                                </tr>
                                <tr class="summary-row">
                                    <td class="summary-label">Deposit Amount (10%)</td>
                                    <td class="summary-value">$<?php echo number_format($invoice['deposit_paid'], 2); ?></td>
                                </tr>
                                <tr class="summary-row">
                                    <td class="summary-label">Land Size</td>
                                    <td class="summary-value"><?php echo htmlspecialchars($invoice['land_size']); ?></td>
                                </tr>
                                <tr class="summary-row">
                                    <td class="summary-label">Remaining Balance (MMK)</td>
                                    <td class="summary-value"><?php echo number_format($invoice['remaining_amount']); ?></td>
                                </tr>
                                <tr class="summary-row-total">
                                    <td class="summary-label">Total Paid (USD)</td>
                                    <td class="summary-value">$<?php echo number_format($invoice['deposit_paid'], 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Notes Section -->
                    <?php if (!empty($invoice['purchase_note'])): ?>
                    <div class="invoice-notes-section">
                        <h4>Notes</h4>
                        <p><?php echo htmlspecialchars($invoice['purchase_note']); ?></p>
                    </div>
                    <?php endif; ?>

                    <!-- Invoice Footer -->
                    <div class="invoice-footer-section">
                        <div class="invoice-terms">
                            <p><strong>Terms:</strong> This invoice confirms your deposit payment for the property. Please keep this invoice for your records.</p>
                        </div>
                        <div class="invoice-actions">
                            <a href="propertyDetail.php?propertyId=<?php echo intval($invoice['property_ID']);?>" class="action-btn view-property"><i class="bi bi-house"></i> View Property</a>
                            <button class="action-btn print-invoice" onclick="window.print()"><i class="bi bi-printer"></i> Print Invoice</button>
                        </div>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
