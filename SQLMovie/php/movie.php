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
    <h3>Search Movie Information:</h3>
    <p>Try searching movie names to learn more about them</p><hr>
    <form action="<?php echo htmlspecialchars("searchDatabase.php");?>" method="GET">
      <div class="form-group">
        <label for="movie">Movie Search:</label>
        <input type="text" class="form-control" placeholder="Search movies" name="movie">
        <br>
        <button type="submit" class="btn btn-info" name="submitMovieForm">Submit</button>
      </div>
    </form>
  </div>
<div class="container" style="margin-left:15%">
<?php 
    //Verify we are trying to get movie
    if(isset($_GET['mid'])) { 
      $mid = $_GET['mid'];
    } else {
      exit();
    }
    $db_connection = mysqli_connect("localhost", "cs143", "", "cs143");
    if (mysqli_connect_errno()) {
      echo "Failed to connect: " . mysqli_connect_error();
    }
    if (!mysqli_select_db($db_connection, "cs143")) {
      echo "Failed to select database.";
    }
    echo "<div class='container'><hr><h3>Movie Search Results:</h3>";
    $query = "SELECT title, company, year, rating FROM Movie WHERE id = $mid;";
    $qryResult = mysqli_query($db_connection, $query);
    if($qryResult == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    $qryResultString = mysqli_fetch_row($qryResult);
    $ttle = $qryResultString[0];
    echo "<div class='table-responsive'><table class='table table-bordered table-hover'><tbody><tr><td><b>Title</strong></td><td>$qryResultString[0]</td></tr><tr><td><b>User Rating Score</b></td><td id='rating-avg'>";
    $qry2 = "SELECT rating FROM Review WHERE mid = $mid;";
    $qryResult2 = mysqli_query($db_connection, $qry2);
    if($qryResult2 == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    $addedRatings = 0;
    if(!mysqli_num_rows($qryResult2)) {
        echo "This movie does not have a review<br><a href='comment.php?mid=$mid&title=$ttle'>Add one</a>";
    } else { 
      while($qryResultString2 = mysqli_fetch_row($qryResult2)) {
        $addedRatings += $qryResultString2[0];
      }
      //Calculate rating
      $avgRating = round(($addedRatings / mysqli_num_rows($qryResult2)), 2);
      echo "$avgRating/10";
    }
    echo "</td><tr><td><b>Company</strong></td><td>$qryResultString[1]</td></tr><tr><td><b>Year</strong></td><td>$qryResultString[2]</td></tr><tr><td><b>MPAA Rating</strong></td><td>$qryResultString[3]</td></tr>";
    $query = "SELECT first, last FROM Director, MovieDirector WHERE mid = $mid AND MovieDirector.did = Director.id;";
    $qryResult = mysqli_query($db_connection, $query);
    if($qryResult == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    echo "<tr><td><b>Director:</b></td><td>";
    if(!mysqli_num_rows($qryResult)) {
        echo "No Director";
    } else {
      while($qryResultString = mysqli_fetch_row($qryResult)) {
        echo "$qryResultString[0] $qryResultString[1]<br>";
      }
    }
    echo "</td></tr>";
    $query = "SELECT genre FROM MovieGenre WHERE mid = $mid;";
    $qryResult = mysqli_query($db_connection, $query);
    if($qryResult == false) {
        echo "Error with genre " . mysqli_error();
        exit(1);
    }
    echo "<tr><td><b>Genre:</b></td><td>";
    if(!mysqli_num_rows($qryResult)) {
      echo "No Genre";
    } else {
	//Display all genres
      while($qryResultString = mysqli_fetch_row($qryResult)) {
        echo "$qryResultString[0]<br>";
      }
    }
    echo "</td></tr></tbody></table></div><hr><h3>Actors in the movie:</h3>";
    $query = "SELECT first, last, role, id FROM MovieActor, Actor WHERE mid = $mid AND MovieActor.aid = Actor.id;";
    $qryResult = mysqli_query($db_connection, $query);
    if($qryResult == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    echo "<div class='table-responsive'><table class='table table-bordered table-hover'><thead><td>Name</td><td>Role in the movie:</td></thead><tbody>";
    while($qryResultString = mysqli_fetch_row($qryResult)) {
      $name = $qryResultString[0] . " " . $qryResultString[1];
      $role = $qryResultString[2];
      $aid = $qryResultString[3];
      echo "<tr><td><a href='actor.php?aid=$aid'>$name</a></td><td>$role</td></tr>";
    }
    echo "</tbody></table></div><hr><h3>Reviews and Ratings for $ttle:</h3><a href='comment.php?mid=$mid&title=$ttle'><button type='button' class='btn btn-success'>Submit review</button></a>";
    $query = "SELECT name, time, rating, comment FROM Review where mid = $mid;";
    $qryResult = mysqli_query($db_connection, $query);
    if($qryResult == false) {
        echo "Error: " . mysqli_error();
        exit(1);
    }
    if(!mysqli_num_rows($qryResult)) {
      echo "<br><br>No Reviews for this movie<hr>";
    } else {
      while($qryResultString = mysqli_fetch_row($qryResult)) {
        $name = $qryResultString[0];
        $tme = $qryResultString[1];
        $mpaaRating = $qryResultString[2];
        $cmmnt = $qryResultString[3];
        echo "<div class='media'><div class='media-left'></div><div class='media-body'><h5 id='review-header' class='media-heading'>Rating: $mpaaRating/10</h5><p id='review-name'>User: $name</p><p id='reviews'>Date: $tme</p></div></div><p id='review-comment'>$cmmnt </p><hr>";
      }
    }
    echo "</div>";
 ?>
</div>
</body>
</html>
