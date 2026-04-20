<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Manage Property</title>
    <style>
    .table-container {
        width: 100%;
    }

    /* new: responsive wrapper */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        /* allow horizontal scrolling on small screens by keeping a reasonable min-width */
        min-width: 900px;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        white-space: nowrap;
    }

    /* optional: make table more compact on very small screens */
    @media (max-width: 480px) {

        th,
        td {
            padding: 8px;
            font-size: 13px;
        }
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

    .inputfield label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .inputfield select,
    .inputfield input[type="text"],
    .inputfield input[type="int"],
    .inputfield input[type="number"] {
        width: 100%;
        max-width: 100%;
        min-width: 0;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .image-thumbnails {
        display: flex;
        gap: 10px;
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
                <li><a href="#">Home</a></li>
                <li class="divider">/</li>
                <li><a href="property.php" class="active">Property</a></li>
            </ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['pid'])){
                        $pid=$_GET['pid'];
                        $property_sql="SELECT * FROM Property WHERE property_ID='$pid'";
                        $property_qry=mysqli_query($dbconid,$property_sql); 
                        while($property_result= mysqli_fetch_assoc($property_qry))
                        {
                        ?>
                    <input type="hidden" name="pid" value="<?php echo $property_result['property_ID'];?>">
                    <div class="inputfield">
                        <label>Property Type</label>
                        <select name="ptypeid" class="input">
                            <?php 
                            $currentPtypeId = $property_result['pt_ID'];
                            $ptype_sql="SELECT pt_ID, ptype FROM Property_type";
                            $ptype_qry=mysqli_query($dbconid,$ptype_sql);
                            while($ptype_result=mysqli_fetch_assoc($ptype_qry))
                            {
                                $ptid=$ptype_result['pt_ID'];
                                $ptype=$ptype_result['ptype'];
                                $selected = ($ptid == $currentPtypeId) ? ' selected' : '';
                                echo "<option value='$ptid'$selected>$ptype</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <input type="hidden" name="status" value="<?php echo htmlspecialchars($property_result['property_status'], ENT_QUOTES); ?>">
                    <div class="inputfield">
                        <label>Property Name</label>
                        <input type="text" name="pname" value="<?php echo $property_result['property_name'];?>"
                            class="input" required>
                    </div>
                    <div class="inputfield">
                        <label>Property Profile</label>
                        <input type="file" name="pprofile" accept="image/*">
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="description"
                            value="<?php echo $property_result['property_description'];?>" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Location</label>
                        <input type="text" name="location" value="<?php echo $property_result['property_location'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Price</label>
                        <input type="int" name="price" value="<?php echo $property_result['property_price'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Area</label>
                        <input type="text" name="area" value="<?php echo $property_result['property_area'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>No of bedroom</label>
                        <input type="number" name="bedroom" value="<?php echo $property_result['no_of_bedroom'];?>"
                            class="input" min="1" max="10">
                    </div>
                    <div class="inputfield">
                        <label>No of bathroom</label>
                        <input type="number" name="bathroom" value="<?php echo $property_result['no_of_bathroom'];?>"
                            class="input" min="1" max="10">
                    </div>
                    <div class="inputfield">
                        <label>Built year</label>
                        <input type="int" name="builtYear" value="<?php echo $property_result['built_year'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Land Size</label>
                        <input type="text" name="landSize" value="<?php echo $property_result['land_size'];?>"
                            class="input">
                    </div>
                    <div class="inputfield">
                        <label>Existing Images</label>
                        <div id="existing-images" class="image-thumbnails">
                            <?php
                            $gallery_items = [];
                            $gallery_sql = "SELECT pg_ID, property_image FROM Property_gallery WHERE property_ID='$pid'";
                            $gallery_qry = mysqli_query($dbconid, $gallery_sql);
                            while ($img = mysqli_fetch_assoc($gallery_qry)) {
                                $gallery_items[] = $img;
                            }
                            foreach ($gallery_items as $item) : ?>
                            <div class="existing-image-block" data-gallery-id="<?= $item['pg_ID'] ?>">
                                <img src="../Admin/imgUpload/property_image/<?= htmlspecialchars($item['property_image']) ?>"
                                    class="existing-thumb" alt="Property image">
                                <button type="button" class="btn-remove-existing"
                                    aria-label="Remove image">&times;</button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="remove_images" id="remove_images" value="">
                    </div>

                    <div class="inputfield">
                        <label>New Images</label>
                        <div id="image-container">
                            <div class="image-row">
                                <input type="file" name="ppimage[]" accept="image/*" class="image-input">
                                <img class="preview" style="display:none;">
                                <button type="button" class="btn-add">+</button>
                            </div>
                        </div>
                    </div>


                    <div class="inputfield">
                        <input type="submit" name="propertyUpdate" value="Update" class="btn">
                    </div>
                    <?php
                        } }
                        else{
							?>
                    <div class="inputfield">
                        <label>Property Type</label>
                        <select name="ptypeid" class="input">
                            <?php 
                            $ptype_sql="SELECT pt_ID, ptype FROM Property_type";
                            $ptype_qry=mysqli_query($dbconid,$ptype_sql);
                            while($ptype_result=mysqli_fetch_assoc($ptype_qry))
                            {
                                $ptid=$ptype_result['pt_ID'];
                                $ptype=$ptype_result['ptype'];
                                echo "<option value='$ptid'>$ptype</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="inputfield">
                        <label>Property Name</label>
                        <input type="text" name="pname" class="input" required>
                    </div>
                    <div class="inputfield">
                        <label>Property Profile</label>
                        <input type="file" name="pprofile" accept="image/*">
                    </div>
                    <div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="description" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Location</label>
                        <input type="text" name="location" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Price</label>
                        <input type="int" name="price" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Area</label>
                        <input type="text" name="area" class="input">
                    </div>
                    <div class="inputfield">
                        <label>No of bedroom</label>
                        <input type="number" name="bedroom" class="input" min="1" max="10">
                    </div>
                    <div class="inputfield">
                        <label>No of bathroom</label>
                        <input type="number" name="bathroom" class="input" min="1" max="10">
                    </div>
                    <div class="inputfield">
                        <label>Built year</label>
                        <input type="int" name="builtYear" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Land Size</label>
                        <input type="text" name="landSize" class="input">
                    </div>
                    <div class="inputfield">
                        <label>Property Images</label>
                        <div id="image-container">
                            <div class="image-row">
                                <input type="file" name="ppimage[]" accept="image/*" required class="image-input">
                                <img class="preview" style="display:none;">
                                <button type="button" class="btn-add">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="inputfield">
                        <input type="submit" name="property" value="Submit" class="btn">
                    </div>
                    <?php } ?>
                </form>
            </div>
            <div class="table-data">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Profile</th>
                                    <th class="desktop-only">Location</th>
                                    <th>Images</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                            $count=1;
                            $property_sql="SELECT * FROM Property_type,Property WHERE Property_type.pt_ID=Property.pt_ID 
                            ORDER BY Property.property_ID DESC";               
                            $property_qry=mysqli_query($dbconid,$property_sql);                                        
                            while($property= mysqli_fetch_assoc($property_qry))
                            {
                                $propertyImg="imgUpload/".$property['property_profile'];
                                // prepare safe attributes
                                $pname = htmlspecialchars($property['property_name'], ENT_QUOTES);
                                $pname = htmlspecialchars($property['property_name'], ENT_QUOTES);
                                $pdesc = htmlspecialchars($property['property_description'], ENT_QUOTES);
                                $ploc  = htmlspecialchars($property['property_location'], ENT_QUOTES);
                                $ptype = htmlspecialchars($property['ptype'], ENT_QUOTES);
                                $pid   = (int)$property['property_ID'];
                                $price = htmlspecialchars($property['property_price'], ENT_QUOTES);
                                $area  = htmlspecialchars($property['property_area'], ENT_QUOTES);
                                $bed   = htmlspecialchars($property['no_of_bedroom'], ENT_QUOTES);
                                $bath  = htmlspecialchars($property['no_of_bathroom'], ENT_QUOTES);
                                $built = htmlspecialchars($property['built_year'], ENT_QUOTES);
                                $land  = htmlspecialchars($property['land_size'], ENT_QUOTES);
                                $status= htmlspecialchars($property['property_status'], ENT_QUOTES);

                                $images_sql = "SELECT property_image FROM Property_gallery WHERE property_ID = '$pid'";
                                $images_qry = mysqli_query($dbconid, $images_sql);
                                $images = [];
                                while($img = mysqli_fetch_assoc($images_qry)) {
                                    $images[] = $img['property_image'];
                                }
                                $total_images = count($images);
                                $display_images = array_slice($images, 0, 3);
                            ?>
                                <tr>
                                    <td><?php echo $count++;?></td>
                                    <td>
                                        <strong><?php echo $pname;?></strong>
                                        <!-- <div class="text-muted small desktop-only"><?php echo $pdesc ? (strlen($pdesc) > 80 ? substr($pdesc,0,80).'...' : $pdesc) : '';?></div> -->
                                    </td>
                                    <td><img src="<?php echo $propertyImg; ?>" alt="" style="width: 3rem; height: 3rem; border-radius:7px;"></td>
                                    <td class="desktop-only"><?php echo $ploc;?></td>
                                    <td>
                                        <div class="image-thumbnails">
                                            <?php
                                                foreach($display_images as $img) {
                                                    echo "<a href='./imgUpload/property_image/$img' target='_blank'><img src='./imgUpload/property_image/$img' class='thumbnail' alt='Property Image'></a>";
                                                }
                                                if($total_images > 3) {
                                                    echo "<span class='more-images'>+ " . ($total_images - 3) . " more</span>";
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td><?php echo $price;?></td>
                                    <td>
                                        <?php if($status == "Available"){ ?>
                                        <span class="badge badge-round text-white bg-success">Available</span>
                                        <?php } else { ?>
                                        <span class="badge badge-round text-white bg-secondary">Sold Out</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <!-- Details button -> launches modal and passes data -->
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                            data-bs-toggle="modal" data-bs-target="#propertyModal"
                                            data-id="<?php echo $pid;?>" data-name="<?php echo $pname;?>"
                                            data-desc="<?php echo $pdesc;?>" data-location="<?php echo $ploc;?>"
                                            data-price="<?php echo $price;?>" data-area="<?php echo $area;?>"
                                            data-bed="<?php echo $bed;?>" data-bath="<?php echo $bath;?>"
                                            data-built="<?php echo $built;?>" data-land="<?php echo $land;?>"
                                            data-ptype="<?php echo $ptype;?>" data-status="<?php echo $status;?>">
                                            <i class="bx bx-info-circle"></i> Details
                                        </button>

                                        <!-- Edit -->


                                        <!-- Sold Out / disabled if already sold -->
                                        <?php if($status == "Available"){ ?>
                                        <a href="property.php?pid=<?php echo $pid;?>"
                                            class="btn btn-sm btn-warning text-dark me-1">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <a href="update_property.php?sold_id=<?php echo $pid;?>"
                                            class="btn btn-sm btn-success me-1">
                                            <i class="bx bx-check"></i> Mark Sold
                                        </a>
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-success disabled me-1" disabled><i
                                                class="bx bx-check"></i></button>
                                        <?php } ?>

                                        <!-- Delete (confirm) -->
                                        <?php if($status != "Sold Out"){ ?>
                                        <a href="delete_property.php?deleteid=<?php echo $pid;?>"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this property?');">
                                            <i class="bx bx-trash"></i>
                                        </a>
                                        <?php } else { ?>
                                        <button class="btn btn-sm btn-danger disabled" disabled><i
                                                class="bx bx-trash"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!-- /.table-responsive -->
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- Property Details Modal -->
    <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="propertyModalLabel">Property Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <h4 id="modalName"></h4>
                            <p id="modalDesc" class="text-muted"></p>
                            <ul class="list-unstyled">
                                <li><strong>Location:</strong> <span id="modalLocation"></span></li>
                                <li><strong>Price:</strong> <span id="modalPrice"></span></li>
                                <li><strong>Area:</strong> <span id="modalArea"></span></li>
                                <li><strong>Bedrooms:</strong> <span id="modalBed"></span></li>
                                <li><strong>Bathrooms:</strong> <span id="modalBath"></span></li>
                                <li><strong>Built Year:</strong> <span id="modalBuilt"></span></li>
                                <li><strong>Land Size:</strong> <span id="modalLand"></span></li>
                                <li><strong>Type:</strong> <span id="modalPType"></span></li>
                                <li><strong>Status:</strong> <span id="modalStatus"></span></li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <a id="modalEditBtn" href="#" class="btn btn-warning">Edit</a> -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
                <input type="file" name="ppimage[]" accept="image/*" class="image-input">
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

<!-- Bootstrap + optional jQuery (Bootstrap 5 not required) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// populate modal from data-* attributes
var propertyModalEl = document.getElementById('propertyModal');
propertyModalEl.addEventListener('show.bs.modal', function(event) {
    var button = event.relatedTarget; // Button that triggered the modal
    if (!button) return;
    // read data
    var id = button.getAttribute('data-id');
    var name = button.getAttribute('data-name') || '';
    var desc = button.getAttribute('data-desc') || '';
    var loc = button.getAttribute('data-location') || '';
    var price = button.getAttribute('data-price') || '';
    var area = button.getAttribute('data-area') || '';
    var bed = button.getAttribute('data-bed') || '';
    var bath = button.getAttribute('data-bath') || '';
    var built = button.getAttribute('data-built') || '';
    var land = button.getAttribute('data-land') || '';
    var ptype = button.getAttribute('data-ptype') || '';
    var status = button.getAttribute('data-status') || '';

    // set modal fields
    document.getElementById('modalName').textContent = name;
    document.getElementById('modalDesc').textContent = desc;
    document.getElementById('modalLocation').textContent = loc;
    document.getElementById('modalPrice').textContent = price;
    document.getElementById('modalArea').textContent = area;
    document.getElementById('modalBed').textContent = bed;
    document.getElementById('modalBath').textContent = bath;
    document.getElementById('modalBuilt').textContent = built;
    document.getElementById('modalLand').textContent = land;
    document.getElementById('modalPType').textContent = ptype;
    document.getElementById('modalStatus').textContent = status;

    // link edit button directly to edit page
    document.getElementById('modalEditBtn').href = 'property.php?pid=' + encodeURIComponent(id);

    // image: you can set src to actual path if you store image paths in DB (not shown here)
    document.getElementById('modalImage').src = 'assets/img/placeholder.png';
});
</script>

<script src="app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="script.js"></script>
<script>
document.addEventListener("change", function(e) {

    if (e.target.classList.contains("image-input")) {

        const file = e.target.files[0];
        const preview = e.target.parentElement.querySelector(".preview");

        if (file) {

            const reader = new FileReader();

            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = "block";
            }

            reader.readAsDataURL(file);
        }

    }

});


document.addEventListener("click", function(e) {

    const container = document.getElementById("image-container");

    // ADD IMAGE
    if (e.target.classList.contains("btn-add")) {

        const row = document.createElement("div");
        row.classList.add("image-row");

        row.innerHTML = `
            <input type="file" name="ppimage[]" accept="image/*" required class="image-input">
                        <img class="preview" style="display:none;">

            <button type="button" class="btn-remove">-</button>
        `;

        container.appendChild(row);
    }

    // REMOVE IMAGE
    if (e.target.classList.contains("btn-remove")) {

        const rows = document.querySelectorAll(".image-row");

        if (rows.length > 1) {
            e.target.parentElement.remove();
        } else {
            alert("At least one image is required.");
        }

    }

});
</script>

</body>

</html>