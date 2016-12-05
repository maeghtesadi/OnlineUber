<?php>
include 'navigation.php';
require('mysql_connect.php');



if(isset($_POST['send']))
{

	$to= "";

	if(!empty($_POST['send']))
	{
		$to= $_POST['email'];
		$to= filter_var($to, FILTER_SANITIZE_EMAIL);
		
		if(!filter_var($to, FILTER_VALIDATE_EMAIL)=== false)
		{
			$addEmail = "INSERT INTO invitations values('$to');";

			if(mysqli_query($dbc, $addEmail)) 
			{	
				echo $success= "Invitation sent to '$to'";
			}
			else 
			{
				echo "Error" . $addEmail . "<br>" . mysqli_error($dbc);
			}
		}
		else
		{
			echo "$to invalid email";
		}
	
	}
	

}

?>

<!DOCTYPE HTML>
<html>
<body>

	<form method="post" action="">
	enter email you want to invite: <input type="email" name="email" value= "<?php echo $to;?>">
	
		<input type="submit" value="Submit" name="send">  
	</form>

</body>
</html>