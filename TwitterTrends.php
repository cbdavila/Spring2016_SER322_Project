<!DOCTYPE html>
<html>
<head>
	<title>Twitter Trends</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container page-content">
	
		<h1 class="text-center">Twitter Trends</h1>
		<div style="font-size:20px;" class="text-center">By: Roy Sofiov, Carlos Davila, Chris Carpenter, Tyler Driskill</div>
	<div class="well">
		<form action="searchPhrase.php">
			<input style="font-size:25px;height:50px;" type="search" class="form-control" id="search" placeholder="Search for a Trend" value=<?php echo "";?>></input>
			<button type="submit" class="btn btn-default">Search</button>
			<div>
				<label>Date Range From: </label>
				<input type="date">
				<input type="time">
			</div>
			<div>
				<label>Date Range To: </label>
				<input type="date">
				<input type="time">
			</div>
		</form>
	</div>
	<?php
		$searchPhrase = "test";
		echo "<h2>$searchPhrase</h2>";

		$servername = "localhost";
		$username = "root";
		$password = "pass";

		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=projectser322", $username, $password);
			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo " Connected successfully"; 
		}
		catch(PDOException $e)
		{
			echo " Connection failed: " . $e->getMessage();
		}
	?>  
</div>
</body>
</html>