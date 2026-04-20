<?php
include("../DB/connection.php");

if(isset($_GET['sold_id'])){

    $id = $_GET['sold_id'];

    $sql = "UPDATE Property SET property_status='Sold Out' WHERE property_ID='$id'";
    mysqli_query($dbconid,$sql);

    header("Location: property.php");
}
?>