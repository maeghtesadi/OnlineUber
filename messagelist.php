<?php 
//Author: Nicolas Horta-Adam #27171315
//Author: Cedric Abou-Farhat #27032633
include 'navigation.php';
require_once 'mysql_connect.php';

function loadinbox (mysqli $dbc) {
	global $userid; 

	$statement = "SELECT DISTINCT fromMember FROM messages where toMember = '$userid'";

	$query = mysqli_query ($dbc, $statement); 

	while ($row = mysqli_fetch_assoc($query)) {
		
		$from = $row['fromMember'];
		$statement2= "SELECT fname, lname from members where id='$from'";
		$query2= mysqli_query($dbc, $statement2);
		$row2=mysqli_fetch_assoc($query2);
		$outfname=$row2['fname'];
		$outlname=$row2['lname'];
		echo "<tr>
		<td>$outfname $outlname</td>
		<td>Reservation</td>
		<td><input type = 'radio' name = 'convo []' value = '$from'></td>
		<tr>";
	}
}

if(isset($_POST['send']))
{
	global $username;
	if($_POST['username']==$username)
	{
		echo "Can't message yourself!";
	}	
	else if (!empty($_POST['username']) && !empty($_POST['content']))
	{
		$to= $_POST['username'];
		$content= $_POST['content'];
		
		$findID= "SELECT members.id from members WHERE members.email = '$to'";
		$toID= mysqli_query($dbc,$findID);
		$row = mysqli_fetch_assoc($toID);
		$to=$row['id'];
		
		if (mysqli_num_rows($toID) == 0)
			echo "user doesn't exist";
		else
		{
			$addMessage = "INSERT INTO messages(fromMember,toMember,content) values('$userid','$to','$content')";
			if(mysqli_query($dbc,$addMessage))
				echo $success="Message sent!";
			else
			{
				echo "Error". $addMesssage . "<br>". mysqli_error($dbc);
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
		textarea {resize: none;  width: 20%; height: 155px;}
		iframe {width: 50%; height: 100%; float: right;}
	  </style>
	</head>
	<body>
		<h1> Inbox </h1>
		<form action ='chat.php' method = 'post'>
			<table>
				<tr>	
					<th> From </th>
					<th> Subject </th>
					<th> Select Message </th>
				</tr>
				<?php loadinbox ($dbc) ?>
			</table>
			<input type = 'submit' name = 'openconvo' value = 'Open Message'>
		</form>
		<iframe src="https://mail.encs.concordia.ca/horde/imp/login.php"></iframe>
		<form method="post" action="">
	<h4>Message Users:</h4>
	<input type="text" style = "width: 20%;" name="username" required  placeholder="Username"><br>
	<textarea name ="content" placeholder="Message" required></textarea><br>
	<input type="submit" value="Submit" name="send">  
	</form>
	</body> 
</html>