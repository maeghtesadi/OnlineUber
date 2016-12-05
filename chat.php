<?php
//Author: Cedric Abou-Farhat #27032633 
include 'navigation.php';
require_once 'mysql_connect.php';

// opens the converation if the request comes from a booked trip
if (isset($_POST['openconvo'])) {

	foreach ($_POST as $key => $value) {
		if (is_array($value)) {

			foreach ($value as $k => $v) {
				$_SESSION['clientid'] = $v;
			}
		}
	}
}
// opens the conversation if the first message is sent by the driver
if (isset($_POST['messagefromdriver'])) {

	foreach ($_POST as $key => $value) {
		if (is_array($value)) {

			foreach ($value as $k => $v) {
				$_SESSION['clientid'] = $v;
			}
		}
	}
}
// opens the conversation if first message was sent by the rider
if (isset($_POST['messagefromrider'])) {

	foreach ($_POST as $key => $value) {
		if (is_array($value)) {

			foreach ($value as $k => $v) {
				$_SESSION['clientid'] = $v;
			}
		}
	}
}
// Finds all messages between one member and another member
function getMessage (mysqli $dbc) {
	
	global $userid;
	$talkingto = $_SESSION['clientid'];
	//returns all messages between user and another member order by ascending datetime	
	$statement = "SELECT * FROM messages WHERE (fromMember = '$userid' AND  toMember = '$talkingto') 
	OR (fromMember = '$talkingto' AND  toMember = '$userid')
	ORDER BY `time` ASC ";

	$query = mysqli_query($dbc, $statement);
// if inbox is empty
	if (mysqli_num_rows($query) == 0) {
		echo "<h3>You do not have any messages yet </h3>"; 

	}
	else {
// finds sent messages
		while ($row = mysqli_fetch_assoc($query)) {

			$message = $row['content'];
			$sender = $row['fromMember'];
			// returns first name of a specific user
			$getname = "SELECT fname FROM members where id = '$sender'";
			$q = mysqli_query($dbc, $getname);
			$namedisp = mysqli_fetch_assoc($q);

			$sendername = $namedisp['fname'];

			echo "<b>$sendername</b> <div class='bubble' background-color: blue;>
					<div class='bubbleInner'>
						$message
					</div>
				</div><br>";
		}
	}
}
// Sends a message to the current client

if (isset($_POST['send'])) {

// gets the id of the client
	global $userid;
	$talkingto = $_SESSION['clientid'];
// formats the message
	$infotopost = mysqli_real_escape_string($dbc, $_POST['content']);
//create (send) message between user and another specific user
	$statement = "INSERT INTO messages (fromMember, toMember, content) 
	VALUES ('$userid', '$talkingto', '$infotopost')";

	if (!mysqli_query($dbc, $statement))
		echo mysqli_error($dbc);

}

?>
<html>
	<head>
	  <link rel="stylesheet" type="text/css" href="chatlayout.css">
	</head>
	<body>
	 	<?php getMessage ($dbc);?>
	 	<form action = "" method = 'post'>
	 	<textarea name = 'content' placeholder='chat now!' required></textarea> 
	 	<br>
	 	<input type = 'submit' name = 'send' value = 'Send Message'>
	 	</form>
	</body> 
</html>