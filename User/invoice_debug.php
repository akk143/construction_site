<?php
require_once '../DB/connection.php';
session_start();

if (!isset($_SESSION['client_ID'])) {
    die('Please log in to use this page.');
}

$clientId = intval($_SESSION['client_ID']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Debug</title>
    <link rel="stylesheet" href="user.css">
    <style>
        .debug-container {
            max-width: 1200px;
            margin: 3rem auto 4rem;
            padding: 0 1.5rem;
        }
        .debug-section {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .debug-section h3 {
            margin-top: 0;
            color: #333;
        }
        .debug-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .debug-table th, .debug-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .debug-table th {
            background: #f5f5f5;
            font-weight: 600;
        }
        .debug-table tr:hover {
            background: #f9f9f9;
        }
        .label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 200px;
        }
        .code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
        .status-ok { color: #10b981; font-weight: 600; }
        .status-error { color: #ef4444; font-weight: 600; }
        .status-warning { color: #f59e0b; font-weight: 600; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="debug-container">
        <h2>Invoice Debug Information</h2>
        <p>This page shows diagnostic information to help troubleshoot invoice creation.</p>

        <!-- Client Info -->
        <div class="debug-section">
            <h3>Your Account Information</h3>
            <p><span class="label">Client ID:</span> <span class="code"><?php echo $clientId; ?></span></p>
            
            <?php
            $clientSql = "SELECT client_name, client_email, client_phone FROM client WHERE client_ID='$clientId' LIMIT 1";
            $clientQry = mysqli_query($dbconid, $clientSql);
            if ($clientQry && mysqli_num_rows($clientQry) > 0) {
                $client = mysqli_fetch_assoc($clientQry);
                echo '<p><span class="label">Name:</span> ' . htmlspecialchars($client['client_name']) . '</p>';
                echo '<p><span class="label">Email:</span> ' . htmlspecialchars($client['client_email']) . '</p>';
                echo '<p><span class="label">Phone:</span> ' . htmlspecialchars($client['client_phone'] ?? 'Not set') . '</p>';
            }
            ?>
        </div>

        <!-- Purchase Property Records -->
        <div class="debug-section">
            <h3>Purchase Property Records</h3>
            <?php
            $purchaseSql = "SELECT pp.pp_ID, pp.property_ID, pp.purchase_status, pp.remaining_amount, pp.payment_ID, p.property_name FROM purchase_property pp LEFT JOIN property p ON pp.property_ID = p.property_ID WHERE pp.client_ID='$clientId'";
            $purchaseQry = mysqli_query($dbconid, $purchaseSql);
            
            if ($purchaseQry && mysqli_num_rows($purchaseQry) > 0) {
                echo '<p class="status-ok">✓ Found ' . mysqli_num_rows($purchaseQry) . ' purchase record(s)</p>';
                echo '<table class="debug-table">';
                echo '<thead><tr><th>PP ID</th><th>Property</th><th>Status</th><th>Remaining (MMK)</th><th>Payment ID</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($purchaseQry)) {
                    echo '<tr>';
                    echo '<td>' . $row['pp_ID'] . '</td>';
                    echo '<td>' . htmlspecialchars($row['property_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['purchase_status']) . '</td>';
                    echo '<td>' . number_format($row['remaining_amount']) . '</td>';
                    echo '<td>' . $row['payment_ID'] . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="status-error">✗ No purchase records found for your account</p>';
                echo '<p><strong>Next steps:</strong></p>';
                echo '<ol>';
                echo '<li>Go to <a href="property.php">Properties</a> and select a property</li>';
                echo '<li>Click "Process To Purchase"</li>';
                echo '<li>Complete the Stripe payment</li>';
                echo '<li>The webhook should create a purchase record automatically</li>';
                echo '</ol>';
            }
            ?>
        </div>

        <!-- Payment Records -->
        <div class="debug-section">
            <h3>Payment Records</h3>
            <?php
            $paymentSql = "SELECT p.payment_ID, p.amount_paid, p.payment_status, pm.pay_name FROM payment p LEFT JOIN payment_method pm ON p.pm_ID = pm.pm_ID WHERE p.payment_ID IN (SELECT payment_ID FROM purchase_property WHERE client_ID='$clientId')";
            $paymentQry = mysqli_query($dbconid, $paymentSql);
            
            if ($paymentQry && mysqli_num_rows($paymentQry) > 0) {
                echo '<p class="status-ok">✓ Found ' . mysqli_num_rows($paymentQry) . ' payment record(s)</p>';
                echo '<table class="debug-table">';
                echo '<thead><tr><th>Payment ID</th><th>Amount</th><th>Status</th><th>Method</th></tr></thead>';
                echo '<tbody>';
                while ($row = mysqli_fetch_assoc($paymentQry)) {
                    echo '<tr>';
                    echo '<td>' . $row['payment_ID'] . '</td>';
                    echo '<td>$' . number_format($row['amount_paid'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($row['payment_status']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['pay_name']) . '</td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="status-warning">No payment records found</p>';
            }
            ?>
        </div>

        <!-- Webhook Logs -->
        <div class="debug-section">
            <h3>Stripe Webhook Status & Logs</h3>
            <p><strong>How to check webhook logs:</strong></p>
            <ol>
                <li>Open your <strong>XAMPP Control Panel</strong></li>
                <li>Click <strong>Logs</strong> button next to Apache</li>
                <li>Look for entries starting with <strong>[Stripe Webhook]</strong></li>
                <li>Each successful payment should have a log entry like:</li>
                <code style="background: #f5f5f5; padding: 1rem; display: block; border-radius: 4px; margin: 1rem 0;">
[Stripe Webhook] Event: checkout.session.completed | Property: 1 | Client: 1 | Amount: 0.15 USD | Deposit: 550 MMK<br>
[Stripe Webhook] SUCCESS: Created purchase_property (ID: 1) and payment (ID: 1)
                </code>
            </ol>
            
            <p style="margin-top: 2rem;"><strong>Alternative - Check using Terminal:</strong></p>
            <code style="background: #f5f5f5; padding: 1rem; display: block; border-radius: 4px; margin: 1rem 0;">
tail -n 50 /Applications/XAMPP/xamppfiles/logs/apache_php.log | grep "Stripe"
            </code>
            
            <p style="margin-top: 2rem; padding: 1rem; background: #f0f9ff; border-radius: 4px; border-left: 4px solid #3f74c1;">
                <strong>ℹ️ Note:</strong> Webhook logs are written to the PHP error log instead of separate files. This avoids permission issues and is the standard practice for production environments.
            </p>
        </div>

        <!-- Troubleshooting Guide -->
        <div class="debug-section">
            <h3>Troubleshooting Guide</h3>
            <ol>
                <li><strong>If you see "No purchase records found":</strong>
                    <ul>
                        <li>Check webhook logs above - if no events, the Stripe webhook might not be configured correctly</li>
                        <li>Make sure your Stripe webhook endpoint is set to: <code><?php echo htmlspecialchars('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/stripe_webhook.php'); ?></code></li>
                        <li>In Stripe Dashboard, go to Developers > Webhooks and add endpoint for <code>checkout.session.completed</code> events</li>
                    </ul>
                </li>
                <li><strong>If you see payment records but no purchase records:</strong>
                    <ul>
                        <li>The webhook is being called, but the purchase_property insertion is failing</li>
                        <li>Check if there's an error in the webhook logs</li>
                    </ul>
                </li>
                <li><strong>If everything looks good:</strong>
                    <ul>
                        <li>Go to <a href="invoice.php">Invoices</a> to view your records</li>
                    </ul>
                </li>
            </ol>
        </div>

        <p style="text-align: center; margin-top: 3rem;">
            <a href="invoice.php" class="action-btn view-property">View Invoices</a>
            <a href="property.php" class="action-btn view-property">Browse Properties</a>
        </p>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
