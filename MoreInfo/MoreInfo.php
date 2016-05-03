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
	<?php
	    if ($_GET['fn'] == "search")
            if (!empty($_GET['username']))
                echo "<h2>". $_GET['username'] . "'s Info</h2>";

		$servername = "localhost";
		$username = "root";
		$password = "Nooice0124";

		//Querry params
		$select = "*";
		$project = "projectdb";
		$personTbl = "person";
		$followTbl =  "following";
		$Follower = "Follower";
		$Followee = "Followee";
		$UsrNm = "UserName";
		$Name = "Name";
		$Email = "Email";
		$DOB = "DOB";
		$CtNm = "CityName";
		$LNm = "LngName";
		$cityTbl = "city";
		$lngTbl = "language";
		$CtId = "CityId";
		$HmCt = "HomeCity";
		$PrLng = "PrfLng";
		$LngID = "LngID" ;

		extract( $_POST );

        // build SELECT query
        $followingQuery = " SELECT " . $Followee . " FROM " . $project . "." . $followTbl . " WHERE " . $Follower . " = '" . $_GET['username'] . "'";

        $infoQuery = "SELECT ". $UsrNm . " , " . $Name . " , " . $Email . " , " . $DOB . " , " . $CtNm . " , " . $LNm . 
        " FROM " . $project . "." . $personTbl . " , " . $project . "." . $cityTbl . " , " . $project . ".". $lngTbl .
        " Where " . $CtId . " = " . $HmCt . " AND " .  $PrLng . " = " . $LngID . " AND " . $UsrNm . " = '" . $_GET['username'] . "'";

        $tweetsQuery = "SELECT Date, CityName, Msg 
                        FROM projectdb.tweets, projectdb.city
                        Where CityId = City  AND User = '" . $_GET['username'] . "'";

        //Builds a SELECT query
        //with the selection made
        //in data.html 
        // Connect to MySQL
        if ( !( $database = mysql_connect( $servername, $username, $password) ) )
            die( "Could not connect to database </body></html>" ); 
        
        // open database
        if ( !mysql_select_db( $project, $database ) )
            die( "Could not open products database </body></html>" );

        // query #1
        if ( !( $result = mysql_query( $infoQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>User Name:</th><th>Name:</th><th>Email:</th><th>D.O.B.:</th><th>Home City:</th><th>Preferred Language:</th></tr>");
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            print( "<tr>" );
            foreach ( $row as $key => $value )
                print( "<td>$value</td>" );
   
            print( "</tr>" );
        }
        print("</table>");

        // query #2
        if ( !( $result = mysql_query( $tweetsQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        echo "<h3>Posted Tweets</h3>";
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>Date:</th><th>City:</th><th>Tweet:</th></tr>");
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
        if ( !( $result = mysql_query( $followingQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        echo "<h3>Following</h3>";
        print("<table border=\"1\" style=\"width:100%\">");
        print("<tr><th>Users:</th></tr>");
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