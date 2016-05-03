<!DOCTYPE html>
<html>
<head>
	<title>Twitter Trends</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/0.2.0/Chart.min.js" type="text/javascript"></script>
</head>
<body>
<?php
$searchPhrase = "";
$dateFrom = $timeFrom= $dateTo = $timeTo = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if (empty($_POST["searchPhrase"])) 
		{
			$nameErr = "Name is required";
		} 
		else
		{
			$searchPhrase = test_input($_POST["searchPhrase"]);
		}
		if (empty($_POST["dateFrom"])) 
		{
			$nameErr = "Name is required";
		} 
		else
		{
			$dateFrom = test_input($_POST["dateFrom"]);
		}
		if (empty($_POST["timeFrom"])) 
		{
			$nameErr = "Name is required";
		} 
		else
		{
			$timeFrom = test_input($_POST["timeFrom"]);
		}
		if (empty($_POST["dateTo"])) 
		{
			$nameErr = "Name is required";
		} 
		else
		{
			$dateTo = test_input($_POST["dateTo"]);
		}
		if (empty($_POST["timeTo"])) 
		{
			$nameErr = "Name is required";
		} 
		else
		{
			$timeTo = ($_POST["timeTo"]);
		}		
	}
	
	
	function test_input($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	class TableRows extends RecursiveIteratorIterator 
	{ 
		var $indx = 0;
		function __construct($it) { 
			parent::__construct($it, self::LEAVES_ONLY); 
		}

		function current() {
			$this->indx = ($this->indx+1) % 5;

			if($this->indx == 2) {
				$current = parent::current();
				return "<td style='width:150px;border:1px solid black;'><a href='MoreInfo.php?fn=search&username=$current'>" . $current . "</a></td>";
			}
			return "<td style='width:150px;border:1px solid black;'>" . parent::current() . "</td>";
		}

		function beginChildren() { 
			echo "<tr>"; 
		} 

		function endChildren() { 
			echo "</tr>" . "\n";
		} 
	} 
?>


<div class="container page-content">
	
		<h1 class="text-center">Twitter Trends</h1>
		<div style="font-size:20px;" class="text-center">By: Roy Sofiov, Carlos Davila, Chris Carpenter, Tyler Driskill</div>
	<div class="well">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input style="font-size:25px;height:50px;" type="search" class="form-control" id="search" name="searchPhrase" placeholder="Search for a Trend" value='<?php echo $searchPhrase?>'></input>
			<button type="submit" class="btn btn-default">Search</button>
			<div>
				<label>Date Range From: </label>
				<input type="date" name="dateFrom" value=<?php echo $dateFrom;?>>
				<input type="time" name="timeFrom" value=<?php echo $timeFrom;?>>
			</div>
			<div>
				<label>Date Range To: </label>
				<input type="date" name="dateTo" value=<?php echo $dateTo;?>>
				<input type="time" name="timeTo" value=<?php echo $timeTo;?>>
			</div>
		</form>
	</div>
	<!--MySQL Connection-->
	<?php
		//$searchPhrase = "test";
		//<?php echo $_GET["searchPhrase"];

		$servername = "localhost";
		$username = "root";
		$password = "Nooice0124";
		$messageQuery = "SELECT * FROM tweets WHERE Msg LIKE '%" . $searchPhrase . "%'";
		$PersonQuery = "SELECT * FROM person WHERE UserName = ANY(SELECT User FROM (". $messageQuery .") as mQ)";

		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=projectdb", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare($messageQuery);
			$stmt->execute();
			$clonestmt = $conn->prepare($messageQuery);
			$clonestmt->execute();
			
			$City = $conn->prepare("SELECT CityId FROM city WHERE city.CityId = ANY(SELECT HomeCity FROM (". $PersonQuery .") as mQ)");
			$City->execute();
			$countCity = $City->rowCount();
			
			$Lng = $conn->prepare("SELECT LngName FROM language WHERE language.LngID = ANY(SELECT PrfLng FROM (". $PersonQuery .") as mQ)");
			$Lng->execute();
			$countLng = $Lng->rowCount();
			
			
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			echo "<div style='color:green;'> Connected to Data Base successfully </div>"; 
			
		}
		catch(PDOException $e)
		{ 
			echo "<div style='color:red;'> Connection to Data Base failed: " . $e->getMessage() . "</div>";
		}
	?>
	
	<!--Searching Inputs-->
	<?php
	
		echo "<br><b>Your Input: </b>";
		echo $searchPhrase;
		echo "<br><b>Date From: </b>";
		echo $dateFrom;
		echo " " . $timeFrom;
		echo "<br><b>Date To: </b>";
		echo $dateTo;
		echo " " . $timeTo;
	?>
	<div style="font-size:25px;">
	<h2>Querys</h2>
	
	<br><b>Messages Found: </b> <?php echo $count; ?>
	
	<br><b>Different Citys used in: </b> <?php echo $countCity; ?>
	<div>
	<canvas id="citysPie" width="100" height="100"></canvas>
	</div>
	<script>
	
		var citysPieData = [
			<?php foreach($clonestmt as $row) { echo "{ value: ". $row["Tid"] .", color: '#'+((1<<24)*Math.random()|0).toString(16), label: '". $row["User"] ."' }, "; }?>
		];

		// Get the context of the canvas element we want to select
		var citysPie= document.getElementById("citysPie").getContext("2d");
		new Chart(citysPie).Doughnut(citysPieData);
	</script>
	
	<br><b>Different Perfered Languages: </b> <?php echo $countLng; ?>
	</div>
	<!--Outputs / querys -->
	<?php
	
		//All messages found matching in a table
		echo "<table style='border: solid 1px black;width: 100%;'>";
		echo "<tr><th>Tid</th><th>User</th><th>Date</th><th>City</th><th>Msg</th></tr>";

		foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=> $v) { 
			echo $v;
		}
		echo "</table>";
	?>
	
	
</div>
</body>
</html>