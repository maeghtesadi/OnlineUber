<?php 
//Author: Cedric Abou-Farhat #27032633
//Author: Nicolas Horta-Adam #27171315
include 'navigation.php';
require_once 'mysql_connect.php';
 // finds a member's insurance coverage
$update = "SELECT * FROM members WHERE id = '$userid'";
$query = mysqli_query($dbc, $update);

$row = mysqli_fetch_assoc($query);

$insurancecomp = $row['insurancecompany'];
$license = $row['licensenumber'];
$insurancenum = $row['insurancenumber'];
$updatedstatus = $row['status'];
//checks if status was activated
if ($updatedstatus != "active") 
  echo "<h4 style = 'font-style: italic;'>Unfortunatly your status still needs to be approved before posting a trip. 
  Status approval can take up to 5 business days.</h4>";

if (isset($_POST['posttrip'])) {
  if ($updatedstatus == "active") {
      // gets user input from various all input fields
        $from = $_POST['fromStreetNum'];
        $cityfrom = $_POST['fromCity'];
        $postalfrom = $_POST['fromPostalCode'];
        $to = $_POST['toStreetNum'];
        $cityto = $_POST['toCity'];
        $tocitypostal = $_POST['toPostalCode'];
        $seats = $_POST['numSpacesAvailable'];
        $fair = $_POST['price'];
        $frequency = $_POST['frequency'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $special = $special = mysqli_real_escape_string($dbc,$_POST['specialTripDesc']);
        $datetime = $date." ".$time;
      // makes sure that the member has proper insurance coverage
        if ($insurancecomp == "") {
            $noinsurance =  "Our system indicates that you do not have proper insurance coverage.
            Please edit your profile and prove that you are qualified. "; 
      } else {
      // If he's good, the trip will be uploaded
          $statement = 
          "INSERT INTO trips (driverID, fromStreetNum, fromCity, fromPostalCode, toStreetNum, toCity, 
          toPostalCode, price, frequency, dateAndTime, specialTripDesc, numSpacesAvailable, numSpacesTaken)   
          VALUES ('$userid', '$from', '$cityfrom', '$postalfrom', '$to', '$cityto', 
          '$tocitypostal', '$fair', '$frequency', '$datetime', '$special', '$seats', $seats)";

          if (!mysqli_query($dbc, $statement)) 
              echo mysqli_error($dbc);
          else echo "Trip posted!";
        }
    } else echo "<h4 style = 'font-style: italic;'>Account is not active!</h4>";     
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="layout.css">
<style type="text/css">
    textarea {width: 379px; height: 141px; resize: none; padding: 6px;};
  </style>
</head>
  <body>
  <h1> Post a Trip </h1>
  <h4 style = "font-style: italic;"> Disclaimer: Make sure you have proper insurance coverage.</h4>
  <?if(isset($noinsurance)) echo $noinsurance;?>
    <div>
      <form action="" method ="post">
        From (Address):<br><input type="text" name="fromStreetNum" required>
        City<br> <input type="text" name="fromCity" required><br>
        Postal Code<br> <input type="text" name="fromPostalCode" required><br>
        
        To:<br> <input type="text" name="toStreetNum" required>
        City<br> <input type="text" name="toCity" required><br>
        Postal Code<br> <input type="text" name="toPostalCode" required><br>
        
        Fair:<br><input type="number" min='1' name="price" placeholder='Fair is based on distance. 4$/Km.'required><br>
        Seat Capacity:<br> <input type="number" min='1' max='6' name="numSpacesAvailable" required><br>
        Frequency:<br> <input type="text" name="frequency"><br>
        Date:<br> <input type="date" name="date" required>
        Time:<br> <input type="time" name="time" required><br>
        Special Trip Description (Optional):<br><textarea type="text" name="specialTripDesc"></textarea><br>

        <input type="submit" value="Post Offer" name = "posttrip">
      </form>
    </div>
  </body>
</html>