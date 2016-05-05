# Spring2016_SER322_Project
Made for an ASU SER 322 database class 2016
## Contributors
- Chris Carpenter
- Carlos Davila
- Tyler Driskill
- Roy Sofiov

## Installation
1. Download MySQL from http://dev.mysql.com/downloads/mysql/ and install
2. Use default settings during installation
4. Install or use a php enabled server most of us used xampp for running locally from https://www.apachefriends.org/index.html

## Set up 
### SQL
1. Open MySQL Workbench
2. CLICK + sign next to MySQL Connections
3. Fill out the New Server Information
2. Click the Local Instance box and enter your password
3. On the left side click Startup / Shutdown and start the server if not already running.
4. Open a tab for executing querys
5. paste in our script "SetupTweetData.txt" from github found in the sql folder

### PHP
1. Copy the files in the php folder on github
2. Paste them in your php enabled server
3. Edit all the php files "$servername, $username, $password, $dbName" found in the head tag to reflect your connection to your SQL database

## Running
1. Open a web browser
2. Type in the web location of TwitterTrends.php for example xampp would most likely be http://localhost:80/TwitterTrends.php
3. Use the searchbox to find trends in the tweets that used that phrase in a message.
4. Set the dates to find only the tweets sent in that time frame. The sql data reflects dummy data from 2016-03-24 to 2016-04-30
5. You can click the User and City Number to see more information about them individualy
6. in the User information you can add a a new Followee for that user to the database