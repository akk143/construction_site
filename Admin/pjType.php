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
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Property Type</a></li>
			</ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['pjtid'])){
                        $pjtid=$_GET['pjtid'];
                        $pjtype_sql="SELECT * FROM Project_Type WHERE pjtype_ID='$pjtid'";
                        $pjtype_qry=mysqli_query($dbconid,$pjtype_sql); 
                        while($pjtype_result= mysqli_fetch_assoc($pjtype_qry))
                        {
                        ?>
                        <input type="hidden" name="projtid" value="<?php echo $pjtype_result['pjtype_ID'];?>">
                        <div class="inputfield">
                        <label>Property Type</label>
                        <input type="text" name="pjtype" value="<?php echo $pjtype_result['pj_type'];?>" class="input">
                        </div>
                       <div class="inputfield">
                            <input type="submit" name="pjtypeUpdate" value="Update" class="btn">
                        </div>
                        <?php
                        } }
                        else{
							?> 
                    <div class="inputfield">
                        <label>Project Type</label>
                        <input type="text" name="pjtype" class="input">
                    </div>                                    
                    <div class="inputfield">
                        <input type="submit" name="pjtypeSubmit" value="Submit" class="btn">
                    </div>
                     <?php } ?>
                </form>
            </div>	
			<div class="table-data">
				<h3 class="table=title">Project Type Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Project Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $pjt_sql="SELECT * FROM Project_Type";               
                        $pjt_qry=mysqli_query($dbconid,$pjt_sql);                                        
                        while($pjtype= mysqli_fetch_assoc($pjt_qry))
                        {
                        ?>
                                <tr>
                                    <td><?php echo $pjtype['pjtype_ID'];?></td> 
                                    <td><?php echo $pjtype['pj_type'];?></td> 
                                    <td>
                                        <a href="pjType.php?pjtid=<?php echo $pjtype['pjtype_ID'];?>">Edit</a>
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