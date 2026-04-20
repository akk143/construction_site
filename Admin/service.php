<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Admin Dashboard</title>
    <style>
    .table-container {
        width: 100%;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        min-width: 1200px;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

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
                <li><a href="#" class="active">Service</a></li>
            </ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['sid'])){
                        $sid=$_GET['sid'];
                        $service_sql="
                        SELECT *
                        FROM Service_tbl st
                        JOIN Service_SubCategory ss ON st.subCate_ID = ss.subCate_ID
                        JOIN Service_Category sc ON ss.sc_ID = sc.sc_ID
                        WHERE st.service_ID='$sid'
                        ";                        
                        $service_qry=mysqli_query($dbconid,$service_sql); 
                        while($service_result= mysqli_fetch_assoc($service_qry))
                        {
                        ?>
                    <input type="hidden" name="sid" value="<?php echo $service_result['service_ID'];?>">
                    <div class="inputfield">
                        <label>Service Category</label>
                        <select name="scid" id="scid" class="input" onchange="loadSubCategory(this.value)" required>
                            <option value="">-----Select Category-----</option>

                            <?php
                            $current_scid = $service_result['sc_ID'];

                            $cate_sql="SELECT sc_ID, category_name FROM Service_Category";
                            $cate_qry=mysqli_query($dbconid,$cate_sql);

                            while($cate_result=mysqli_fetch_assoc($cate_qry)){
                                $cid=$cate_result['sc_ID'];
                                $cname=$cate_result['category_name'];

                                if($cid == $current_scid){
                                    echo "<option value='$cid' selected>$cname</option>";
                                }else{
                                    echo "<option value='$cid'>$cname</option>";
                                }
                            }
                            ?>

                        </select>
                    </div>
                    <div class="inputfield">
                        <label>Service Subcategory</label>

                        <select name="subid" id="subcate" class="input" required>

                            <?php

                            $current_subid = $service_result['subCate_ID'];
                            $current_scid = $service_result['sc_ID'];

                            $sub_sql="SELECT * FROM Service_SubCategory WHERE sc_ID='$current_scid'";
                            $sub_qry=mysqli_query($dbconid,$sub_sql);

                            while($sub=mysqli_fetch_assoc($sub_qry)){

                                $subid = $sub['subCate_ID'];
                                $subname = $sub['subCate_name'];

                                if($subid == $current_subid){
                                    echo "<option value='$subid' selected>$subname</option>";
                                }else{
                                    echo "<option value='$subid'>$subname</option>";
                                }
                            }

                            ?>
                        </select>
                    </div>
                    <div class="inputfield">
                        <label>Service Name</label>
                        <input type="text" name="sname" value="<?php echo $service_result['servicename'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="sdes" value="<?php echo $service_result['service_description'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Detail</label>
                        <input type="text" name="sdetail" value="<?php echo $service_result['service_detail'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Content</label>
                        <input type="text" name="scontent" value="<?php echo $service_result['service_content'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Price</label>
                        <input type="int" name="sprice" value="<?php echo $service_result['service_price'];?>"
                            class="input">
                    </div>
                    <?php
                        $scateImg = "imgUpload/".$service_result['service_img'];
                        ?>

                    <div class="inputfield">
                        <label>Current Image</label><br>
                        <img id="currentImage" src="<?php echo $scateImg; ?>"
                            style="width:100px;height:100px;border:1px solid #ccc;">
                    </div>

                    <div class="inputfield">
                        <label>Upload New Image</label>
                        <input type="file" name="simg" id="imageInput" accept="image/*" class="input">
                    </div>

                    <div class="inputfield">
                        <label id="previewLabel" style="display:none;">New Image Preview</label><br>
                        <img id="newPreviewImage" style="width:100px;height:100px;display:none;border:1px solid #ccc;">
                        <button type="button" id="removePreview" style="display:none;margin-top:5px;">
                            ❌ Remove
                        </button>
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="serviceUpdate" value="Update" class="btn">
                    </div>
                    <?php
                        } }
                        else{
							?>
                    <div class="inputfield">
                        <label>Service Category</label>
                        <select name="scid" id="scid" class="input" onchange="loadSubCategory(this.value)" required>
                            <option value="">-----Select Category-----</option>

                            <?php
                            $cate_sql="SELECT sc_ID, category_name FROM Service_Category";
                            $cate_qry=mysqli_query($dbconid,$cate_sql);

                            while($cate_result=mysqli_fetch_assoc($cate_qry)){
                                $cid=$cate_result['sc_ID'];
                                $cname=$cate_result['category_name'];
                                echo "<option value='$cid'>$cname</option>";
                            }
                            ?>

                        </select>
                    </div>
                    <div class="inputfield">
                        <label>Service Subcategory</label>

                        <select name="subid" id="subcate" class="input" required>
                            <option value="">------Select Subcategory------</option>
                        </select>

                    </div>
                    <div class="inputfield">
                        <label>Service Name</label>
                        <input type="text" name="sname" class="input" required>
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="sdes" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Detail</label>
                        <input type="text" name="sdetail" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Content</label>
                        <input type="text" name="scontent" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Price</label>
                        <input type="number" name="sprice" class="input" min="1" required>
                    </div>
                    <div class="inputfield">
                        <label>Service Image</label>
                        <input type="file" name="simg" id="imageInput" accept="image/*" class="input">
                    </div>

                    <div class="inputfield">
                        <label id="previewLabel" style="display: none;">Preview Image</label><br>
                        <img id="previewImage" style="width:100px;height:100px; display:none; border:1px solid #ccc;">
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="serviceSubmit" value="Submit" class="btn">
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="table-data">
                <h3 class="table-title">Service Table</h3>
                <div class="table-container">
                    <div class="table-responsive">

                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Main Category</th>
                                    <th>Sub Category</th>
                                    <th>Service Name</th>
                                    <th>Description</th>
                                    <th>Detail</th>
                                    <th>Content</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $count=1;
                        $service_sql="SELECT * FROM Service_tbl LEFT JOIN Service_SubCategory ON Service_SubCategory.subCate_ID = Service_tbl.subCate_ID
                        LEFT JOIN service_category ON Service_SubCategory.sc_ID=service_category.sc_ID ORDER BY service_ID DESC";
                        $service_qry=mysqli_query($dbconid,$service_sql);
                        while($service_result= mysqli_fetch_assoc($service_qry))
                        {
							$serviceImg="imgUpload/".$service_result['service_img'];
                        ?>
                                <tr>
                                    <td><?php echo $count++;?></td>
                                    <td><?php echo $service_result['category_name'];?></td>
                                    <td><?php echo $service_result['subCate_name'];?></td>
                                    <td><?php echo $service_result['servicename'];?></td>
                                    <td><?php echo $service_result['service_description'];?></td>
                                    <td><?php echo $service_result['service_detail'];?></td>
                                    <td><?php echo $service_result['service_content'];?></td>
                                    <td><?php echo $service_result['service_price'];?></td>
                                    <td><img src="<?php echo $serviceImg;?>" alt="" style="width: 7rem; height: 7rem;">
                                    </td>
                                    <td>
                                        <a href="service.php?sid=<?php echo $service_result['service_ID'];?>" class="btn-edit">
                                            <i class='bx bx-edit'></i>Edit</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>

                        </table>
                    </div>
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

    function loadSubCategory(cate_id) {

        var xhr = new XMLHttpRequest();

        xhr.open("GET", "getSubcategory.php?cid=" + cate_id, true);

        xhr.onload = function() {
            if (this.status == 200) {
                document.getElementById("subcate").innerHTML = this.responseText;
            }
        };

        xhr.send();
    }
    </script>
    <script>
    window.addEventListener("pageshow", function(event) {

        if (event.persisted || window.performance.navigation.type === 2) {

            // reset category
            const category = document.getElementById("scid");
            if (category) {
                category.selectedIndex = 0;
            }

            // reset subcategory
            const subcate = document.getElementById("subcate");
            if (subcate) {
                subcate.innerHTML = "<option value=''>------Select Subcategory------</option>";
            }

        }

    });
    </script>

</body>

</html>