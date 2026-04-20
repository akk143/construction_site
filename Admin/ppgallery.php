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
			<h1 class="title" style="color: #5F84A2;">Property Gallery</h1>
            <?php
                include_once("../DB/connection.php");
                if(isset($_GET['pgid'])){
                    $pgid=$_GET['pgid'];
                    $pg_sql="SELECT * FROM Property_gallery WHERE pg_ID='$pgid'";
                    $pg_qry=mysqli_query($dbconid,$pg_sql); 
                    while($pg_result= mysqli_fetch_assoc($pg_qry))
                    {
                        $pgImg = "imgUpload/property_image/".$pg_result['property_image'];
                    ?>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="pgid" value="<?php echo $pg_result['pg_ID'];?>"> 
                    <div class="inputfield">
                        <label>Current Image</label><br>
                        <img id="currentImage" src="<?php echo $pgImg; ?>" style="width:100px;height:100px;border:1px solid #ccc;"> 
                    </div>
                    <div class="inputfield"> 
                        <label>Upload New Image</label> 
                        <input type="file" name="pgimg" id="imageInput" accept="image/*" class="input"> 
                    </div> 
                    <div class="inputfield"> 
                        <label id="previewLabel" style="display:none;">New Image Preview</label><br> 
                        <img id="newPreviewImage" style="width:100px;height:100px;display:none;border:1px solid #ccc;"> 
                        <button type="button" id="removePreview" style="display:none;margin-top:5px;"> ❌ Remove </button> 
                    </div> 
                    <div class="inputfield"> 
                        <label>Property Name</label> 
                        <select name="pid" class="input" required>
                            <?php 
                            $current_pid = $pg_result['property_ID']; 
                            $property_sql="SELECT property_ID, property_name FROM Property"; 
                            $property_qry=mysqli_query($dbconid,$property_sql);
                            while($property_result=mysqli_fetch_assoc($property_qry))
                            {
                                $pid=$property_result['property_ID'];
                                $pname=$property_result['property_name'];
                                if($pid == $current_pid){ 
                                    echo "<option value='$pid' selected>$pname</option>"; 
                                    }
                                    else{ 
                                        echo "<option value='$pid'>$pname</option>"; } } ?> 
                        </select> 
                    </div> 
                    <div class="inputfield"> 
                        <input type="submit" name="pgUpdate" value="Update" class="btn">
                    </div> 
                    <div class="inputfield"> 
                        <a href="ppgallery.php" class="btn" style="text-align: center;">Cancel Update</a>
                    </div>
                    </form> 
                </div>                 
                <?php 
                } 
                } 
                ?>               
                    <div class="table-data"> 
                        <div class="table-container"> 
                            <table> 
                                <thead> 
                                    <tr> 
                                        <th>ID</th> 
                                        <th>Property ID</th> 
                                        <th>Image</th> 
                                        <th>Property Name</th> 
                                        <th>Action</th> 
                                    </tr> 
                                </thead> 
                                <tbody> 
                                <?php 
                                    include_once("../DB/connection.php"); 
                                    $count=1;
                                    $pg_sql="SELECT * FROM Property_gallery, Property
                                    WHERE Property.property_ID = Property_gallery.property_ID";               
                                    $pg_qry=mysqli_query($dbconid,$pg_sql);                                        
                                    while($pg= mysqli_fetch_assoc($pg_qry))
                                    {
							            $pgImg="imgUpload/property_image/".$pg['property_image'];
                                    ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>   
                                    <td><?php echo $pg['property_ID'];?></td>                                     
									<td><img src="<?php echo $pgImg; ?>" alt="" style="width: 7rem; height: 7rem;"></td>
									<td><?php echo $pg['property_name'];?></td>
                                    <td>
                                        <a href="ppgallery.php?pgid=<?php echo $pg['pg_ID'];?>">Edit</a>
                                        <a href="../DB/insert.php?pgDelId=<?php echo $pg['pg_ID'];?>">Delete</a>
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