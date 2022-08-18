<?php 
include 'db_connection_admin.php'; //Hämtar kopplingssträngen från db_connection
session_start();

//Avbryter sessionen och kunden/admin loggas ut
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>ChangeMovie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="master.css">
</head>
<body>
<header>
	<div class="headerlogo">
        <a href="index.php">
	<img class= "logo" src="icon.jpg" alt="Picture of logo">
    </a>
</div>
<div class="headertitle">
	<h1>Cineasternas biograf</h1>
</div>
</header>

<nav>
<div class="topnav">
<?php  if (isset($_SESSION['email'])|| isset($_SESSION['username'])) : ?> 
  <a href="index.php">Home</a>
  <a href="ticket.php">Tickets</a>
  <a href="settings.php">Settings</a>
  <a href="orders.php">Orders</a>
  <a href="search.php">Search</a>
  <?php endif ?>
  <div class='topnav-right'>

<!-- Om kunden eller admin är inloggad visas "Logga ut", annars "Logga in" -->
<?php if( isset($_SESSION['email']) && !empty($_SESSION['email']) || isset($_SESSION['username']) && !empty($_SESSION['username']) )
{
	echo "<a href='index.php?logout'>Log out</a>";
}else
{ 
	echo "<a href='login.php'>Log in</a>";
} ?>

  </div>
</div>
</nav>

  <!-- navbar för admin -->
  <?php  if (isset($_SESSION['username'])) : ?> 
<nav>
	<div class="adminnav">
  <a href="addmovie.php">Add movie</a>
  <a href="#">Change movie</a>
  <a href="statistic.php">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>


<h2 class="header">Choose a movie</h2>
<div class="movieclick">
<div class="addmovie">

    <div class="addmovieform">
    <h1>List of movies:</h1>
    <?php
    
    $query = "SELECT title, movieid FROM movie
	ORDER BY movie.movieid"; //SQL fråga för att hämta alla titlar 

    $resultat = mysqli_query($mysql_conn_admin, $query) or die("Error: ".mysqli_error($mysql_conn_admin)); //Fråga mot databasen med två argument: strängen från db_connection som har koppling mot databasen samt SQL fråga

	while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all data och presenterar det 
	{
	echo "<div class='movie1'>"; 
    echo"<a href='Changemovie_information.php?movieid=", $rad['movieid'],",'>", $rad['title'],"</a>"; //Skickar data vid klick
	echo "</div>"; 
	}
?>
</div>
</div>
</div>
</body>
</html>

