<?php
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';
// keeps track of the member to delete
$currentStatus = array();
$currentid = array();

if (isset($_POST['changestatus'])) {
    foreach($_POST as $key => $value) {
        if (is_array($value)) {
            if ($key == 'status_') {
                foreach($value as $k => $v) {
                    array_push($currentStatus, $v);
                }
            }
            if ($key == 'ids_') {
                foreach($value as $k => $v) {
                    array_push($currentid, $v);
                }
            }
        }
    }
   // applies changes for each member 
    for ($i = 0; $i <count($currentid); $i++){
    	
    	$setstat = $currentStatus[$i];
    	$setid = $currentid[$i];
    	$statement = "UPDATE members SET status = '$setstat' WHERE id = '$setid'";
    	mysqli_query($dbc, $statement);

    }
}

// All the bad reviews are inspected by the admin
function showlowratings (mysqli $dbc) {

	$statement = "SELECT id, fname, lname, rating, status FROM members WHERE isAdmin <> 1;";
	$query = mysqli_query($dbc, $statement); 
// finds information of members with low ratings
	while ($row = mysqli_fetch_assoc($query)) {
	 	
	 	$dbid = $row['id'];
	 	$dbfname = $row['fname'];
	 	$dblname = $row['lname'];
	 	$dbrating = number_format($row['rating'],2);
	 	$dbstatus = $row['status']; 

	 	$one = ($dbstatus =="active") ? "selected" : "";
	 	$two = ($dbstatus =="inactive") ? "selected" : "";
	 	$three = ($dbstatus=="suspended") ? "selected" : "";

	 	echo "<tr><td>$dbfname</td><td>$dblname</td><td>$dbrating</td><td>$dbstatus</td>
	 	<td>
	 	<select name = 'status []' >
		  <option value='active' $one>Active</option>
		  <option value='inactive' $two>Inactive</option>
		  <option value='suspended' $three>Suspended</option>
		</select></td>
		<input type = 'hidden' value = '$dbid' name = 'ids []'>
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
		<h1>Member Review</h1>
		<h4>Use this page to activate or suspend accounts.</h4>
		<form action ='' method = 'post'>
			<table>
			  <tr>
			    <th>First Name</th>
			    <th>Last Name</th>
			    <th>Rating</th>
			    <th>Current Status</th>
			    <th>Change Status</th>
			  </tr>
			  <?php showlowratings ($dbc);?>
			</table>
			<input type = "submit" value = "Update!" name = "changestatus" style = "width: 10%;" >
		</form>
	</body>
</html>