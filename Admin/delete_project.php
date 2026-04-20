<?php
include("../DB/connection.php");

if(isset($_GET['deleteid'])){

    $id = $_GET['deleteid'];

    // 1️⃣ Check project status
    $check_status = "SELECT p_status FROM Project WHERE pj_ID='$id'";
    $status_qry = mysqli_query($dbconid,$check_status);
    $status_row = mysqli_fetch_assoc($status_qry);

    // 2️⃣ Check if project exists in Feedback table
    $check_feedback = "SELECT * FROM Feedback WHERE pj_ID='$id'";
    $feedback_qry = mysqli_query($dbconid,$check_feedback);

    if($status_row['p_status'] == "completed" || mysqli_num_rows($feedback_qry) > 0){

        // Not allowed to delete
        echo "<script>
                alert('This project cannot be deleted.');
                window.location='../Admin/project.php';
              </script>";

    }else{

        // Delete project
        $delete_sql = "DELETE FROM Project WHERE pj_ID='$id'";
        mysqli_query($dbconid,$delete_sql);

        echo "<script>
                alert('Project deleted successfully.');
                window.location='../Admin/project.php';
              </script>";
    }

}
?>