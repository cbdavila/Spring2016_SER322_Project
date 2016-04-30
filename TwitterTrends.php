<!DOCTYPE html>
<html>
<head>
	<title>Twitter Trends</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
	
class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
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
			<input style="font-size:25px;height:50px;" type="search" class="form-control" id="search" name="searchPhrase" placeholder="Search for a Trend" value=<?php echo $searchPhrase;?>></input>
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
		$password = "pass";

		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=projectser322", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT Name FROM person"); 
			$stmt->execute();
			echo "<div style='color:green;'> Connected to Data Base successfully </div>"; 
			
			echo "<table style='border: solid 1px black;'>";
			foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=> $v) { 
				echo $v;
			}
			
			echo "</table>";
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
	
	<!--Outputs / querys -->
	<?php
		echo "<h2>Querys</h2>";
		echo $searchPhrase;
	?>
	
</div>
</body>
</html>