<?php
session_start();
if (isset($_SESSION['userid'])) $userid = $_SESSION['userid'];
if (isset($_SESSION['username'])) $username = $_SESSION['username'];
if (isset($_SESSION['role'])) $role = $_SESSION['role'];
if (isset($_SESSION['status'])) $status = $_SESSION['status'];
?>

<style type="text/css">
nav ul li {display: inline-block; padding: 10px; font-weight: bold;}
nav {background-color: orange;}
a {text-decoration:none; color: black; }
body {background-image: url("background.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: cover;
}
</style>

<nav>
    <ul style="list-style: none;">

        <li><a href="index.php">Home</a></li>
<?php      
        if (!isset($_SESSION['username']))
            {
            echo "<li><a href='login.php'>Login</a></li>";
            echo "<li><a href='register.php'>Register</a></li>";
            }
          else
            {
            // administrator only will be allowed to have a look at ratings.
            if ($role == 1) {
                echo "<li><a href='lowratings.php'>Member Review</a></li>";
                echo "<li><a href='adminpage.php'>Member Trip History</a></li>";
            } // roles for members
            if ($role != 1) 
                {
                echo "<li><a href='booktrips.php'>Book Now</a></li>";
                echo "<li><a href='posttrip.php'>Post Trip</a></li>";
                echo "<li><a href='mybookedtrips.php'>My Booked Rides</a></li>";
                echo "<li><a href='pending.php'>My Client Requests</a></li>";
                echo "<li><a href='mypostedtrips.php'>My Posted Trips</a></li>";
                echo "<li><a href='messagelist.php'>Inbox</a></li>";
				echo "<li><a href='invite.php'>Invite</a></li>";
                echo "<li><a href='bills.php'>Make Payment</a></li>";
                }
            echo "<li><a href='editinfo.php'>Account Info</a></li>";
            echo "<li><a href='logout.php'>Logout</a></li>";
            }
?>
    </ul>
</nav>