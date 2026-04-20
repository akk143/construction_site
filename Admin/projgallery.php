<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<title>Admin Dashboard</title>
</head>
<body>
	<!-- SIDEBAR -->
	<?php include 'sidebar.php'; ?>
	<!-- NAVBAR -->
	<section id="navbar">
		<?php include 'header.php'; ?>
		<!-- MAIN -->
		<main>
			<h1 class="title" style="color: #5F84A2;">Project Gallery</h1>
            <?php
            include_once("../DB/connection.php");

            /* =========================
            SHOW FORM ONLY IF EDIT
            ========================= */
            if(isset($_GET['pjgid'])){

                $pjgid = $_GET['pjgid'];
                $pjg_sql = "SELECT * FROM Project_gallery WHERE projg_ID='$pjgid'";
                $pjg_qry = mysqli_query($dbconid, $pjg_sql);

                while($pjg_result = mysqli_fetch_assoc($pjg_qry)){
                    $projImg = "imgUpload/project_image/".$pjg_result['pj_image'];
                ?>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="pjgid" value="<?php echo $pjg_result['projg_ID'];?>"> 
                    <div class="inputfield">
                        <label>Current Image</label><br>
                        <img id="currentImage" src="<?php echo $projImg; ?>" style="width:100px;height:100px;border:1px solid #ccc;"> 
                    </div>
                    <div class="inputfield"> 
                        <label>Upload New Image</label> 
                        <input type="file" name="pjimg" id="imageInput" accept="image/*" class="input"> 
                    </div> 
                    <div class="inputfield"> 
                        <label id="previewLabel" style="display:none;">New Image Preview</label><br> 
                        <img id="newPreviewImage" style="width:100px;height:100px;display:none;border:1px solid #ccc;"> 
                        <button type="button" id="removePreview" style="display:none;margin-top:5px;"> ❌ Remove </button> 
                    </div> 
                    <div class="inputfield"> 
                        <label>Project Name</label> 
                        <select name="pjid" class="input" required> 
                            <?php $current_pjid = $pjg_result['pj_ID']; 
                            $pj_sql="SELECT pj_ID, pj_title FROM Project"; 
                            $pj_qry=mysqli_query($dbconid,$pj_sql); 
                            while($pj_result=mysqli_fetch_assoc($pj_qry)){ 
                                $pjid=$pj_result['pj_ID']; 
                                $pjname=$pj_result['pj_title']; 
                                if($pjid == $current_pjid){ 
                                    echo "<option value='$pjid' selected>$pjname</option>"; 
                                    }
                                    else{ 
                                        echo "<option value='$pjid'>$pjname</option>"; } } ?> 
                        </select> 
                    </div> 
                    <div class="inputfield"> 
                        <input type="submit" name="projgUpdate" value="Update" class="btn">
                    </div> 
                    <div class="inputfield"> 
                        <a href="projgallery.php" class="btn" style="text-align: center;">Cancel Update</a>
                    </div>
                    </form> 
                </div>                 
                <?php } } ?> 
                            
                        
                        <div class="table-data"> 
                            <h3 class="table=title">Project Gallery Table</h3> 
                            <div class="table-container"> 
                                <table> 
                                    <thead> 
                                        <tr> 
                                            <th>ID</th> 
                                            <th>Project ID</th> 
                                            <th>Image</th> 
                                            <th>Project Name</th> 
                                            <th>Action</th> 
                                        </tr> 
                                        </thead> 
                                        <tbody> 
                                            <?php include_once("../DB/connection.php"); 
                                            $count=1; 
                                            $pjg_sql="SELECT * FROM Project, Project_gallery WHERE Project.pj_ID = Project_gallery.pj_ID"; 
                                            $pjg_qry=mysqli_query($dbconid,$pjg_sql); 
                                            while($pjg= mysqli_fetch_assoc($pjg_qry)) { 
                                                $pjImg="imgUpload/project_image/".$pjg['pj_image']; 
                                                ?> 
                                                <tr> 
                                                    <td>
                                                        <?php echo $count++; ?></td> 
                                                        <td><?php echo $pjg['pj_ID'];?></td> 
                                                        <td><img src="<?php echo $pjImg; ?>" alt="" style="width: 7rem; height: 7rem;"></td> 
                                                        <td><?php echo $pjg['pj_title'];?></td> 
                                                        <td> 
                                                            <a href="projgallery.php?pjgid=<?php echo $pjg['projg_ID'];?>">Edit</a> 
                                                            <a href="../DB/insert.php?projDelId=<?php echo $pjg['projg_ID'];?>">Delete</a>
                                                        </td> 
                                                    </tr> 
                                                    <?php } ?> 
                                                </tbody> 
                                            </table> 
                                        </div> 
                                    </div>
		</main>
		<!-- MAIN -->
	</section>
<script src="app.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
     <script>
    const imageInput = document.getElementById("imageInput");
    const previewImage = document.getElementById("previewImage");
    const previewLabel = document.getElementById("previewLabel");

    imageInput.addEventListener("change", function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = "block";
                previewLabel.style.display = "block";
            }

            reader.readAsDataURL(file);
        }
    });
    </script>
    <script>
    const imageInput1 = document.getElementById("imageInput");
    const previewImage1 = document.getElementById("newPreviewImage");
    const previewLabel1 = document.getElementById("previewLabel");
    const removeBtn = document.getElementById("removePreview");

    if (imageInput1) {

        imageInput1.addEventListener("change", function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImage1.src = e.target.result;
                    previewImage1.style.display = "block";
                    previewLabel1.style.display = "block";
                    removeBtn.style.display = "inline-block";
                }

                reader.readAsDataURL(file);
            }
        });

        removeBtn.addEventListener("click", function() {

            imageInput1.value = ""; // clear selected file
            previewImage1.src = "";
            previewImage1.style.display = "none";
            previewLabel1.style.display = "none";
            removeBtn.style.display = "none";

        });
    }
    </script>
</body>
</html>