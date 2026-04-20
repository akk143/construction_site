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
				<li><a href="#" class="active">FAQ</a></li>
			</ul>
            <div class="data-registration-form">
                <form action="../DB/insert.php" method="POST">
                    <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['faqid'])){
                        $faqid=$_GET['faqid'];
                        $faq_sql="SELECT * FROM FAQ WHERE FAQ_ID ='$faqid'";
                        $faq_qry=mysqli_query($dbconid,$faq_sql); 
                        while($faq_result= mysqli_fetch_assoc($faq_qry))
                        {
                        ?>
                        <input type="hidden" name="faqid" value="<?php echo $faq_result['FAQ_ID'];?>">
                       <div class="inputfield">
                        <label>Question</label>
                        <input type="text" name="question" value="<?php echo $faq_result['FAQ_question'];?>" class="input">
                    </div>   
                    <div class="inputfield">
                        <label>Answer</label>
                        <input type="text" name="ans" value="<?php echo $faq_result['FAQ_answer'];?>" class="input">
                    </div>           
                       <div class="inputfield">
                            <input type="submit" name="faqUpdate" value="Update" class="btn">
                        </div>
                        <?php
                        } }
                        else{
							?> 
                    <div class="inputfield">
                        <label>Question</label>
                        <input type="text" name="question" class="input">
                    </div>   
                    <div class="inputfield">
                        <label>Answer</label>
                        <input type="text" name="ans" class="input">
                    </div>                        
                    <div class="inputfield">
                        <input type="submit" name="faqSubmit" value="Submit" class="btn">
                    </div>
                     <?php } ?>
                </form>
            </div>	
			<div class="table-data">
				<h3 class="table=title">FAQ Table</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $faq_sql="SELECT * FROM FAQ";               
                        $faq_qry=mysqli_query($dbconid,$faq_sql);                                        
                        while($faq= mysqli_fetch_assoc($faq_qry))
                        {
                        ?>
                                <tr>
                                    <td><?php echo $faq['FAQ_ID'];?></td> 
                                    <td><?php echo $faq['FAQ_question'];?></td> 
                                    <td><?php echo $faq['FAQ_answer'];?></td> 
                                    <td>
                                        <a href="faq.php?faqid=<?php echo $faq['FAQ_ID'];?>">Edit</a>
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