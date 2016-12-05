<?php 
//Author: Cedric Abou - Farhat 27032633
//Author: Nicolas Horta-Adam #27171315
include 'navigation.php';
require_once 'mysql_connect.php';
if (isset($_POST['register'])) {

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$repassword = $_POST['repassword'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$creditcardnum = $_POST['creditnum'];

$dlicensenum = $_POST['dln'];
$inscompany = $_POST['inscompany'];
$insnumber = $_POST['insnumber'];


$wasinvited= False;
$query= "SELECT * FROM invitations";
$tofetch=mysqli_query($dbc,$query);

while($row= mysqli_fetch_assoc($tofetch)) {

  if($row['email']==$email)
    $wasinvited=True;
}
  

  if ($password == $repassword) 
  {
    if($wasinvited)
    {
      if (empty($_POST['dln']) || empty($_POST['inscompany'])  || empty($_POST['insnumber']) ) 
      {
        $register = "INSERT INTO members (fname, lname, email, password, address, DOB, ccnum) VALUES ('$fname','$lname', '$email', '$password', '$address', '$dob', '$creditcardnum')";
      } 
      else {
        $register = "INSERT INTO members (fname, lname, email, password, address, DOB, ccnum, licensenumber, insurancecompany, insurancenumber) VALUES ('$fname','$lname', '$email', '$password', '$address', '$dob', $creditcardnum, $dlicensenum, '$inscompany', '$insnumber' )";
      }
      if (mysqli_query($dbc, $register)) 
      {
        $success =  "Record created successfully! Thank you for joining Super.";
      } 
      else {
        echo "Sorry. This username is already taken.";
      }
    } 
    else{
      echo "Sorry, you were not invited to join Super";
    }
  }
  else $passwordsdontmatch = "The passwords don't match. Make sure that the passwords match.";
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="layout.css">
</head>
  <body>
  <h1> Register </h1>
  <h4 style = "font-style: italic;">A minimum of 20$ is required to be deposited in your Super funds. 
    This will be done once registered.</h4>
  <?php 
  if (isset($success)) {echo $success;}
  if (isset($passwordsdontmatch)) {echo $passwordsdontmatch;}
  ?>
    <div>
      <form action="" method ="post">
        First name:<br> <input type="text" name="fname" required><br>
        Last name:<br> <input type="text" name="lname" required><br>
        Email (login with this):<br> <input type="text" name="email" required><br>
        Password:<br> <input type="password" name="password" required><br>
        Confirm Password:<br><input type="password" name="repassword" required><br>
        Address:<br> <input type="text" name="address" required><br>
        Date of Birth (YYYY-MM-DD):<br> <input type="date" name="dob"required><br>
        Credit Card Number:<br> <input type="text" name='creditnum' required>
          <h4> (Required only for drivers). </h4>

        Driver's license Number:<br> <input type="text" name="dln"><br>
        Insurrance Company:<br> <input type="text" name="inscompany"><br>
        Insurrance Number:<br> <input type="text" name="insnumber"><br>

        <input type="submit" value="Submit" name = "register">
      </form>
    </div>
  </body>
</html>