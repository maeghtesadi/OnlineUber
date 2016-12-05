<?php
//nicolas horta-Adam 27171315
//cedric abou-farhat
include 'navigation.php';
require_once 'mysql_connect.php';


//this method prints out the tripHistory table to show every created and deleted trips, their completion status, creation time, deletion time and the id and name of driver
function showtriphistory(mysqli $dbc)
{
	//returns the trip log
	$statement = "SELECT * from tripHistory";

	$trips= mysqli_query($dbc, $statement);
	
	while($row= mysqli_fetch_assoc($trips))
	{
		$fname="";
		$lname="";

		$tripID= $row['tripID'];
		$driverID= $row['driverID'];
		$action= $row['action'];
		$completed= $row['completed'];
		$startDate= $row['startDate'];
		$endDate= $row['endDate'];
		// returns the name of driver for the trip
		$statement2 = "SELECT fname,lname from members where id='$driverID'";
		$member= mysqli_query($dbc, $statement2);
	
		while($row= mysqli_fetch_assoc($member))
		{
			$fname=$row['fname'];
			$lname=$row['lname'];
		}
	
	
		echo " <tr>
				<td> $tripID</td>
				<td> $driverID</td>
				<td> $fname</td>
				<td> $lname</td>
				<td> $action</td>
				<td>$completed</td>
				<td>$startDate</td>
				<td>$endDate</td>
				</tr>";

	}
}


?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="layout.css">
	</head>	
	<body>
		<h1>Trips History</h1>
		<h4>Created and delete trips</h4>
		<form action ='' method = 'post'>
			<table>
			  <tr>
			    <th>tripID</th>
			    <th>driverID</th>
			    <th>fname</th>
			    <th>lname</th>
			    <th>action</th>
			    <th>completed </th>
			    <th>startDate </th>
				<th>endDate </th>
			  </tr>
			  <?php showtripHistory($dbc);?>
			</table>
			<input type = "submit" value = "Update!" name = "changestatus" style = "width: 10%;" >
		</form>
	</body>
</html>