<?php 
//Author: Cedric Abou-Farhat #27032633
session_start();
require_once 'mysql_connect.php';
// if the login btn is pressed it will go here.
if(isset($_POST['loginbtn'])) {

	$username = $_POST['username']; // form username
	$password = $_POST['password']; //form password

	$finduser = "SELECT * FROM members WHERE email = '$username'";
	$query = mysqli_query($dbc, $finduser);
	$foundrows = mysqli_num_rows($query); // returns the number of found rows

	// if there's 1 row, this means that the user was found 
   if ($foundrows == 1){

        $row = mysqli_fetch_assoc($query);
        $dbname = $row['fname'];
        $dbusername = $row['email'];
        $dbpassword = $row['password']; 
        $dbid = $row['id'];
        $dbisadmin = $row['isAdmin'];
        $dbstatus = $row['status'];
    // verifies that the user is not suspended    
        if ($dbstatus != "suspended") {
        
        if($password == $dbpassword) {

            $_SESSION['username'] = $dbusername;
            $_SESSION['userid'] = $dbid;
            $_SESSION['role'] = $dbisadmin; // checks if admin
            $_SESSION['status'] = $dbstatus;

 
            } else $wrongpasserror =  "<h3>The password you've provided does not match the username, try again!</h3>";
        } else $badstatus = "<h3>Your account has been suspended, in order to log in you must have an active account.</h3>";
    } else $nouserfound = "<h3>No user found</h3>";
}

?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="layout.css">
<style>
input[type=text], input[type=password] {width: 60%;padding: 6px 5px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;border-radius: 4px;}
input[type=submit] {background-color: #4CAF50;color: white;padding: 7px 10px;margin: 8px 0;border:4px;}
div {border-radius: 5px;background-color: #f2f2f2;padding: 20px; width: 20%;}
</style>
</head>
<body>

<?php include 'navigation.php';
 
$login = "<H1> Login </h1>";

$form = "<div><form action='' method ='post'>	
  Username:<br>
  <input type='text' name='username' required>
  <br>
  Password:<br>
  <input type='password' name='password' required>
  <br>
  <input type='submit' value='Submit' name ='loginbtn'>
</form></div>";

if (!isset($_SESSION['username'])){
echo $login;
echo $form;
} else {
    echo "You are now signed in to Super. <br>";
    echo "Welcome, <b>$dbname</b>!<br>";
}

echo $wrongpasserror;
echo $badstatus;
echo $nouserfound;
?>

</body>
</html>