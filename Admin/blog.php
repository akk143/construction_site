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
				<li><a href="#" class="active">Blog</a></li>
			</ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['bid'])){
                        $bid=$_GET['bid'];
                        $blog_sql="SELECT * FROM Blog WHERE blog_ID='$bid'";
                        $blog_qry=mysqli_query($dbconid,$blog_sql); 
                        while($blog_result= mysqli_fetch_assoc($blog_qry))
                        {
                        ?>
                        <input type="hidden" name="bid" value="<?php echo $blog_result['blog_ID'];?>">         
						<div class="inputfield">
                        <label>Blog Title</label>
                        <input type="text" name="btitle" class="input" value="<?php echo $blog_result['blog_title'];?>">
                    </div>    
                    <div class="inputfield">
                        <label>Content</label>
                        <input type="text" name="bcontent" class="input" value="<?php echo $blog_result['blog_content'];?>">
                    </div>        
					<div class="inputfield">
                        <label>Blog Image</label>
						<input type="file" name="bimg" accept="../img/*" class="input">
                    </div>
					 <div class="inputfield">
                        <label>Service</label>
                        <select name="sid" class="input">	
                            <?php 
                            $service_sql="SELECT service_ID, servicename FROM Service_tbl";
                            $service_qry=mysqli_query($dbconid,$service_sql);
                            while($service_result=mysqli_fetch_assoc($service_qry))
                            {
                                $sid=$service_result['service_ID'];
                                $sname=$service_result['servicename'];
                                echo "<option value='$sid'>$sname</option>";
                            }
                            ?>
                            </select>
                        </div>     
                       <div class="inputfield">
                            <input type="submit" name="blogUpdate" value="Update" class="btn">
                        </div>
                        <?php
                        } }
                        else{
							?> 
                    <div class="inputfield">
                        <label>Blog Title</label>
                        <input type="text" name="btitle" class="input">
                    </div>    
                    <div class="inputfield">
                        <label>Content</label>
                        <input type="text" name="bcontent" class="input">
                    </div>        
					<div class="inputfield">
                        <label>Blog Image</label>
						<input type="file" name="bimg" accept="../img/*" class="input">
                    </div>
					 <div class="inputfield">
                        <label>Service</label>
                        <select name="sid" class="input">	
                            <?php 
                            $service_sql="SELECT service_ID, servicename FROM Service_tbl";
                            $service_qry=mysqli_query($dbconid,$service_sql);
                            while($service_result=mysqli_fetch_assoc($service_qry))
                            {
                                $sid=$service_result['service_ID'];
                                $sname=$service_result['servicename'];
                                echo "<option value='$sid'>$sname</option>";
                            }
                            ?>
                            </select>
                        </div>                             
                    <div class="inputfield">
                        <input type="submit" name="blogSubmit" value="Submit" class="btn">
                    </div>
                     <?php } ?>
                </form>
            </div>	
			<div class="table-data">
				<h3 class="table=title">Blog Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Blog Title</th>
								<th>Content</th>
								<th>Image</th>
                                <th>Uploaded Date</th>
                                <th>Service</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $blog_sql="SELECT * FROM Blog, Service_tbl WHERE Service_tbl.service_ID=Blog.service_ID";               
                        $blog_qry=mysqli_query($dbconid,$blog_sql);                                        
                        while($blog= mysqli_fetch_assoc($blog_qry))
                        {
							$blogImg="imgUpload/".$blog['blog_img'];
                        ?>
                                <tr>
                                    <td><?php echo $blog['blog_ID'];?></td> 
                                    <td><?php echo $blog['blog_title'];?></td> 
                                    <td><?php echo $blog['blog_content'];?></td> 
									<td><img src="<?php echo $blogImg; ?>" alt="" style="width: 7rem; height: 7rem;"></td>
									<td><?php echo $blog['blog_date'];?></td> 
                                    <td><?php echo $blog['servicename'];?></td> 
                                    <td>
                                        <a href="blog.php?bid=<?php echo $blog['blog_ID'];?>">Edit</a>
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