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
			<h1 class="title" style="color: #5F84A2;">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="dashboard.php">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Gallery</a></li>
			</ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['gid'])){
                        $gid=$_GET['gid'];
                        $gallery_sql="SELECT * FROM Gallery WHERE GID='$gid'";
                        $gallery_qry=mysqli_query($dbconid,$gallery_sql); 
                        while($gallery_result= mysqli_fetch_assoc($gallery_qry))
                        {
                        ?>
                        <input type="hidden" name="gid" value="<?php echo $gallery_result['GID'];?>">
						<div class="inputfield">
                        <label>Image</label>
                        <input type="file" name="gimg" accept="../img/*" class="input">
                        </div>          
                        <div class="inputfield">
                            <label>Description</label>
                            <input type="text" name="gdes" class="input" value="<?php echo $gallery_result['Gdescription'];?>">
                        </div>         
                       <div class="inputfield">
                            <input type="submit" name="galleryUpdate" value="Update" class="btn">
                        </div>
                        <?php
                        } }
                        else{
							?> 
                    <div class="inputfield">
                        <label>Image</label>
                        <input type="file" name="gimg" accept="../img/*" class="input">
                    </div>          
					<div class="inputfield">
                        <label>Description</label>
                        <input type="text" name="gdes" class="input">
                    </div>                          
                    <div class="inputfield">
                        <input type="submit" name="gallerySubmit" value="Submit" class="btn">
                    </div>
                     <?php } ?>
                </form>
            </div>	
			<div class="table-data">
				<h3 class="table=title">Gallery Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
								<th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $gallery_sql="SELECT * FROM Gallery";               
                        $gallery_qry=mysqli_query($dbconid,$gallery_sql);                                        
                        while($gallery= mysqli_fetch_assoc($gallery_qry))
                        {
							$gImg="imgUpload/".$gallery['GImage'];
                        ?>
                                <tr>
                                    <td><?php echo $gallery['GID'];?></td>                                     
									<td><img src="<?php echo $gImg; ?>" alt="" style="width: 7rem; height: 7rem;"></td>
									<td><?php echo $gallery['Gdescription'];?></td>
                                    <td>
                                        <a href="gallery.php?gid=<?php echo $gallery['GID'];?>">Edit</a>
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
    
</body>
</html>