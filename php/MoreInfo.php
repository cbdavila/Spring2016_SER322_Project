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
	    if ($_GET['fn'] == "search")
            if (!empty($_GET['username']))
                echo "<h2>". $_GET['username'] . "'s Info</h2>";

		//Querry params
		$select = "*";
		$project = "". $dbName ."";
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
                        FROM tweets, city
                        Where CityId = City  AND User = '" . $_GET['username'] . "'";

        $notFollowingQuery = "SELECT DISTINCT Followee
                              FROM following
                              WHERE Followee NOT IN (SELECT Followee 
                                                     FROM following
					                                 WHERE Follower = '" . $_GET['username'] . "')";

		$followInsertQuery = "INSERT INTO " . $dbName .".following (`Follower`, `Followee`)
                              VALUES ('". $_GET['username'] ."','". $_GET['followee'] ."')";
                              //INSERT INTO `projectdb`.`following` (`Follower`, `Followee`) VALUES (‘BoatsAndHoes11','CarlosD’);

        //Builds a SELECT query
        //with the selection made
        //in data.html 
        // Connect to MySQL
        if ( !( $database = mysql_connect( $servername, $username, $password) ) )
            die( "Could not connect to database </body></html>" ); 
        
        // open database
        if ( !mysql_select_db( $project, $database ) )
            die( "Could not open products database </body></html>" );

        // query #0
        if (!empty($_GET['followee']))
        {
        	if ( !( $result = mysql_query( $followInsertQuery, $database ) ) )
            {
                print( "Could not execute query! <br />" );
                die( mysql_error() . "</body></html>" );
            }
        }

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

        // query #4
        if ( !( $result = mysql_query( $notFollowingQuery, $database ) ) )
        {
            print( "Could not execute query! <br />" );
            die( mysql_error() . "</body></html>" );
        } // end if 
        
        echo "<h4>Add a Followee for ". $_GET['username'] . "</h4>"; 
        print("<select id=\"select\">");
        for ( $counter = 0; $row = mysql_fetch_row( $result ); $counter++ )
        {
            // build table to display results
            print( "<option value=\"" );
            foreach ( $row as $key => $value )
                print( "$value\">$value" );
   
            print( "</option>" );
        }
        print("</select>");

        mysql_close( $database );
	?> 

	<button onclick=myFunction()>Try it</button>

	<script>
	    function myFunction() {
	    	var usrnm = getParameterByName('username');
	    	var e = document.getElementById("select");
            var slctFol = e.options[e.selectedIndex].value;
            var newURL = "MoreInfo.php?fn=search&username="+usrnm+"&followee="+slctFol;
            window.location.href = newURL;
        }

        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                 results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

	</script> 
	
</div>
</body>
</html>