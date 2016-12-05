<?php 
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';

function loadBalance ($dbc) {

global $userid;
$returnbills = "SELECT * FROM transactions WHERE toMember = '$userid'";
$query = mysqli_query($dbc, $returnbills);
$totaldue = 0;
while ($row = mysqli_fetch_assoc($query)) {

	$toId = array();

	$you = $row['toMember'];
	$amount = $row['amount'];
	$time = $row['time'];
	$to = $row['fromMember'];

	$totaldue += $amount;

	echo "<tr>
			<td>$amount</td>
			<td>$time</td>
			<td><input type = 'hidden' name = 'due []' value = '$amount'</td>	
		</tr>";
	array_push($toId, $to);	 
	}
	echo "<h3>Total Due: $totaldue</h3>";

	if (isset($_POST['pay'])) {
		$count = 0;

	foreach ($_POST as $key => $value) {
		if (is_array($value)) {
			
			foreach ($value as $k => $v) {
				$tempto = $toId[$count];

				$returnToWhoBalance = "SELECT balance FROM members WHERE id = '$tempto'";
				$query = mysqli_query($dbc, $returnToWhoBalance);

				$row = mysqli_fetch_assoc($query);
				$whoToPay = $row['balance'];

				$whoToPay += $v;

				$newbal = "UPDATE members SET balance = '$whoToPay' WHERE id = '$tempto'";
				if (!mysqli_query($dbc, $newbal)) echo mysqli_error($dbc);
				else $success = "<h3> Thank you for paying your bills </h3>";

				$count++;
			}	
		}
	}
	}
}			
?>
<!DOCTYPE HTML>
<html>
	<head>
	  <link rel="stylesheet" type="text/css" href="layout.css">
	  <style type="text/css">
	  	input[type=submit] {width: 10%;}
	  </style>
	</head>
	<body>
		<h1> Make a payment </h1>
		<form action = '' method = 'post'>
			<table>
				<tr>	
					<th> Amount </th>
					<th> Date of trip </th>
				</tr>
				<?php loadBalance ($dbc) ?>
			</table>
			<input type = 'submit' name = 'pay' value = 'Pay Now!'>
		</form>
	</body> 
</html>