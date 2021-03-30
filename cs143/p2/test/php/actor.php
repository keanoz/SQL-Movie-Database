<!DOCTYPE html>
<html lang="en">
  <title>Movie Database</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
<body>
<div class="w3-sidebar w3-light-grey  w3-card-2" style="width20%">
  <h3 class="w3-bar-item w3-light-grey">
      <a href="../index.php" class="w3-bar-item w3-light-grey">CS143 Project 2</a>
  </h3>
  <div class="w3-blue w3-card-2 w3-container">
    <p>Input New Information</p>
  </div>
  <a href="comment.php" class="w3-bar-item w3-button">Add Review</a>
  <div class="w3-container w3-blue w3-card-2">
    <p>Browse Information</p>
  </div>
  <a href="actor.php" class="w3-bar-item w3-button">Actor Information</a>
  <a href="movie.php" class="w3-bar-item w3-button">Movie Information</a>
  <div class="w3-container w3-blue w3-card-2">
    <p>Search</p>
  </div>
 <a href="searchDatabase.php" class="w3-bar-item w3-button">Search</a>
</div>
 <div class = "container" style="margin-left:15%">
    <h3>Search Actor Information:</h3>
    <p>Search actor named to learn more about them</p><hr>
    <form action="<?php echo htmlspecialchars("searchDatabase.php");?>" method="GET">
      <div class="form-group">
        <label for="actor">Actor Search:</label>
        <p>Search using FirstName LastName</p>
        <input type="text" class="form-control" placeholder="Search Actors:" name="actor">
        <br>
        <button type="submit" class="btn btn-info" name="submitActorForm">Submit</button>
      </div>
    </form>
  </div>
<div class="container" style="margin-left:15%">
  <?php
    if(isset($_GET['aid'])) { 
      $aid = $_GET['aid'];
    } else {
      exit();
    }
    $db_connection = mysqli_connect("localhost", "cs143", "", "cs143");
    if (mysqli_connect_errno()) {
      echo "Error Connecting: " . mysqli_connect_error();
    }
    if (!mysqli_select_db($db_connection, "cs143")) {
      echo "Error Connecting";
    }
    echo "<div class='container'>";
    echo "<hr><h3>Actors:</h3>";
    $qry = "SELECT first, last, sex, dob, dod FROM Actor WHERE id = $aid;";
    $result = mysqli_query($db_connection, $qry);
    if($result == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    echo "<div class='table-responsive'><table class='table table-bordered table-hover'>";
    echo "<thead><tr><td>Name:</td><td>Sex:</td><td>Date of Birth:</td><td>Date of Death:</td></tr></thead><tbody>";
    $row = mysqli_fetch_row($result);
    $name = $row[0] . " " . $row[1];
    $sx = $row[2];
    $doB = $row[3];
    if (empty($row[4])) {
      $doD = "N/A";
    } else {
      $doD = $row[4];
    }
    echo "<tr><td>$name</td><td>$sx</td><td>$doB</td><td>$doD</td></tr></tbody></table></div>";
    echo "<hr><h4>Movies that $name appears in:</h4>";
    $qry = "SELECT mid, role FROM MovieActor WHERE aid = $aid;";
    $result = mysqli_query($db_connection, $qry);
    if($result == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    echo "<div class='table-responsive'><table class='table table-bordered table-hover'>";
    echo "<thead><tr><td>Movie</td><td>MPAA Rating</td><td>Year</td><td>Company</td><td>Role in Movie</td></tr></thead><tbody>";
    while($row = mysqli_fetch_row($result)) {
      $mid = $row[0];
      $role = $row[1];
      $qry = "SELECT title, rating, year, company FROM Movie WHERE id = $mid;";
      $result2 = mysqli_query($db_connection, $qry);
      while($row2 = mysqli_fetch_row($result2)) {
        $ttle = $row2[0];
        $rtng = $row2[1];
        $yr = $row2[2];
        $cpny = $row2[3];
        echo "<tr><td><a href='movie.php?mid=$mid'>$ttle</td><td>$rtng</td><td>$yr</td><td>$cpny</td><td>$role</td></tr>";
      }
    }
    echo "</tbody></table></div><br></div>";
    mysqli_close($db_connection);
?>
</div>
</body>
</html>
