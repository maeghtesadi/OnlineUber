<?php 
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';
// shows all available trips that are not the ones posted by the current user
$notripsbooked;
$currentuserBalance;
// returns the current balance of the user 
$returnbalance = "SELECT balance FROM members WHERE id = '$userid'";
$balquery = mysqli_query($dbc, $returnbalance);
$bal = mysqli_fetch_assoc($balquery);
$currentuserBalance = $bal['balance'];

function showtrips (mysqli $dbc) {
	global $userid, $currentuserBalance;
	$currentprice = array();
	$currenttripID = array();
	$count = 0;
	// select all trips where user logged in is not the driver and still have spaces available
	// trips that are full wont be returned
	$statement = "SELECT * FROM trips WHERE driverID <> '$userid' AND numSpacesTaken <> '0'";
	$query = mysqli_query($dbc, $statement);

	while ($row = mysqli_fetch_assoc($query)) {

		$dbtripid = $row['tripID'];
	// finds the name and rating of the driver posting a trip	
		$dbdriverid = $row['driverID'];
		$statement2 = "SELECT rating, fname, lname FROM members WHERE id = '$dbdriverid'";
		$query2 = mysqli_query($dbc, $statement2);
		$row2 = mysqli_fetch_assoc($query2);
		$newdbFirstname = $row2['fname'];
		$newdbLastname = $row2['lname'];
		$newdbRating = number_format($row2['rating'],2);
// information on the every posted trip
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
		<td>$newdbFirstname $newdbLastname</td>
		<td>$newdbRating</td>
		<td><input type = 'checkbox' value = '$count' name = 'tripids []'></td>
		</tr>";
		$count++;
		array_push($currenttripID, $dbtripid);
		array_push($currentprice, $dbprice);
	}

	if (isset($_POST['booktrip'])) {
		
		$total = 0;
		//Get total of all selected rides
		foreach ($_POST as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $k => $v) {
						$total += $currentprice[$v];
					}
				}
			}
if ($currentuserBalance >= $total) {
		foreach ($_POST as $key => $value) {
				
				if (is_array($value)) {
					foreach ($value as $k => $v) {
					$thistripID = $currenttripID[$v];	
					//request a specific trip for user logged in
					$statement = "INSERT INTO tripMembers (tripID, memberID) VALUES ('$thistripID','$userid')";
					if (!mysqli_query($dbc, $statement)) 
						$alreadybooked = "<h3>You already booked this trip!</h3>";
					else 
						$successbook =  "<h3 style='display:inline;'>Your trip has been requested!</h3>";
				}

			}
		}
} else echo"<h3>Unfortunatly, your current balance is not sufficient to request those trips. Please update your Super account.</h3>";

		if(isset($alreadybooked)) echo $alreadybooked;
		if(isset($successbook)) echo $successbook;
		
	}
}

// returns all the trips that are awaiting a response from the driver
// retreive also all the information about it
function showmytrips (mysqli $dbc) {
	global $userid, $notripsbooked;

	if (isset($_POST['cancelrequest'])) {

		foreach ($_POST as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					//cancel a request for a trip for user logged in
					$cancel = "DELETE FROM tripMembers where tripID = '$v' AND memberID = '$userid'";
					if (!mysqli_query($dbc, $cancel))
						echo mysqli_error($dbc);
				}
			}
		}
	}
	//select trip information for every trip user logged in requested and isnt confirmed yet
	$statement = "SELECT * FROM trips, tripMembers WHERE tripMembers.memberID = '$userid' AND trips.tripID = tripMembers.tripID AND tripMembers.confirmed = '0'";
	$query = mysqli_query($dbc, $statement);

	if (mysqli_num_rows($query) == 0)
		$notripsbooked = "<h3 style='color: black;'>Your haven't booked any trips yet.</h3>";

	while ($row = mysqli_fetch_assoc($query)) {

		$dbtripid = $row['tripID'];

		$dbdriverid = $row['driverID'];
		//select the rating and name from the driver of user logged in’s trip
		$statement2 = "SELECT rating, fname, lname FROM members WHERE id = '$dbdriverid'";
		$query2 = mysqli_query($dbc, $statement2);
		$row2 = mysqli_fetch_assoc($query2);
		$newdbFirstname = $row2['fname'];
		$newdbLastname = $row2['lname'];
		$newdbRating = number_format($row2['rating'],2);

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
		<td>$newdbFirstname $newdbLastname</td>
		<td>$newdbRating</td>
		<td><input type = 'checkbox' name = 'cancel []' value = '$dbtripid'></td>
		</tr>
		";
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			table {border-collapse: collapse; width: 100%; background-color: #f2f2f2;}
			th, td {padding: 5px; text-align: left; width: 10%; border-bottom: 1px solid #ddd;}
			h1 {padding-left: 15px;} h3 {color: black; font-style: italic;}
			input[type=submit] {width: 10%;background-color: #4CAF50;color: white;padding: 14px 20px;margin: 8px 0;border: 4px;}
		</style>
	</head>
	<body>
	<h1> Book Trips </h1>
		<form action ='' method = 'post'>	
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Seats Left</th>
				    <th>Frequency</th>
				    <th>Price $</th>
				    <th>Special Note</th>
				    <th>Driver Name </th>
				    <th>Rating </th>
				    <th>Book</th>
				</tr>
				  <?php showtrips($dbc) ?>	
			</table>
			<input type = 'submit' name = 'booktrip' value = 'Book Now!'>
		</form>
	<h3> Pending Trips (waiting for confirmation from the driver) </h3>
		<form action = '' method = 'post'>
			<table>
				<tr>
				    <th>From</th>
				    <th>To</th>
				    <th>Date</th>
				    <th>Seats Left</th>
				    <th>Frequency</th>
				    <th>Price $</th>
				    <th>Special Note</th>
				    <th>Driver Name </th>
				    <th>Rating </th>
				    <th>Cancel Request</th>			
				</tr>
				  <?php showmytrips($dbc) ?>	
			</table>
			<input type = 'submit' name = 'cancelrequest' value = 'Cancel Request'>
		<form>
		<?php global $notripsbooked; if(isset($notripsbooked)) echo $notripsbooked;	?>			
	</body>	
</html>