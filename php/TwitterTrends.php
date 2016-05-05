<!DOCTYPE html>
<html>
<head>
	<title>Twitter Trends</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	
	<!--MySQL Connection-->
	<?php
		$servername = "localhost";
		$username = "root";
		$password = "pass";
		$dbName = "projectser322"; //projectdb projectser322
	?>
</head>
<body>
<?php
$searchPhrase = "";
$dateFrom = "2015-12-13";
$timeFrom = "";
$dateTo = "2016-05-04";
$timeTo = "";

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
			if($this->indx == 4) {
				$current = parent::current();
				return "<td style='width:150px;border:1px solid black;'><a href='CityInfo.php?fn=search&CityId=$current'>" . $current . "</a></td>";
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
	<div class="well" style="background-color:#A0A0A0;">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input style="font-size:25px;height:50px;" type="search" class="form-control" id="search" name="searchPhrase" placeholder="Search for a Trend" value='<?php echo $searchPhrase?>'></input>
			<button type="submit" class="btn btn-default">Search</button>
			<div>
				<label>Date Range From: </label>
				<input type="date" name="dateFrom" value=<?php echo $dateFrom;?>>
				<!-- <input type="time" name="timeFrom" value=<?php echo $timeFrom;?>> -->
			</div>
			<div>
				<label>Date Range To: </label>
				<input type="date" name="dateTo" value=<?php echo $dateTo;?>>
				<!-- <input type="time" name="timeTo" value=<?php echo $timeTo;?>> -->
			</div>
		</form>
	</div>
	
	<!--Searching Inputs-->
	<div class="well">
		<?php
			echo "<b>Your Input: </b>";
			echo $searchPhrase;
			echo "<br><b>Date From: </b>";
			echo $dateFrom;
			echo " " . $timeFrom;
			echo "<br><b>Date To: </b>";
			echo $dateTo;
			echo " " . $timeTo;
		?>
	</div>
	
	<!-- Querys -->
	<h2>Querys</h2>
	<?php
		$messageQuery = "SELECT * FROM tweets WHERE Msg LIKE '%" . $searchPhrase . "%'AND tweets.Date BETWEEN '$dateFrom' AND '$dateTo'";
		$PersonQuery = "SELECT * FROM person WHERE UserName = ANY(SELECT User FROM (". $messageQuery .") as mQ)";
		
		try 
		{
			$conn = new PDO("mysql:host=$servername;dbname=". $dbName, $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//all the queried messages
			$stmt = $conn->prepare($messageQuery);
			$stmt->execute();
			$clonestmt = $conn->prepare($messageQuery);
			$clonestmt->execute();
			
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			
			////City////
			$City = $conn->prepare("SELECT city.CityName AS name, COUNT(*) AS num
									FROM city
									INNER JOIN 
										(SELECT City AS 'name'
										FROM (". $messageQuery .") AS pQ
										) AS totalCount
									ON city.CityId=totalCount.name
									GROUP BY CityId
									");
			$City->execute();
			$countCity = $City->rowCount();
			//CityPie
			$CityPie = $conn->prepare("SELECT city.CityName AS name, COUNT(*) AS num
									FROM city
									INNER JOIN 
										(SELECT City AS 'name'
										FROM (". $messageQuery .") AS pQ
										) AS totalCount
									ON city.CityId=totalCount.name
									GROUP BY CityId
									");
			$CityPie->execute();
			
			////Country////
			$Country = $conn->prepare("SELECT country.CountryName AS name, COUNT(*) AS num
									FROM country
									INNER JOIN
										(SELECT city.CountryId as 'name'
										FROM city
										INNER JOIN 
											(SELECT City AS 'name'
											FROM (". $messageQuery .") AS pQ) AS totalCount
										ON city.CityId=totalCount.name) as cQ
									ON country.CountryId=cQ.name
									GROUP BY CountryId
									");
			$Country->execute();
			$countCountry = $Country->rowCount();
			
			////Language////
			$Language = $conn->prepare("SELECT LngName AS name, COUNT(*) AS num
									FROM language 
									INNER JOIN
										(SELECT PrfLng 
										FROM (". $PersonQuery .") as mQ) as totalCount
									ON language.LngID=totalCount.PrfLng
									GROUP BY LngID
									");
			$Language->execute();
			$countLanguage = $Language->rowCount();
			////People Referenced////
			$Referenced = $conn->prepare("SELECT word AS name, COUNT(*) AS num
										FROM 
											(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(mQ.Msg, ' ', n.n), ' ', -1) AS word
											FROM (". $messageQuery .") as mQ CROSS JOIN 
												(SELECT a.N + b.N * 10 + 1 n
												FROM 
													(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
													,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
												ORDER BY n) n
											WHERE n.n <= 1 + (LENGTH(mQ.Msg) - LENGTH(REPLACE(mQ.Msg, ' ', '')))
											ORDER BY Msg) AS messageWords
										WHERE word LIKE '%@%'
										GROUP BY word
										
										");
			$Referenced->execute();
			$countReferenced = $Referenced->rowCount();
			
			////HashTags////
			$HashTags = $conn->prepare("SELECT word AS name, COUNT(*) AS num
										FROM 
											(SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(mQ.Msg, ' ', n.n), ' ', -1) AS word
											FROM (". $messageQuery .") as mQ CROSS JOIN 
												(SELECT a.N + b.N * 10 + 1 n
												FROM 
													(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
													,(SELECT 0 AS N UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
												ORDER BY n) n
											WHERE n.n <= 1 + (LENGTH(mQ.Msg) - LENGTH(REPLACE(mQ.Msg, ' ', '')))
											ORDER BY Msg) AS messageWords
										WHERE word LIKE '%#%'
										GROUP BY word
										
										");
										/*SELECT Msg 
										FROM (". $messageQuery .") as mQ
										WHERE Msg LIKE '%#%'
									");
									SUBSTRING_INDEX(SUBSTRING_INDEX('test,test,test', ',', 1), ',', -1)
									*/
			$HashTags->execute();
			$countHashTags = $HashTags->rowCount();
			
			// $messageQuery = "SELECT * FROM tweets WHERE Msg LIKE '%" . $searchPhrase . "%'AND tweets.Date BETWEEN '$dateFrom' AND '$dateTo'";
			 
			////Dates////
			$Dates = $conn->prepare("SELECT Date AS name, COUNT(*) AS num 
									FROM (". $messageQuery .") as mQ
									GROUP BY Date
									");
			$Dates->execute();
			$countDates = $Dates->rowCount();


			//Opening Elements//
			echo "<div class='well' style='color:green;background-color:#B2FFB2;'> Connected to Data Base successfully </div>"; 
			echo "<div style=''>"; //font-size:25px;
			
			//tweets found
				echo "<b>Tweets Found: </b> ". $count;
			
			//City
				//number of diffrent citys '5'
				echo "<hr><b>Citys used in: </b>". $countCity;
				//cites with amount used by 'Phoenix: 7'
				echo "<br><canvas id='citysPie' width='200' height='150'></canvas>";
				echo "<ul>";
				foreach($City as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Tweets</b></li>";
				}
				echo "</ul>";

			
			//Country
				//number of diffrent Countries '5'
				echo "<hr><b>Countries used in: </b>". $countCountry;
				//Countries with amount used by 'USA: 7'
				echo "<ul>";
				foreach($Country as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Tweets</b></li>";
				}
				echo "</ul>";
			
			//Language
				//number of diffrent Languages '5'
				echo "<hr><b>Different Preferred Languages: </b>". $countLanguage;
				//Languages with amount used by 'English: 7'
				echo "<ul>";
				foreach($Language as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Tweets</b></li>";
				}
				echo "</ul>";
			
			//People Referenced with @ 
				//number of diffrent References '5'
				echo "<hr><b>People referenced: </b>". $countReferenced;
				//References with amount used by '@Tom: 7'
				echo "<ul>";
				foreach($Referenced as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Time</b></li>";
				}
				echo "</ul>";
			//HashTags
				//number of diffrent HashTags '5'
				echo "<hr><b>HashTags used: </b>". $countHashTags;
				//HashTags with amount used by '#Awesome: 7'
				echo "<ul>";
				foreach($HashTags as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Time</b></li>";
				}
				echo "</ul>";
			//Dates 
				//Number of diffrent Dates 
				echo "<hr><b>Dates used on: </b>". $countDates;
				//Dates with amount Used '2-7-2016: 7'
				echo "<ul>";
				foreach($Dates as $row) 
				{ 
					echo "<li>". $row["name"] .": <b>". $row["num"] ." Tweets</b></li>";
				}
				echo "</ul>";
			
			//Closing Elements//
			echo "</div><hr>";
			
			//List as Table
				echo "<h2>Tweets Found: ". $count ."</h2>";
				echo "<table style='border: solid 1px black;width: 100%;'>";
				echo "<tr><th>Tid</th><th>User</th><th>Date</th><th>City</th><th>Msg</th></tr>";

				foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=> $v) { 
					echo $v;
				}
				echo "</table>";
			
		}
		catch(PDOException $e)
		{
			echo "<div class='well' style='color:red;background-color:#FFB2B2;'> Connection to Data Base failed: " . $e->getMessage() . "</div>";
		}
	?>
	
	<!-- Pie Testing Stuff 
	<canvas id="citysPie" width="200" height="100"></canvas>-->
	<script>
		var citysPieData = [
			<?php foreach($CityPie as $row) { echo "{ value: ". $row["num"] .", color: '#'+((1<<24)*Math.random()|0).toString(16), label: '". $row["name"] ."' }, "; }?>
		];
		
		var ctx = document.getElementById('citysPie').getContext('2d');
		var testing = new Chart(ctx).Pie(citysPieData, {});
	</script>
	
</div>	
</body>
</html>