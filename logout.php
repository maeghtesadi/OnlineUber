<?php 
//Author: Cedric Abou-Farhat #27032633
session_start();
if (isset($_SESSION['userid'])) $userid = $_SESSION['userid'];
if (isset($_SESSION['username'])) $username = $_SESSION['username'];

// the user is currently logged in
if ($username && $userid){
	session_destroy();
	echo "You have been logged out. Thank you for using Super. <br> 
	Click here to go back to home page <a href = 'index.php'> Click Here</a>";
} 

// The user is not logged in
else echo "You are not logged in. <a href = 'index.php'> Log in here </>";
?>