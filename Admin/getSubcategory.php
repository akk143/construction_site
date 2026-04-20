<?php
include "../DB/connection.php"; // your database connection

if(isset($_GET['cid'])){

$cid=$_GET['cid'];

$sql="SELECT subCate_ID, subCate_name 
      FROM Service_SubCategory 
      WHERE sc_ID='$cid'";

$qry=mysqli_query($dbconid,$sql);

echo "<option value=''>-----Select Subcategory-----</option>";

while($row=mysqli_fetch_assoc($qry)){

$id=$row['subCate_ID'];
$name=$row['subCate_name'];

echo "<option value='$id'>$name</option>";

}

}
?>