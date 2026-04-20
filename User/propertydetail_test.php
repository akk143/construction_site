<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Detail</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
    <style>
        /* property detail */
.propertyDetail-container{
    width: 100%;
    display:flex;
    background:blue;
}     
.propertyDetail-container .property-image{
    background:red;
    width: 50%;
    /* margin: 5% 0%; */
}
.propertyDetail-container .property-image img{
   
    border-radius: 10px;
    box-shadow: 0px 4px 9px lightgrey;
}
.propertyDetail-container .property-details .property-title{
    /* text-align: center; */
    color: #335e8f;
    font-size: 2.7rem;
    font-family: Franklin Gothic Heavy;
}
.propertyDetail-container .property-details .property-lists h4,
.propertyDetail-container .property-description h5,
.propertyDetail-container .property-note h5{
    font-size: 1.5rem;
    margin: 1.3rem 0rem 0.5rem 1rem;
    color: #335e8f;
    font-weight: bold;
    position: relative;
}
.propertyDetail-container .property-details .property-lists h4::before,
.propertyDetail-container .property-description h5::before,
.propertyDetail-container .property-note h5::before{
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 33px;
    height: 4px;
    background-color: #335e8f;
}
.propertyDetail-container .property-details .property-lists,
.propertyDetail-container .property-description ul,
.propertyDetail-container .property-note ul{
    margin: 1rem 2rem;
}
.propertyDetail-container .property-details .property-lists li,
.propertyDetail-container .property-description ul li,
.propertyDetail-container .property-note ul li{
    color: #335e8f;
    font-size: 1.3rem;
}
.propertyDetail-container .property-details .property-lists li span{
    font-weight: bold;
}
.propertyDetail-container .property-form-button{
    margin: 4rem 0 0 4rem;
}
.propertyDetail-container .property-form-button a{
    border-radius: 10px;
    width: 8rem;
    height: 5rem;
    text-decoration: none;
    background-color: #335e8f;
    color: #fff;
    padding: 1rem 2rem;
}
.propertyDetail-container .property-form-button a:hover{
    background-color: #758cbd;
}

    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <!-- property detail -->      
    <section class="propertyDetail-container">
        <div class="property-image">
            <img src="../img/main_logo.jpg" alt="">
        </div>
        <div class="property-details">
            <h2 class="property-title">Expedita placeat doloremque autem officia.</h2>
            <div class="property-lists">
                <h4>Information</h4>
                <ul>
                    <li><span>Price: </span>55 MMK</li>
                    <li><span>Type: </span>Vero tempora atque animi quasi eos hic sed? </li>
                    <li><span>Location: </span>lorem</li>
                    <li><span>Area: </span>lorem</li>
                    <li><span>Land size: </span>lorem</li>
                    <li><span>No of bedroom: </span>4</li>
                    <li><span>No of bathroom: </span>5</li>
                    <li><span>Built year: 2021</span></li>
                </ul>
            </div>
            <div class="property-description">
                <h5>Other Facts</h5>
                <ul>
                    <li>Lorem ipsum dolor sit amet consectetur adipisicing elit. </li>
                </ul>
            </div>
            <div class="property-note">
                <h5>Note</h5>
                <ul>
                    <li>If you are interested in purchasing this property, a 10% deposit is required to secure it. The remaining balance will be paid in person at our office when signing the contract.</li>
                </ul>
            </div>
            <div class="property-form-button">
                <a href="purchaseProperty.php">Process To Purchase</a>
            </div>
        </div>      
        
    </section>
   
    <!-- photo gallery -->
    <section class="photo-gallery">
        <div class="gallery-container">
            <h3 class="heading">Gallery</h3>
            <div class="gallery-photos">
            <?php

                if(isset($_GET['propertyId'])){
                $propertyId=$_GET['propertyId'];
                $propertyGallery_sql="SELECT * FROM Property_gallery, Property WHERE Property_gallery.property_ID = Property.property_ID
                AND Property.property_ID='$propertyId'";               
                $propertyGallery_qry=mysqli_query($dbconid,$propertyGallery_sql);  
                while($propertyGallery_result= mysqli_fetch_assoc($propertyGallery_qry))
                {
                    $propertyGallery="../Admin/imgUpload/property_image/".$propertyGallery_result['property_image'];
                ?>
                <div class="gallery-photo">
                    <a href="<?php echo $propertyGallery;?>">
                        <img src="<?php echo $propertyGallery;?>">
                        <?php echo $property_result['property_description'];?>
                    </a>
                </div>
            <?php } }?>      
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