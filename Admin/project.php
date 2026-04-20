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

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        color: white;
        font-size: 13px;
    }

    .bg-success {
        background-color: #28a745;
    }

    .bg-primary {
        background-color: #007bff;
    }

    .btn-edit {
        background: #ffc107;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: black;
        font-size: 13px;
        margin-left: 5px;
    }

    .btn-edit:hover {
        background: #e0a800;
    }

    .btn-edit.disabled {
        background: #bfbfbf;
        cursor: not-allowed;
    }

    .btn-complete {
        background: #28a745;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 13px;
    }

    .btn-complete:hover {
        background: #218838;
    }

    .btn-complete.disabled {
        background: #9e9e9e;
        cursor: not-allowed;
    }

    .btn-delete {
        background: #dc3545;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 13px;
        margin-left: 5px;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-delete.disabled {
        background: #9e9e9e;
        cursor: not-allowed;
    }

    .image-thumbnails {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .existing-image-block {
        position: relative;
        width: 100px;
        height: 100px;
        border: 1px solid #d8e0eb;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #fff;
    }

    .existing-image-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .existing-thumb {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .btn-remove-existing {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.95);
        color: white;
        font-weight: bold;
        cursor: pointer;
        line-height: 20px;
        text-align: center;
        padding: 0;
        z-index: 10;
        transition: background-color 0.2s;
    }

    .btn-remove-existing:hover {
        background: rgba(200, 35, 50, 1);
    }

    .thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #ddd;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .thumbnail:hover {
        transform: scale(1.1);
    }

    .more-images {
        font-size: 12px;
        color: #666;
        margin-left: 5px;
    }
    .image-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .image-row input[type="file"] {
        flex: 1;
    }

    .preview {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    .btn-add {
        background: #28a745;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-remove {
        background: #dc3545;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
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
                <li><a href="index.php">Home</a></li>
                <li class="divider">/</li>
                <li><a href="project.php" class="active">Project</a></li>
            </ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['projid'])){
                        $projid = intval($_GET['projid']);
                        $proj_sql = "SELECT * FROM Project WHERE pj_ID='$projid' LIMIT 1";
                        $proj_qry = mysqli_query($dbconid, $proj_sql);
                        $proj_result = mysqli_fetch_assoc($proj_qry);

                        $gallery_items = [];
                        if ($proj_result) {
                            $gallery_sql = "SELECT projg_ID, pj_image FROM project_gallery WHERE pj_ID='$projid'";
                            $gallery_qry = mysqli_query($dbconid, $gallery_sql);
                            while ($img = mysqli_fetch_assoc($gallery_qry)) {
                                $gallery_items[] = $img;
                            }
                        }

                        if ($proj_result) {
                        ?>
                    <input type="hidden" name="projid" value="<?php echo $proj_result['pj_ID'];?>">
                    <div class="inputfield">
                        <label>Project Title</label>
                        <input type="text" name="ptitle" class="input" value="<?php echo $proj_result['pj_title'];?>">
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="pdes" class="input"
                            value="<?php echo $proj_result['pj_description'];?>">
                    </div>
                    <div class="inputfield">
                        <label>Detail</label>
                        <input type="text" name="pdetail" class="input" value="<?php echo $proj_result['pj_detail'];?>">
                    </div>

                    <div class="inputfield">
                        <label>Existing Images</label>
                        <div id="existing-images" class="image-thumbnails">
                            <?php foreach($gallery_items as $item): ?>
                                <div class="existing-image-block" data-gallery-id="<?= $item['projg_ID'] ?>">
                                    <img src="../Admin/imgUpload/project_image/<?= htmlspecialchars($item['pj_image']) ?>" class="existing-thumb" alt="Project image">
                                    <button type="button" class="btn-remove-existing" aria-label="Remove image">&times;</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="remove_images" id="remove_images" value="">
                    </div>

                    <div class="inputfield">
                        <label>New Images</label>
                        <div id="image-container">
                            <div class="image-row">
                                <input type="file" name="pimg[]" accept="image/*" class="image-input">
                                <img class="preview" style="display:none;">
                                <button type="button" class="btn-add">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="inputfield">
                        <input type="submit" name="projUpdate" value="Update" class="btn">
                    </div>
                    <?php
                        } }
                        else{
							?>
                    <div class="inputfield">
                        <label>Project Title</label>
                        <input type="text" name="ptitle" class="input" required>
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="pdes" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Detail</label>
                        <input type="text" name="pdetail" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Project Images</label>

                        <div id="image-container">

                            <div class="image-row">
                                <input type="file" name="pimg[]" accept="image/*" required class="image-input">
                                <img class="preview" style="display:none;">

                                <button type="button" class="btn-add">+</button>

                            </div>

                        </div>

                        <!-- <small class="image-note">At least one image is required</small> -->
                    </div>
                    <div class="inputfield">
                        <input type="submit" name="projSubmit" value="Submit" class="btn">
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="table-data">
                <h3 class="table=title">Projects</h3>
                <div class="table-container">
                    <div class="table-responsive">

                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Project Name</th>
                                    <th>Description</th>
                                    <th>Detail</th>
                                    <th>Project Status</th>
                                    <th>Images</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $count=1;
                        $proj_sql="SELECT * FROM Project ORDER BY pj_ID DESC";               
                        $proj_qry=mysqli_query($dbconid,$proj_sql);                                        
                        while($proj= mysqli_fetch_assoc($proj_qry))
                        {
                            // Fetch images for this project
                            $pjid = $proj['pj_ID'];
                            $images_sql = "SELECT pj_image FROM project_gallery WHERE pj_ID = '$pjid'";
                            $images_qry = mysqli_query($dbconid, $images_sql);
                            $images = [];
                            while($img = mysqli_fetch_assoc($images_qry)) {
                                $images[] = $img['pj_image'];
                            }
                            $total_images = count($images);
                            $display_images = array_slice($images, 0, 3);
                        ?>
                                <tr>
                                    <td><?php echo $count++;?></td>
                                    <td><?php echo $proj['pj_title'];?></td>
                                    <td><?php echo $proj['pj_description'];?></td>
                                    <td><?php echo $proj['pj_detail'];?></td>
                                    <td>
                                        <?php
                                    $status = $proj['p_status'];

                                    if($status == "ongoing"){
                                        echo "<span class='badge bg-success'>Ongoing</span>";
                                    }
                                    elseif($status == "completed"){
                                        echo "<span class='badge bg-primary'>Completed</span>";
                                    }
                                    ?>
                                    </td>

                                    <td>
                                        <div class="image-thumbnails">
                                            <?php
                                            foreach($display_images as $img) {
                                                echo "<a href='./imgUpload/project_image/$img' target='_blank'><img src='./imgUpload/project_image/$img' class='thumbnail' alt='Project Image'></a>";
                                            }
                                            if($total_images > 3) {
                                                echo "<span class='more-images'>+ " . ($total_images - 3) . " more</span>";
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td>

                                        <?php if($proj['p_status'] == "ongoing"){ ?>

                                        <a href="update_project.php?completeid=<?php echo $proj['pj_ID'];?>"
                                            class="btn-complete">
                                            <i class='bx bx-check'></i> Completed
                                        </a>

                                        <a href="project.php?projid=<?php echo $proj['pj_ID'];?>" class="btn-edit">
                                            <i class='bx bx-edit'></i> Edit
                                        </a>

                                        <?php } else { ?>

                                        <button class="btn-complete disabled" disabled>
                                            <i class='bx bx-check'></i> Completed
                                        </button>

                                        <button class="btn-edit disabled" disabled>
                                            <i class='bx bx-edit'></i> Edit
                                        </button>

                                        <?php } ?>

                                        <?php
                                    $pjid = $proj['pj_ID'];

                                    // check feedback existence
                                    $check_feedback = "SELECT * FROM Feedback WHERE pj_ID='$pjid'";
                                    $feedback_qry = mysqli_query($dbconid,$check_feedback);
                                    $hasFeedback = mysqli_num_rows($feedback_qry) > 0;
                                    ?>

                                        <?php if($proj['p_status'] == "completed" || $hasFeedback){ ?>

                                        <button class="btn-delete disabled" disabled>
                                            <i class='bx bx-trash'></i> Delete
                                        </button>

                                        <?php } else { ?>

                                        <a href="delete_project.php?deleteid=<?php echo $proj['pj_ID'];?>"
                                            class="btn-delete"
                                            onclick="return confirm('Are you sure you want to delete this project?');">
                                            <i class='bx bx-trash'></i> Delete
                                        </a>

                                        <?php } ?>

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
    const removeIds = new Set();

    document.addEventListener('change', function(e) {
        if (e.target.matches('.image-input')) {
            const file = e.target.files[0];
            const preview = e.target.parentElement.querySelector('.preview');

            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSize = 2 * 1024 * 1024;

            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG, PNG, GIF images are allowed.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }

            if (file.size > maxSize) {
                alert('Image must be smaller than 2MB.');
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    document.addEventListener('click', function(e) {
        const container = document.getElementById('image-container');

        if (e.target.matches('.btn-add')) {
            const row = document.createElement('div');
            row.className = 'image-row';
            row.innerHTML = `
                <input type="file" name="pimg[]" accept="image/*" class="image-input">
                <img class="preview" style="display:none;">
                <button type="button" class="btn-remove">-</button>
            `;
            container.appendChild(row);
            return;
        }

        if (e.target.matches('.btn-remove')) {
            const rows = container.querySelectorAll('.image-row');
            if (rows.length > 1) {
                e.target.closest('.image-row').remove();
            } else {
                alert('At least one image field is required.');
            }
            return;
        }

        if (e.target.matches('.btn-remove-existing')) {
            const block = e.target.closest('.existing-image-block');
            if (!block) return;
            const pgid = block.getAttribute('data-gallery-id');
            if (!pgid) return;

            removeIds.add(pgid);
            document.getElementById('remove_images').value = Array.from(removeIds).join(',');
            block.remove();
        }
    });
    </script>

</body>

</html>