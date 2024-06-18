<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<script src="jquery.min.js"></script>
		<script>
			$(document).ready(function(){
				 $("#getUID").load("UIDContainer.php");
				setInterval(function() {
					$("#getUID").load("UIDContainer.php");
				}, 500);
			});
		</script>
		
		<style>
		html {
			font-family: Arial;
			display: inline-block;
			margin: 0px auto;

		}
         body {
			 background-color: black;
			 }
		
		textarea {
			resize: none;
		}

		ul.topnav {
			list-style-type: none;
			margin: auto;
			padding: 0;
			overflow: hidden;
			background-color: #ff6600;
			width: 100%;
		}

		ul.topnav li {float: left;}

		ul.topnav li a {
			display: block;
			color: aqua;
			text-align: center;
			padding: 16px 16px;
			text-decoration: none;
		}

		ul.topnav li a:hover:not(.active) {background-color: #3333ff;}

		ul.topnav li a.active {background-color: #333;}

		ul.topnav li.right {float: right;}

		@media screen and (max-width: 600px) {
			ul.topnav li.right, 
			ul.topnav li {float: none;}
		}
		form {
			display: block;
			margin-top: 0em;
			unicode-bidi: isolate;
			margin-block-end: 1em;
		}
		.row h3 {
			margin-right: 10px;
			margin-left: 5px;
		}
		</style>
		
		<title>Registration : Medicard Database</title>
	</head>
	
	<body>

		<h2 align="center"><font color="aqua">Medicard Database</font></h2>
		<ul class="topnav">
			<li><a href="../starting/dashboard.php">Home</a></li>
			<li><a href="user data.php">User Data</a></li>
			<li><a class="active" href="registration.php">Registration</a></li>
			<li><a href="read tag.php">Read Tag ID</a></li>
		</ul>

		<div class="container">
			<br>
			<div class="center" style="margin: 0 auto; width:495px; border-style: solid; border-color: green;">
				<div class="row">
					<h3 align="center"><font color="aqua">Registration Form</font></h3>
				</div>
				<br>
				<form class="form-horizontal" action="insertDB.php" method="post" enctype="multipart/form-data">
					<div class="control-group">
						<label class="control-label"><font color="aqua">ID</label>
						<div class="controls">
							<textarea name="id" id="getUID" placeholder="Please Scan your Card / Key Chain to display ID" rows="1" cols="1" required></textarea>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Family Name</label>
						<div class="controls">
							<input id="div_refresh" name="fname" type="text"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Main Beneficiary Name</label>
						<div class="controls">
							<input id="div_refresh" name="bname" type="text"  placeholder="" required>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Gender</label>
						<div class="controls">
							<select name="gender">
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Email Address</label>
						<div class="controls">
							<input name="email" type="text" placeholder="" required>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label">Mobile Number</label>
						<div class="controls">
							<input name="mobile" type="text"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Proof of Residence</label>
						<div class="controls">
							<input name="proof" type="file"  placeholder="" required>
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">Complete Address</label>
						<div class="controls">
							<input name="address" type="text" placeholder="" required>
						</div>
					</div>
					
					<div class="form-actions">
						<button type="submit" class="btn btn-success">Save</button>
                    </div>
				</form></font>
				
			</div>               
		</div> <!-- /container -->	
	</body>
</html>