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
		$searchPhrase = "Test";
		echo "<h2>$searchPhrase</h2>";

		$servername = "localhost";
		$username = "root";
		$password = "Nooice0124";
		$select = "*";
		$project = "projectdb";
		$personTbl = "person";

		extract( $_POST );

        // build SELECT query
        $query = "SELECT " . $select . " FROM "
                 .$project . "." . $personTbl ;

        //Builds a SELECT query
        //with the selection made
        //in data.html 
        // Connect to MySQL
        if ( !( $database = mysql_connect( $servername, $username, $password) ) )
            die( "Could not connect to database </body></html>" ); 
        
        // open Products database
        if ( !mysql_select_db( $project, $database ) )
            die( "Could not open products database </body></html>" );

        // query Products database
        if ( !( $result = mysql_query( $query, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 

        mysql_close( $database );
        print("<h3>Search Results</h3>");
        print("<table border=\"1\" style=\"width:100%\">");
        for ( $counter = 0; $row = mysql_fetch_row( $result );
        $counter++ )
        {
            // build table to display results
            print( "<tr>" );
            foreach ( $row as $key => $value )
                print( "<td>$value</td>" );
   
            print( "</tr>" );
        }
        print("</table>");
		// try 
		// {
		// 	$conn = new PDO("mysql:host=$servername;dbname=projectdb", $username, $password);
		// 	// set the PDO error mode to exception
		// 	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		// 	echo " Connected successfully"; 
		// }
		// catch(PDOException $e)
		// {
		// 	echo " Connection failed: " . $e->getMessage();
		// }
	?>  
</div>
</body>
</html>