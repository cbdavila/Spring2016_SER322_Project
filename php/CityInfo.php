<!DOCTYPE html>
<html>
<head>
	<title>Twitter Trends</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
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
<div class="container page-content">
	
		<h1 class="text-center">Twitter Trends</h1>
	<?php	
        $CityName = "City Name N/A";

		extract( $_POST );

        // build SELECT querys
        $infoQuery = "SELECT city.Population, CountryName, country.Population
                      FROM city, country
                      WHERE city.CountryId = country.CountryId AND CityId = " . $_GET['CityId'];

        $selectCityQuery = "SELECT CityName
                            FROM city
                            WHERE CityId = " . $_GET['CityId'];

        $tweetsQuery = "SELECT User, Date, Msg 
                        FROM tweets
                        Where City = " . $_GET['CityId'];

        $personQuery = "SELECT UserName
                        FROM person, city
                        WHERE CityId = HomeCity AND CityId = " . $_GET['CityId'];

        //Builds a SELECT query
        //with the selection made
        //in data.html 
        // Connect to MySQL
        if ( !( $database = mysql_connect( $servername, $username, $password) ) )
            die( "Could not connect to database </body></html>" ); 
        
        // open database
        if ( !mysql_select_db( $dbName, $database ) )
            die( "Could not open products database </body></html>" );

        // query #1
        if ( !( $result = mysql_query( $selectCityQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            foreach ( $row as $key => $value )
                $CityName = $value;
        }
        echo "<h2>". $CityName . "'s Info</h2>";

        // query #2
        if ( !( $result = mysql_query( $infoQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>City Population:</th><th>Located In:</th><th>Country Population:</th></tr>");
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            print( "<tr>" );
            foreach ( $row as $key => $value )
                print( "<td>$value</td>" );
   
            print( "</tr>" );
        }
        print("</table>");

        // query #3
        if ( !( $result = mysql_query( $tweetsQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        echo "<h3>Posted Tweets From $CityName</h3>";
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>User:</th><th>Date:</th><th>Tweet:</th></tr>");
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            print( "<tr>" );
            foreach ( $row as $key => $value )
                print( "<td>$value</td>" );
   
            print( "</tr>" );
        }
        print("</table>");

        // query #4
        if ( !( $result = mysql_query( $personQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        echo "<h3>Users from $CityName</h3>";
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>UserName:</th></tr>");
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            print( "<tr>" );
            foreach ( $row as $key => $value )
                print( "<td>$value</td>" );
   
            print( "</tr>" );
        }
        print("</table>");


        mysql_close( $database );
	?>  
</div>
</body>
</html>