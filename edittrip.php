<?php 
//Author: Cedric Abou-Farhat #27032633
//Author: Nicolas Horta-Adam #27171315
include 'navigation.php';
require_once 'mysql_connect.php';
// This page will receive the request coming from mypostedtrips to open the information on a single trip to edit
// For each value edited, the system will edit the information on the trip 
 if (isset($_POST['editthistrip'])) {
    $triptoedit = $_SESSION['triptoedit'];

    foreach ($_POST as $key => $value) {
      if ($value != 'Save Changes') {

        $newstatement = "UPDATE trips SET $key = '$value' WHERE tripID = '$triptoedit'";
        mysqli_query($dbc, $newstatement);
        
        if(!mysqli_query($dbc, $newstatement))
          echo mysqli_error($dbc);
        else $success =  "Trip Updated.";
      }
    }
    echo $success;
  }
// deletes the current trip from the DB
  if (isset($_POST['deletethistrip'])) {
    $triptodelete = $_SESSION['triptoedit'];
    $deletestatement = "DELETE from trips WHERE tripID = '$triptodelete'";

    if (mysqli_query($dbc, $deletestatement)) 
      echo "Trip Deleted";
    else mysqli_error($dbc);

  }

// finds the id of the trip the member wishes to edit
if (isset($_POST['openEditPage'])) {

  foreach ($_POST as $key => $value) {
    if (is_array($value)) {
      foreach ($value as $k => $v) {
        $_SESSION['triptoedit'] = $v;
      }
    }
  }
}

// gets the updated information from the db to display it
  $triptoedit = $_SESSION['triptoedit'];
  $statement = "SELECT * FROM trips WHERE tripId = '$triptoedit'";
  $query = mysqli_query($dbc, $statement);
  $row = mysqli_fetch_assoc($query);

  $from = $row['fromStreetNum'];
  $cityfrom = $row['fromCity'];
  $postalfrom = $row['fromPostalCode'];
  $to = $row['toStreetnum'];
  $cityto = $row['toCity'];
  $tocitypostal = $row['toPostalCode'];
  $fair = $row['price'];
  $frequency = $row['frequency'];
  $special = $row['specialTripDesc'];
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
    <div>
      <form action="" method ="post">
        From (Address):<br><input type="text" name="fromStreetNum" value = '<?php echo $from;?>' required>
        City<br> <input type="text" name="fromCity" value = '<?php echo $cityfrom;?>' required><br>
        Postal Code<br> <input type="text" name="fromPostalCode" value = '<?php echo $postalfrom;?>' required><br>
        
        To:<br> <input type="text" name="toStreetnum" value = '<?php echo $to;?>' required>
        City<br> <input type="text" name="toCity" value = '<?php echo $cityto;?>' required><br>
        Postal Code<br> <input type="text" name="toPostalCode" value = '<?php echo $tocitypostal;?>' required><br>
        
        Frequency:<br> <input type="text" name="frequency" value = '<?php echo $frequency;?>'><br>
        Special Trip Description (Optional):<br><textarea type="text" name="specialTripDesc" value = '<?php echo $special;?>'></textarea><br>

        <input type="submit" value="Save Changes" name = "editthistrip">
        <input type="submit" value="Remove Trip" name = "deletethistrip">

      </form>
    </div>
  </body>
</html>