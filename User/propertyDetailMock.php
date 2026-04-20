<?php
$propertyId = intval($_GET['propertyId'] ?? 0);
$remoteUrl = 'https://raw.githubusercontent.com/anshumansinha1/real-estate-mock-api/master/db.json';
$json = @file_get_contents($remoteUrl);
$property = null;

if ($json !== false) {
    $data = json_decode($json, true);
    $listings = $data['real-estate-data']['listings'] ?? [];

    foreach ($listings as $item) {
        if (intval($item['property_id'] ?? 0) === $propertyId) {
            $property = $item;
            break;
        }
    }
}

function escapeHtml($value) {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function safeText($value, $default = 'N/A') {
    return $value !== null && $value !== '' ? escapeHtml($value) : $default;
}

$propertyImg = $property ? "https://picsum.photos/seed/property{$propertyId}/900/500" : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Detail</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="propertyDetail-container">
        <div class="property-layout">
            <?php if (!$property): ?>
                <div class="property-meta" style="margin-top: 2rem; text-align: center; width: 100%;">
                    <h3>Property not found</h3>
                    <p>We could not find that mock listing. Please return to the property list.</p>
                    <a href="property.php" class="btn">Back to listings</a>
                </div>
            <?php else: ?>
                <div class="property-image-section">
                    <div class="property-image">
                        <img src="<?php echo escapeHtml($propertyImg); ?>" alt="<?php echo safeText($property['property_name']); ?>">
                    </div>
                    <div class="property-description">
                        <h5>Property description</h5>
                        <p><?php echo nl2br(escapeHtml($property['description'] ?? 'No description available.')); ?></p>
                    </div>
                </div>
                <div class="property-details">
                    <div class="property-header-row">
                        <h2 class="property-title"><?php echo safeText($property['property_name']); ?></h2>
                        <span class="badge available">Mock Listing</span>
                    </div>
                    <div class="property-lists">
                        <h4>Property details</h4>
                        <ul>
                            <li class="label"><span class="value">Price:</span> <?php echo number_format($property['price'] ?? 0); ?> MMK</li>
                            <li class="label"><span class="value">Property type:</span> <?php echo safeText($property['property_type']); ?></li>
                            <li class="label"><span class="value">Location:</span> <?php echo safeText(trim(($property['address'] ?? '') . ', ' . ($property['city'] ?? ''))); ?></li>
                            <li class="label"><span class="value">Area:</span> <?php echo number_format($property['square_footage'] ?? 0); ?> sqft</li>
                            <li class="label"><span class="value">Built year:</span> <?php echo safeText($property['year_built']); ?></li>
                            <li class="label"><span class="value">Listing date:</span> <?php echo safeText($property['listing_date']); ?></li>
                        </ul>
                    </div>
                    <div class="property-note">
                        <h5>Note</h5>
                        <p>This is a mock property detail page powered by an external demo API, so purchases are not supported here.</p>
                    </div>
                    <div class="property-form-button">
                        <a href="property.php" class="btn primary full">Back to listings</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
