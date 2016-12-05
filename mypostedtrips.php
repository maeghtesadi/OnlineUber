<?php
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';
// this function shows all the trips posted by the current user
function showtrips (mysqli $dbc) {
	global $userid;
	$statement = "SELECT * FROM trips WHERE driverID = '$userid'";
	$query = mysqli_query($dbc, $statement);

// finds all information from the db
	while ($row = mysqli_fetch_assoc($query)) {

		$dbtripid = $row['tripID'];
		$dbdate = $row['dateAndTime'];
		$dbfrequency = $row['frequency'];
		$dbfromstreet = $row['fromStreetNum'];
		$dbtostreet = $row['toStreetnum'];
		$dbfromcity = $row['fromCity'];
		$dbtocity = $row['toCity'];
		$dbfrompostal = $row['fromPostalCode'];
		$dbtopostal = $row['toPostalCode'];
		$dbavplaces = $row['numSpacesAvailable'];
		$numtaken = $row['numSpacesTaken'];
		$dbprice = $row['price'];
		$dbspecial = $row['specialTripDesc'];
		echo "
		<tr>
		<td>$dbfromstreet ($dbfromcity) / $dbfrompostal</td>
		<td>$dbtostreet ($dbtocity) / $dbtopostal</td>
		<td>$dbdate</td>
		<td>$numtaken of $dbavplaces</td>
		<td>$dbfrequency</td>
		<td>$dbprice</td>
		<td>$dbspecial</td>
		<td><input type = 'radio' value = '$dbtripid' name = 'tripids []'></td>
		</tr>";
	}
}
?>

<!DOCTYPE>
<html>
	<head>
		<style type="text/css">
			table {border-collapse: collapse; width: 100%; background-color: #f2f2f2;}
			th, td {padding: 5px; text-align: left; width: 10%; border-bottom: 1px solid #ddd;}
			h1 {padding-left: 15px;} h3 {color: red; font-style: italic;}
			input[type=submit] {width: 10%;background-color: #4CAF50;color: white;padding: 14px 20px;margin: 8px 0;border: 4px;}
		</style>
	</head>
	<body>
	<h1> My Posted Trips </h1>
		<form action ='edittrip.php' method = 'post'>	
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Seats Left</th>
				    <th>Frequency</th>
				    <th>Price $</th>
				    <th>Special Note</th>
				    <th>Edit Trip</th>
				</tr>
				  <?php showtrips($dbc) ?>	
			</table>
			<input type = 'submit' name = 'openEditPage' value = 'Edit Trip'>
		</form>			
	</body>	
</html>