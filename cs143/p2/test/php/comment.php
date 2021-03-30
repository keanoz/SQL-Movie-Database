<!DOCTYPE html>
<html lang="en">
  <title>Movie Database</title>
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
    <p>Search Database</p>
  </div>
   <a href="searchDatabase.php" class="w3-bar-item w3-button">Search</a>
</div>
  <div class="container" style="margin-left:15%">
    <h3> Add Movie Review </h3><hr>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="GET">
      <div class="form-group">
        <label for="movie">Movie:</label>
        <select multiple class="form-control" name="movie[]">
          <?php
            if(isset($_GET['title'])) { 
              $title = $_GET['title'];
              $mid = $_GET['mid'];
              echo "<option selected='selected' value=$mid>$title</option>";
            } else {
              $db_connection = mysqli_connect("localhost", "cs143", "", "cs143");
              if (mysqli_connect_errno()) {
                echo "Failed to connect: " . mysqli_connect_error();
              }
              if (!mysqli_select_db($db_connection, "cs143")) {
                echo "Failed to select database.";
              }
              //Search for movie
              $qry = "SELECT id, title, year FROM Movie ORDER BY title;";
              $result = mysqli_query($db_connection, $qry);
              if($result == false) {
                echo "Error: " . mysqli_error();
                exit(1);
              }
	      //Display movie info
              while($movieInfo = mysqli_fetch_assoc($result)) {
                echo "<option value=" . $movieInfo['id'] . ">" . $movieInfo['title'] . " (" . $movieInfo['year'] . ")</option>";
              }
            }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" placeholder="Enter your name" name="name">
      </div>
      <div class="form-group">
        <label for="rating">Rating:</label>
        <select class="form-control" name="rating">
          <option>Select a rating</option>
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
        </select>
      </div>
      <div class="form-group">
        <label for="comment">Comment :</label>
        <textarea class="form-control" placeholder="Comment Here" rows="6" name="comment"></textarea>
      </div>
      <button type="submit" class="btn btn-info" name="submitForm">Submit</button>
    </form><hr>
  </div>
<div class="container" style="margin-left:15%">
<?php
    $presentError = false;
    $correctionMsg = "Please correct these errors:<br>";  
    if(isset($_GET['submitForm'])) { 
      if(!isset($_GET['movie'])) { 
        $correctionMsg .= "Select a movie.<br>";
        echo "<div class='container' id='error-string'>";
        echo $correctionMsg;
        exit(1);
      }
      if(empty($_GET['name'])) { 
        $correctionMsg .= "- Enter your name.<br>";
        echo "<div class='container' id='error-string'>";
        echo $correctionMsg;
        exit(1);
      }
    } else { 
      exit();
    }
    $db_connection = mysqli_connect("localhost", "cs143", "", "cs143");
    if (mysqli_connect_errno()) {
      echo "Error connecting: " . mysqli_connect_error();
    }
    if (!mysqli_select_db($db_connection, "cs143")) {
      echo "Error Selecting database.";
    }
    if(isset($_GET['title'])) {
      $movie = $title;
    } else { 
      $numOfMovies = $_GET['movie'];
      foreach($numOfMovies as $addMovie) {
        $movie = $addMovie;
      } 
    }
    $name = $_GET['name'];
    $rating = $_GET['rating'];
    if(empty($_GET['comment'])) {
      $comment = "";
    }
    else {
      $comment = $_GET['comment'];
    }
    $time = date("Y-m-d H:i:s");
    $qry = "INSERT INTO Review (name, time, mid, rating, comment) SELECT * FROM (SELECT '$name', '$time', $movie, '$rating', '$comment') AS tmp WHERE NOT EXISTS (SELECT name, time, mid, rating, comment FROM Review WHERE  name = '$name' AND time = '$time' AND mid = $movie AND rating = '$rating' AND comment = '$comment') LIMIT 1;";
    echo "<div class='container'>";
    mysqli_query($db_connection, $qry);
    if(mysqli_affected_rows($db_connection) === 0) {
      echo "<div id='error-string'><u>Error: Review already added</u><br><hr></div>";
    } else {
      echo "<div id='success-string'>Added review <br></div><a href='movie.php?mid=$movie'>Click here to see the movie</a><hr></div>";
    }
    echo "</div>";
    
    mysqli_close($db_connection);
?>
</div>
</body>
</html>
