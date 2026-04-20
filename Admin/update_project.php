<?php
include("../DB/connection.php");

if(isset($_GET['completeid'])){

    $id = $_GET['completeid'];

    $sql = "UPDATE Project SET p_status='completed' WHERE pj_ID='$id'";
    mysqli_query($dbconid,$sql);

    header("Location: project.php");
}
?>