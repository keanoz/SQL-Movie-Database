<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<div class="w3-sidebar w3-light-grey  w3-card-2" style="width25%">
  <h3 class="w3-bar-item w3-light-grey">
      <a href="index.php" class="w3-bar-item w3-light-grey">CS143 Project 2</a>
  </h3>
  <div class="w3-blue w3-card-2 w3-container">
    <p>Input New Information</p>
  </div>
  <a href="php/comment.php" class="w3-bar-item w3-button">Add a Comment</a>
  <div class="w3-container w3-blue w3-card-2">
    <p>Browse Information</p>
  </div>
  <a href="php/actor.php" class="w3-bar-item w3-button">Actor Information</a>
  <a href="php/movie.php" class="w3-bar-item w3-button">Movie Information</a>
  <div class="w3-container w3-blue w3-card-2">
    <p>Search</p>
  </div>
  <a href="php/searchDatabase.php" class="w3-bar-item w3-button">Search For Actor/Movie</a>
</div>
<div class="container" style="margin-left:15%">
  <?php
      echo "Welcome to my website! Use the sidebar to navigate"
?>    
</div>
</body>
</html>
