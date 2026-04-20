<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
		<main style="height: 300%;">
			<h1 class="title" style="color: #5F84A2">Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
           <div class="info-data">
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-calendar"></i> Total Services</h2>
							<p style="margin: 1rem;">10 services</p>
						</div>
						
					</div>
					
				</div>				
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-buildings"></i> Completed projects</h2>
							<p style="margin: 1rem;">5 projects</p>
						</div>						
					</div>
				</div>
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-bank"></i> Ongoing projects</h2>
							<p style="margin: 1rem;">4 projects</p>
						</div>						
					</div>
				</div>
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-basket-fill"></i> Total Property Sales</h2>
							<p style="margin: 1rem;">8 properties</p>
						</div>						
					</div>
				</div>
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-journal"></i> Service Bookings</h2>
							<p style="margin: 1rem;">10 bookings</p>
						</div>						
					</div>
				</div>
				<div class="card" style="background-color: #d4dfee;">
					<div class="head">
						<div>
							<h2><i class="bi bi-book"></i> Total Consultations</h2>
							<p style="margin: 1rem;">3 consultations</p>
						</div>						
					</div>
				</div>
			</div>
                
            </div>	
			
		</main>
		<!-- MAIN -->
	</section>

	<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
	<script src="script.js"></script>
</body>
</html>