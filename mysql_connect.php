<?php
//Author: Cedric Abou-Farhat #27032633 
define('DB_USER', 'jpc353_2');
define('DB_PASSWORD', 'nDGfrG');
define('DB_HOST', 'jpc353_2.encs.concordia.ca');
define('DB_NAME', 'jpc353_2');

//Create connection to the Database.
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
OR die ('Could no connect to MySQL '.mysqli_connect_error());
?>