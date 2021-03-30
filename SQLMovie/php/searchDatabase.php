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
  <div class="container" style="margin-left:15%">
    <h3>Search Results:</h3><hr>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET">
      <div class="form-group">
        <label for="search">Search:</label>
        <input type="text" class="form-control" placeholder="Search actors/movies" name="search">
        <br>
        <button type="submit" class="btn btn-info" name="submitSearchForm">Submit</button>
      </div>
    </form>
  </div>
  <div class="container" style="margin-left:15%">
  <?php
    //Variables to determine if we show actors or movies and actors
    $selectToShowActor = $selectToShowMovie = $submittedSearchForm = false;
    $displayActor = $showMovie = true;
    $db_connection = mysqli_connect("localhost", "cs143", "", "cs143");
    if (mysqli_connect_errno()) {
      echo "Error connecting to " . mysqli_connect_error();
    }
    if (!mysqli_select_db($db_connection, "cs143")) {
      echo "Error selecting database";
    }
    if(isset($_GET['submitActorForm']) || isset($_GET['submitSearchForm'])) {
      if(isset($_GET['submitActorForm'])) {
        $paramaterArray = explode(" ", $_GET['actor']);
        $showMovie = false;
      } else {
        $paramaterArray = explode(" ", $_GET['search']);
        $showMovie = true;
      }
      $firstName = $paramaterArray[0];
      $lastName = $paramaterArray[1];
      if(!empty($lastName)) {
        $qry = "SELECT first, last, dob, dod, id FROM Actor WHERE (first LIKE LOWER('%$firstName%') AND last LIKE LOWER('%$lastName%')) OR (first LIKE LOWER('%$lastName%') AND last LIKE LOWER('%$firstName%')) ORDER BY last, first ASC, dob ASC, dod ASC;";
      } else {
         $qry = "SELECT first, last, dob, dod, id FROM Actor WHERE first LIKE LOWER('%$firstName%') OR last LIKE LOWER('%$firstName%') ORDER BY last, first ASC, dob ASC, dod ASC;";
      }
      $actorResult = mysqli_query($db_connection, $qry);
      if($actorResult == false) {
        echo "Error: " . mysqli_error();
        exit(1);
      }
    }
    if (isset($_GET['submitMovieForm']) || isset($_GET['submitSearchForm'])) {
      if(isset($_GET['submitMovieForm'])) {
        $movie = $_GET['movie'];
        $displayActor = false;
      } else {
        $movie = $_GET['search'];
      }
      $paramaterArray = explode(" ", $movie);
      $qry = "SELECT title, year, id FROM Movie WHERE title LIKE LOWER(";
      for ($i = 0; $i < count($paramaterArray); $i++) {
        if($i < (count($paramaterArray) - 1)) {
          $qry .= "'%" . $paramaterArray[$i] . "%') AND title LIKE LOWER(";
        } else {
          $qry .= "'%" . $paramaterArray[$i];
        }
      }
      $qry .= "%') ORDER BY id;";
      $movQueryAnswer = mysqli_query($db_connection, $qry);
      if($movQueryAnswer == false) {
        echo "Error for movie query " . mysqli_error();
        exit(1);
      }
    }
    echo "<div class='container'>";
    //Verify we display actor
    if($displayActor === true) {
      echo "<hr><h3>Actor Search Results:</h3>";
      echo "<div class='table-responsive'><table border=1 class='table table-hover'><thead><tr><td>Name</td><td>Date of Birth</td><td>Date of Death</td></tr></thead><tbody>";
      while($row = mysqli_fetch_row($actorResult)) {
        $name = $row[0] . " " . $row[1];
        $dOB = $row[2];
        if(empty($row[3])) {
          $dOD = "Does Not Exist";
        } else {
          $dOD = $row[3];
        }
        $aid = $row[4];
        echo "<tr><td><a href='actor.php?aid=$aid'>$name</a></td><td>$dOB</td><td>$dOD</td>";
      }
      echo "</tbody></table></div><hr>";
    }
    if($showMovie === true) {
      echo "<hr><h4>Movies Search Results:</h4>";
      echo "<div class='table-responsive'><table border=1 class='table table-hover'><thead> <tr><td align='center'><b>Title</b></td><td align='center'><b>Year</b></td></tr></thead><tbody>";
      while($row = mysqli_fetch_row($movQueryAnswer)) {
        $ttle = $row[0];
        $yr = $row[1];
        $mid = $row[2];
        echo "<tr><td><a href='movie.php?mid=$mid'>$ttle</a></td><td>$yr</td></tr>";
      }
      echo "</tbody></table></div><hr>";
    }
    echo "</div>";
    mysqli_close($db_connection);
?>
</div>
</body>
</html>
