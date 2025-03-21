<!DOCTYPE html>
<html lang="en">
<?php 
session_start(); 
require 'Connection.php';
$conn = Connect();
$sql = "SELECT * FROM sensors";
$result = $conn->query($sql);

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&amp;subset=devanagari,latin-ext" rel="stylesheet">
	<link rel="shortcut icon" type="image/icon" href="assets/logo/favicon.png"/>
       
    <!--font-awesome.min.css-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

	<!--flat icon css-->
	<link rel="stylesheet" href="assets/css/flaticon.css">

	<!--animate.css-->
    <link rel="stylesheet" href="assets/css/animate.css">

    <!--owl.carousel.css-->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
	<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
		
    <!--bootstrap.min.css-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
	<!-- bootsnav -->
	<link rel="stylesheet" href="assets/css/bootsnav.css" >	

	<link rel="stylesheet" href="assets/css/user.css">

	<link rel="stylesheet" href="assets/css/viewshopdetails.css">
        
    <!--style.css-->
    <link rel="stylesheet" href="assets/css/style.css">
        
    <!--responsive.css-->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <title>Sensor Control</title>
</head>
<body>

	<header class="top-area">
		<div class="header-area">
			<!-- Start Navigation -->
			<nav class="navbar navbar-default bootsnav navbar-fixed dark no-background">

				<div class="container">

					<!-- Start Header Navigation -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
							<i class="fa fa-bars"></i>
						</button>
						<a class="navbar-brand" href="index.php">Smart Home</a>
					</div><!--/.navbar-header-->
					<!-- End Header Navigation -->

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
						<ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
						<li class=" smooth-menu active"></li>
							<!-- <li class=" smooth-menu"><a href="#education">education</a></li> -->
							<li> <a href="Sensor_Control.php">Sensor Control</a></li>
								<li> <a href="Skill.php">Skill</a></li>
			                    <li> <a href="Experience.php">Experience</a></li>
			                    <li> <a href="Contact.php">Contact</a></li>
						</ul><!--/.nav -->
					</div><!-- /.navbar-collapse -->
				</div><!--/.container-->
			</nav><!--/nav-->
			<!-- End Navigation -->
		</div><!--/.header-area-->

		<div class="clearfix"></div>

	</header><!-- /.top-area-->
	<!-- top-area End -->
	 
    		<!--education start -->
		<section id="education" class="education">
			<div class="section-heading text-center">
				<h3>Sensor Control</h3>
			</div>
			<div class="container">
				<div class="education-horizontal-timeline">
					<div class="row">
						<h1>Sensors</h1>
						<table>
							<tr>
								<th>RID</th>
								<th>DateTime</th>
								<th>Luminosity</th>
								<th>Temperature</th>
								<th>Presence</th>
							</tr>
							<?php while ($row = $result->fetch_assoc()): ?>
							<tr>
								<td><?php echo $row['RID']; ?></td>
								<td><?php echo $row['DateTime']; ?></td>
								<td><?php echo $row['Luminosity']; ?></td>
								<td><?php echo $row['Temperature']; ?></td>
								<td><?php echo $row['Presence']; ?></td>
							</tr>
							<?php endwhile; ?>
						</table>
					</div>
				</div>
			</div>

		</section><!--/.education-->
		<!--education end -->

		<?php
		#$sql = "SELECT * FROM sensors";  
		#$result = $conn->query($sql);

		#if ($result->num_rows > 0) {
    	#	while($row = $result->fetch_assoc()) {
        #		#echo "Nhiệt độ: " . $row["temperature"] . "°C<br>";
    	#	}
		#} else {
    	#	echo "Không có dữ liệu.";
		#}
        ?>          
    </div>

</body>
</html>