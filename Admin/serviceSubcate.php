<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
    <style>
    .btn-edit {
        background: #ffc107;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: black;
        font-size: 13px;
    }

    .btn-edit:hover {
        background: #e0a800;
    }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>
    <!-- NAVBAR -->
    <section id="navbar">
        <?php include 'header.php'; ?>
        <!-- MAIN -->
        <main>
            <h1 class="title" style="color: #5F84A2;">Dashboard</h1>
            <ul class="breadcrumbs">
                <li><a href="dashboard.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="#" class="active">Service Subcategory</a></li>
            </ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['scateid'])){
                        $scateid=$_GET['scateid'];
                        $subcate_sql="SELECT * FROM Service_SubCategory ss LEFT JOIN Service_Category sc ON ss.sc_ID = sc.sc_ID WHERE subCate_ID ='$scateid'";
                        $subcate_qry=mysqli_query($dbconid,$subcate_sql); 
                        while($subcate_result= mysqli_fetch_assoc($subcate_qry))
                        {
                        ?>
                    <input type="hidden" name="scateid" value="<?php echo $subcate_result['subCate_ID'];?>">
                    <div class="inputfield">
                        <label>Service Category</label>
                        <select name="scid" class="input" required>

                            <?php
                            $current_scid = $subcate_result['sc_ID'];

                            $scate_sql="SELECT sc_ID, category_name FROM Service_Category";
                            $scate_qry=mysqli_query($dbconid,$scate_sql);

                            while($scate_result=mysqli_fetch_assoc($scate_qry)){

                                $scid = $scate_result['sc_ID'];
                                $scname = $scate_result['category_name'];

                                if($scid == $current_scid){
                                    echo "<option value='$scid' selected>$scname</option>";
                                }else{
                                    echo "<option value='$scid'>$scname</option>";
                                }
                            }
                            ?>

                        </select>
                    </div>
                    <div class="inputfield">
                        <label>SubCategory Name</label>
                        <input type="text" name="scname" class="input"
                            value="<?php echo $subcate_result['subCate_name'];?>">
                    </div>
                    <?php
                        $scateImg = "imgUpload/".$subcate_result['subCate_img'];
                        ?>
                    <div class="inputfield">
                        <label>Current Image</label><br>
                        <img id="currentImage" src="<?php echo $scateImg; ?>"
                            style="width:100px;height:100px;border:1px solid #ccc;">
                    </div>
                    <div class="inputfield">
                        <label>Upload New Image</label>
                        <input type="file" name="scimg" id="imageInput" accept="image/*" class="input">
                    </div>

                    <div class="inputfield">
                        <label id="previewLabel" style="display:none;">New Image Preview</label><br>
                        <img id="newPreviewImage" style="width:100px;height:100px;display:none;border:1px solid #ccc;">
                        <button type="button" id="removePreview" style="display:none;margin-top:5px;">
                            ❌ Remove
                        </button>
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="scdes" class="input"
                            value="<?php echo $subcate_result['subCate_description'];?>">
                    </div>

                    <div class="inputfield">
                        <input type="submit" name="subcateUpdate" value="Update" class="btn">
                    </div>
                    <?php
                        } }
                        else{
							?>
                    <div class="inputfield">
                        <label>Service Category</label>
                        <select name="scid" class="input" required>
                            <option value="" disabled selected>--------- Select Category ---------</option>
                            <?php 
                            $scate_sql="SELECT sc_ID, category_name FROM Service_Category";
                            $scate_qry=mysqli_query($dbconid,$scate_sql);
                            while($scate_result=mysqli_fetch_assoc($scate_qry))
                            {
                                $scid=$scate_result['sc_ID'];
                                $scname=$scate_result['category_name'];
                                echo "<option value='$scid'>$scname</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="inputfield">
                        <label>SubCategory Name</label>
                        <input type="text" name="scname" class="input" required>
                    </div>
                    <div class="inputfield">
                        <label>SubCategory Image</label>
                        <input type="file" name="scimg" id="imageInput" accept="image/*" class="input">
                    </div>

                    <div class="inputfield">
                        <label id="previewLabel" style="display: none;">Preview Image</label><br>
                        <img id="previewImage" style="width:100px;height:100px; display:none; border:1px solid #ccc;">
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="scdes" class="input">
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="subcate" value="Submit" class="btn">
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="table-data">
                <h3 class="table=title">Service Subcategory Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service SubCategory</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Service Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $count = 1;
                        $subCate_sql="SELECT * FROM Service_Category,Service_SubCategory WHERE Service_Category.sc_ID = Service_SubCategory.sc_ID ORDER BY subCate_ID DESC";               
                        $subcate_qry=mysqli_query($dbconid,$subCate_sql);                                        
                        while($subCate= mysqli_fetch_assoc($subcate_qry))
                        {
							$subcateImg="imgUpload/".$subCate['subCate_img'];
                        ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $subCate['subCate_name'];?></td>
                                <td><img src="<?php echo $subcateImg; ?>" alt="" style="width: 7rem; height: 7rem;">
                                </td>
                                <td><?php echo $subCate['subCate_description'];?></td>
                                <td><?php echo $subCate['category_name'];?></td>
                                <td>
                                    <a href="serviceSubcate.php?scateid=<?php echo $subCate['subCate_ID'];?>" class="btn-edit">
                                        <i class='bx bx-edit'></i>Edit</a>
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