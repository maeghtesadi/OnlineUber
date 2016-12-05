<?php 
//nicolas horta-adam 27171315
//cedric abou-farhat 27032633

include 'navigation.php';
require_once 'mysql_connect.php';
// Once the edit button is pressed it will go here
if (isset($_POST['editprofile'])) {
		
//for each field that is of type post in the form, it will get the information and replace it in the db
		foreach ($_POST as $key => $value) {
			if ($value != 'Edit Profile'){
			$statement = "UPDATE members SET $key = '$value' WHERE id = '$userid'";
			mysqli_query($dbc, $statement);
			if(!$statement)
				echo mysqli_error();
			else $successupdate = "<h4 style = 'font-style: italic;'>Profile Updated.</h4>";
		}
	}
	echo $successupdate;
}

// Deletes the current account
if (isset($_POST['delaccount'])) {
	global $userid;
	$delstatement = "DELETE FROM members WHERE id = '$userid'";
	if(mysqli_query($dbc, $delstatement)) {
		echo "Your account was deleted successfully! <br>";
		session_destroy();
		echo "<h3><a href='index.php' style = 'color: red;'>CLick here to finalize</a></h3>";
	}
}

//returns all user info and prints them in their respected html fields below
// statement, query
$info = "SELECT * FROM members where id = '$userid'";
$query = mysqli_query($dbc, $info);
$foundrows = mysqli_num_rows($query);

if ($foundrows == 1) {
	$row = mysqli_fetch_assoc($query);
	$dbfname = $row['fname'];
	$dblname = $row['lname']; 
	$dbemail = $row['email']; 
	$dbpassword = $row['password']; 
	$dbaddress = $row['address']; 
	$dbbday = $row['DOB']; 
	$dbccnum = $row['ccnum']; 
	$dblicensenumber = $row['licensenumber']; 
	$dbinsurancecomp = $row['insurancecompany']; 
	$dbinsurancenumber = $row['insurancenumber'];

	//can't edit this
	$dbbalance = $row['balance'];
	$dbstatus = $row['status']; 
	$dbisadmin = ($row['isAdmin'] == 1) ? "Administrator" : "Member"; 
	$dbrating = number_format($row['rating'], 2);  


} $profile = "profile not available";

// shows how many trips user has drove for this month
function showthismonthdrives(mysqli $dbc)
{
	$t= time();
	$y= date('y',$t);
	$m=date('m', $t);
	$month=date('F',$t);
	$mn= $m+1;


	$statement = "SELECT count(DISTINCT tripID) as totaldrives from trips where driverID= '$userid' and completed= 1 and dateAndTime>='$y-$m-01 00:00:00' and dateAndTime<='$y-$mn-01 00:00:00'";

	$dmtrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($dmtrips))
	{
		$total= $row['totaldrives'];
		echo "<br><br>". "<b>Total trips I drove for in $month : </b>". "$total";
	}
	
}

// shows how many trips user has ridden for this month
function showthismonthrides(mysqli $dbc)
{
	$t= time();
	$y= date('y',$t);
	$m=date('m', $t);
	$month=date('F',$t);
	$mn= $m+1;


	$statement = "SELECT count(distinct T2.tripID) as totalrides from tripMembers as T1, trips as T2 where T1.memberID= '$userid' and T2.completed=1 and T1.memberID<>T2.driverID and T2.dateAndTime>='$y-$m-01 00:00:00' and T2.dateAndTime<='$yn-$mn-01 00:00:00'";

	$mtrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($mtrips))
	{
		$total= $row['totalrides'];
		echo "<br><br>". "<b>Total trips I rode in $month : </b>". "$total";
	}
	
}

//shows how many trips user drove for in the current year
function showthisyeardrives(mysqli $dbc)
{
	$t= time();
	$y= date('y',$t);
	$year= date('Y',$t);
	$yn=$y+1;


	$statement = "SELECT count(distinct tripID) as totalyear from trips where driverID= '$userID' and completed=1 and dateAndTime>='$y-01-01 00:00:00' and dateAndTime<='$yn-01-01 00:00:00'";

	$dytrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($dytrips))
	{
		$total= $row['totalyear'];
		echo "<br><br>". "<b>Total trips I drove for in $year : </b>". "$total";
	}
	
}

//shows how many trips user rode in the current year
function showthisyearrides(mysqli $dbc)
{
	$t= time();
	$y= date('y',$t);
	$year= date('Y',$t);
	$yn=$y+1;


	$statement = "SELECT count(distinct T2.tripID) as totalyear from tripMembers as T1, trips as T2 where T1.memberID= '$userID' and T2.completed=1 and T1.memberID<>T2.driverID and T2.dateAndTime>='$y-01-01 00:00:00' and T2.dateAndTime<='$yn-01-01 00:00:00'";

	$ytrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($ytrips))
	{
		$total= $row['totalyear'];
		echo "<br><br>". "<b>Total trips I rode in $year : </b>". "$total ";
	}
	
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="layout.css">
</head>

<body>
	<h1>My Account Info</h1>
<div>
	<form action = "" method = "post">
	First name:<br> <input type="text" name="fname" value = '<?php echo $dbfname;?>'required><br>
  	Last name:<br> <input type="text" name="lname" value = "<?php echo $dblname;?>"required><br>	
  	Email:<br> <input type="text" name="email" value = "<?php echo $dbemail;?>"required><br>
  	Password:<br> <input type="text" name="password" value = "<?php echo $dbpassword;?>"required><br>
  	Address:<br> <input type="text" name="address" value = "<?php echo $dbaddress;?>"required><br>
  	Date of Birth:<br> <input type="date" name="DOB" value = "<?php echo $dbbday;?>"required><br>
  	<?php 
  	if ($dbisadmin == 'Member') echo "
  	Credit Card Number:<br> <input type='text' name='ccnum' value = $dbccnum required><br>
  	Driver's license Number:<br> <input type='text' min = '0' name='licensenumber' value = $dblicensenumber><br>
  	Insurrance Company:<br> <input type='text' name='insurancecompany' value = $dbinsurancecomp><br>
  	Insurrance Number:<br> <input type='text' name='insurancenumber' value = $dbinsurancenumber><br>
  	Add Funds:<br> <input type='number' min='0' name = 'balance' value = $dbbalance><br>
  	";
  	?>

	<input type='submit' value='Edit Profile' name ='editprofile'>
	<input type = 'submit' value = 'Delete Account' name = 'delaccount'>
	</form>
</div>
<div>
	Privilege (Member or Admin):<br> <input type="text" value = "<?echo $dbisadmin?>"readonly><br>
	<?php 
  	if ($dbisadmin == 'Member') echo "
	Current Balance $$:<br> <input type='text' value = $dbbalance readonly><br>
  	Current Status:<br> <input type='text'  value = $dbstatus readonly><br>
  	Your Current Rating (/10):<br> <input type='text'  value = $dbrating readonly><br>
  	";
	if($role!=1){
	echo "<br><br>";
  	showthismonthdrives($dbc);
 	showthismonthrides($dbc);
 	echo "<br><br>";
 	showthisyeardrives($dbc);
 	showthisyearrides($dbc);
	}
  	?>
</div>
</body>	

</html>