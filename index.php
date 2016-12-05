<?php 
//Author: Cedric Abou-Farhat #27032633, Nicolas Horta-Adam 27171315
include 'navigation.php';
require_once 'mysql_connect.php';
// This function loads all posted messages by the admin
function loadpublicmessages (mysqli $dbc) { 
$messages = "SELECT * FROM publicmessages ORDER BY `orderposted` DESC";
$qmsgs = mysqli_query($dbc, $messages); 

// Fetches all messages from the DB
while ($row = mysqli_fetch_assoc($qmsgs)) {
    $announcement = $row['content'];
    $date = $row['posted'];
    $id = $row['orderposted'];
    global $role;

    // maximum of 75 chars per line 
    // all messages are stored in an id array in html
    echo "<b>Announcement:</b><br>";
    echo wordwrap($announcement,75,"<br>\n");
    if ($role == 1) 
    echo " <input type = 'checkbox' value = '$id' name = 'ids []'>";
    echo "<br>";
    echo "<b>Posted</b>: $date <br><br>";

    }
}
	//Gets message to be posted and uploads it to the database
function postpublicmessage (mysqli $dbc) {

        $information = mysqli_real_escape_string($dbc,$_POST['infotopost']);
        $stamp = 'NOW()';
        $statement = "INSERT INTO publicmessages (content,posted) VALUES ('$information', $stamp)";    
        mysqli_query($dbc, $statement);
    
}


// removes selected message from the index page
function removemessage(mysqli $dbc){

	foreach ($_POST as $key => $value) {
				
			if($key != 'deletemessage'){
				foreach ($value as $k => $v) {
					$statement = "DELETE FROM publicmessages WHERE orderposted = '$v'";
					mysqli_query($dbc, $statement);
			}
		
		}

	}
}

//shows total active users in Super
function showtotalusers(mysqli $dbc)
{
	$statement = "SELECT count(*) as totalmembers from members where status='active'";
	$totalusers= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($totalusers))
	{
		$total= $row['totalmembers'];
		echo "<br><br>". "<b>Total active Super users: </b>". "$total";
	}
	
}

// shows total completed trips in Super
function showtotaltrips(mysqli $dbc)
{
	$statement = "SELECT count(*) as totalCompleted from trips where completed=1";
;
	$totaltrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($totaltrips))
	{
		$total= $row['totalCompleted'];
		echo "<br><br>". "<b>Total completed trips : </b>". "$total";
	}
	
}
//shows all trips not yet completed
function showlivetrips(mysqli $dbc)
{
	$statement = "SELECT count(*) as livetrips from trips where completed=0";
;
	$livetrips= mysqli_query($dbc, $statement);
	while($row= mysqli_fetch_assoc($livetrips))
	{
		$total= $row['livetrips'];
		echo "<br><br>". "<b> There are $total trips being processed right now!!</b>";
	}
	
}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
	<style type="text/css">
		#div1 {padding: 15px; background-color: #eee9e9; width: 50%; float: left;}
		#div2 {width: 45%; float: left; padding-left: 5px; }
		textarea {width: 595px; height: 187px; resize: none;}
		input[type=submit] {background-color: #4CAF50;color: white;padding: 7px 10px;margin: 8px 0;border:4px;}
		h1, h3 {padding-left: 20px;}
	</style>
</head>
	<body>
		<h1>Welcome to Super! <i class="fa fa-car"></i></h1>
		<h3>Public Announcements - </h3>	

		<div id = "div1">
			<?php
			if (isset($_POST['postinfo']))
				postpublicmessage($dbc);
			if (isset($_POST['deletemessage']))
				removemessage($dbc);	 
			echo "<form action = '' method = 'post'>"; 
				loadpublicmessages($dbc);
				
				showlivetrips($dbc);
				showtotalusers($dbc);
				showtotaltrips($dbc);
				
			if ($role == 1)	{
			echo"<input type = 'submit' name = 'deletemessage' value = 'Delete Message'>
				</form>";
				
			}
			?>		
		</div>
		<div id = "div2">
			<?php 
			if ($role == 1) echo "
				<form action ='' method = 'post'>
				<textarea name = 'infotopost' placeholder='Everything you post here will be shared on the public home page.' required></textarea>
				<br>
				<input type = 'submit' value = 'Post Message!' name = 'postinfo'>
				</form>";
			?>
		</div>			
	</body>
</html>