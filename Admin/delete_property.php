<?php
include("../DB/connection.php");

if(isset($_GET['deleteid'])){

    $id = $_GET['deleteid'];

    // Check property status
    $stmt_check = mysqli_prepare($dbconid, "SELECT property_status FROM Property WHERE property_ID = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $id);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    $status_row = mysqli_fetch_assoc($result_check);
    mysqli_stmt_close($stmt_check);

    if(!$status_row){
        echo "<script>alert('Property not found.'); window.location='../Admin/property.php';</script>";
    }elseif($status_row['property_status'] == "Sold Out"){

        // Not allowed to delete
        echo "<script>
                alert('This property cannot be deleted.');
                window.location='../Admin/property.php';
              </script>";

    }else{

        // Start transaction
        mysqli_begin_transaction($dbconid);
        
        // Fetch images
        $stmt = mysqli_prepare($dbconid, "SELECT property_image FROM property_gallery WHERE property_ID = ?");
        mysqli_stmt_bind_param($stmt, "s", $id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($dbconid);
            echo "<script>alert('Error fetching images.'); window.location='../Admin/property.php';</script>";
            exit;
        }
        $result = mysqli_stmt_get_result($stmt);
        $images = [];
        while($row = mysqli_fetch_assoc($result)) {
            $images[] = $row['property_image'];
        }
        mysqli_stmt_close($stmt);
        
        // Delete files
        foreach($images as $img) {
            $file_path = "../Admin/imgUpload/property_image/" . $img;
            if(file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Delete from gallery
        $stmt2 = mysqli_prepare($dbconid, "DELETE FROM property_gallery WHERE property_ID = ?");
        mysqli_stmt_bind_param($stmt2, "s", $id);
        if (!mysqli_stmt_execute($stmt2)) {
            mysqli_rollback($dbconid);
            echo "<script>alert('Error deleting gallery records.'); window.location='../Admin/property.php';</script>";
            exit;
        }
        mysqli_stmt_close($stmt2);
        
        // Delete property
        $stmt3 = mysqli_prepare($dbconid, "DELETE FROM Property WHERE property_ID = ?");
        mysqli_stmt_bind_param($stmt3, "s", $id);
        if (!mysqli_stmt_execute($stmt3)) {
            mysqli_rollback($dbconid);
            echo "<script>alert('Error deleting property.'); window.location='../Admin/property.php';</script>";
            exit;
        }
        mysqli_stmt_close($stmt3);
        
        // Commit
        mysqli_commit($dbconid);
        echo "<script>alert('Property deleted successfully.'); window.location='../Admin/property.php';</script>";
    }

}
?>