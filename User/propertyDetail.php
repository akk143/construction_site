<?php
require_once '../DB/connection.php';
require_once 'stripe_config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Detail</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- property detail -->
    <section class="propertyDetail-container">
        <?php
        if(isset($_GET['propertyId'])){
        $propertyId=$_GET['propertyId'];
        $propertyDetail_sql="SELECT * FROM Property_type, Property WHERE Property_type.pt_ID = Property.pt_ID
        AND Property.property_ID='$propertyId'";               
        $propertyDetail_qry=mysqli_query($dbconid,$propertyDetail_sql);  
        while($property_result= mysqli_fetch_assoc($propertyDetail_qry))
        {
			$propertyprofile="../Admin/imgUpload/".$property_result['property_profile'];
        ?>
        <div class="property-layout">
            <div class="property-image-section">
                <div class="property-image">
                    <?php 
                    // Determine image source
                    $imageUrl = $propertyprofile;
                    if (empty($property_result['property_profile']) || $property_result['property_profile'] === '') {
                        // Use picsum placeholder if no image
                        $imageUrl = 'https://picsum.photos/seed/property' . $propertyId . '/800/600';
                    }
                    ?>
                    <img src="<?php echo $imageUrl;?>" alt="<?php echo htmlspecialchars($property_result['property_name']); ?>" loading="lazy">
                </div>
                <div class="property-description enhanced-description">
                    <h5><i class="bi bi-file-text"></i> About this property</h5>
                    <p><?php 
                    // Use property_description if available, otherwise use description from API
                    $desc = !empty($property_result['property_description']) ? $property_result['property_description'] : '';
                    echo nl2br(htmlspecialchars($desc)); 
                    ?></p>
                </div>
            </div>
            <div class="property-details">
                <?php
                    $clientId = intval($_SESSION['client_ID'] ?? 0);
                    $hasExistingPurchase = false;
                    if ($propertyId > 0) {
                        $purchaseCheckSql = "SELECT pp_ID FROM Purchase_Property WHERE property_ID='$propertyId' LIMIT 1";
                        $purchaseCheckQry = mysqli_query($dbconid, $purchaseCheckSql);
                        $hasExistingPurchase = $purchaseCheckQry && mysqli_num_rows($purchaseCheckQry) > 0;
                    }
                    $depositAmount = max(1, intval(ceil($property_result['property_price'] * 0.10)));
                    $usdDeposit = max(0.5, round($depositAmount / MMK_TO_USD_RATE, 2));
                    $effectiveStatus = $property_result['property_status'];
                    if ($hasExistingPurchase && strcasecmp(trim($effectiveStatus), 'Sold Out') !== 0) {
                        $effectiveStatus = 'Reserved';
                    }
                    $isStatusDisabled = in_array(strtolower(str_replace(' ', '-', $effectiveStatus)), ['sold-out', 'reserved'], true);
                ?>
                <div class="property-header-row">
                    <div class="header-content">
                        <h2 class="property-title"><?php echo htmlspecialchars($property_result['property_name']);?></h2>
                        <p class="property-location-header"><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($property_result['property_location']); ?></p>
                    </div>
                    <span class="property-status-badge <?php echo strtolower(str_replace(' ', '-', $effectiveStatus)); ?>"><?php echo htmlspecialchars($effectiveStatus); ?></span>
                </div>
                <div class="property-lists enhanced-lists">
                    <h4><i class="bi bi-info-circle"></i> Property details</h4>
                    <div class="property-details-grid">
                        <div class="detail-card price-card">
                            <div class="detail-icon"><i class="bi bi-currency-dollar"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Price</span>
                                <span class="detail-value"><?php echo number_format(floor($property_result['property_price'])); ?> MMK</span>
                            </div>
                        </div>
                        <div class="detail-card deposit-card">
                            <div class="detail-icon"><i class="bi bi-percent"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Deposit (10%)</span>
                                <span class="detail-value"><?php echo number_format($depositAmount); ?> MMK</span>
                            </div>
                        </div>
                        <div class="detail-card stripe-card">
                            <div class="detail-icon"><i class="bi bi-credit-card"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Stripe USD</span>
                                <span class="detail-value">$<?php echo number_format($usdDeposit, 2); ?></span>
                            </div>
                        </div>
                        <div class="detail-card type-card">
                            <div class="detail-icon"><i class="bi bi-house-door"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Type</span>
                                <span class="detail-value"><?php echo htmlspecialchars($property_result['ptype'] ?? $property_result['property_type'] ?? 'Property'); ?></span>
                            </div>
                        </div>
                        <?php if (!empty($property_result['property_area']) && intval($property_result['property_area']) > 0): ?>
                        <div class="detail-card area-card">
                            <div class="detail-icon"><i class="bi bi-border-all"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Area</span>
                                <span class="detail-value"><?php echo number_format($property_result['property_area']); ?> sqft</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($property_result['no_of_bedroom']) && intval($property_result['no_of_bedroom']) > 0): ?>
                        <div class="detail-card bedroom-card">
                            <div class="detail-icon"><i class="bi bi-door-closed"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Bedrooms</span>
                                <span class="detail-value"><?php echo htmlspecialchars($property_result['no_of_bedroom']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($property_result['no_of_bathroom']) && intval($property_result['no_of_bathroom']) > 0): ?>
                        <div class="detail-card bathroom-card">
                            <div class="detail-icon"><i class="bi bi-water"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Bathrooms</span>
                                <span class="detail-value"><?php echo htmlspecialchars($property_result['no_of_bathroom']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($property_result['built_year']) || !empty($property_result['year_built'])): ?>
                        <div class="detail-card year-card">
                            <div class="detail-icon"><i class="bi bi-calendar"></i></div>
                            <div class="detail-info">
                                <span class="detail-label">Built year</span>
                                <span class="detail-value"><?php echo htmlspecialchars($property_result['built_year'] ?? $property_result['year_built']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="property-note enhanced-note">
                    <div class="note-header">
                        <h5><i class="bi bi-info-circle-fill"></i> How it works</h5>
                    </div>
                    <div class="note-content">
                        <div class="note-item">
                            <span class="note-icon">1</span>
                            <p>Price shown on this site is in <strong>MMK</strong>. Stripe will charge the <strong>USD equivalent</strong>.</p>
                        </div>
                        <div class="note-item">
                            <span class="note-icon">2</span>
                            <p>If Stripe shows VND or another local currency, that is only for display purposes.</p>
                        </div>
                        <div class="note-item">
                            <span class="note-icon">3</span>
                            <p>Receipt name follows your Stripe Business Profile settings.</p>
                        </div>
                    </div>
                </div>
                <div class="property-form-button">
                    <?php if ($isStatusDisabled): ?>
                        <a class="btn sold-out full"><?php echo $effectiveStatus === 'Reserved' ? 'Reserved' : 'Sold Out'; ?></a>
                    <?php else: ?>
                        <a href="purchaseProperty.php?purchaseId=<?php echo $property_result['property_ID'];?>" class="btn primary full">Process To Purchase</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php } }?>
    </section>
   
    <!-- photo gallery -->
    <section class="photo-gallery">
        <div class="gallery-container">
            <h3 class="heading">Gallery</h3>
            <div class="gallery-photos">
            <?php
                if(isset($_GET['propertyId'])){
                $propertyId=$_GET['propertyId'];
                $propertyGallery_sql="SELECT * FROM Property_gallery WHERE property_ID='$propertyId' LIMIT 4";               
                $propertyGallery_qry=mysqli_query($dbconid,$propertyGallery_sql);  
                $galleryCount = mysqli_num_rows($propertyGallery_qry);
                
                // If no gallery images in database, generate placeholder images
                if($galleryCount == 0) {
                    // Generate 4 placeholder gallery images
                    for($i = 1; $i <= 4; $i++) {
                        $placeholder = 'https://picsum.photos/seed/gallery' . $propertyId . '_' . $i . '/600/400';
                    ?>
                        <div class="gallery-photo">
                            <a href="<?php echo $placeholder;?>" data-lightbox="gallery">
                                <img src="<?php echo $placeholder;?>" alt="Property Gallery Image <?php echo $i;?>" loading="lazy">
                                <div class="gallery-overlay">
                                    <i class="bi bi-search"></i>
                                </div>
                            </a>
                        </div>
                    <?php
                    }
                } else {
                    // Display actual gallery images from database (max 4)
                    while($propertyGallery_result = mysqli_fetch_assoc($propertyGallery_qry)) {
                        $propertyGallery="../Admin/imgUpload/property_image/".$propertyGallery_result['property_image'];
                    ?>
                        <div class="gallery-photo">
                            <a href="<?php echo $propertyGallery;?>" data-lightbox="gallery">
                                <img src="<?php echo $propertyGallery;?>" alt="Property Gallery" loading="lazy">
                                <div class="gallery-overlay">
                                    <i class="bi bi-search"></i>
                                </div>
                            </a>
                        </div>
                    <?php 
                    }
                }
                }
            ?>      
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.photo-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    </script>
</body>
</html>