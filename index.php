<?php 
include 'db_connection.php'; //Hämtar kopplingssträngen från db_connection
session_start();

//Avbryter sessionen och kunden loggas ut
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="master.css">
 

</head>
<body>
<header>
	<div class="headerlogo">
		<img class="logo" src="icon.jpg" alt="Picture of logo">
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
  <a href="changemovie.php">Change movie</a>
  <a href="statistic.php">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>

<!-- Meddelande när kunden loggar in. visas bara om kunden är inloggad -->
<div class="loginmessage"> 
  
    <?php  if (isset($_SESSION['email'])) : ?>
        Welcome 
        <strong>
            <?php echo $_SESSION['name']; ?>
        </strong>

        <?php endif ?>
    </div>



<h2 class="titelstartpage">New movies at the Cinema</h2>

<div class="movies">

<?php
    
    $query = "SELECT movie.title, movie.category, movie.runtime, movie.poster, movie.movieid
	FROM movie
	LIMIT 3"; //Kod som hämtar titel och filmomslag som ska visas på startsidan. Hämtar också kategori och speltid som skickas till nästa sida vid klick. Limit på 3 filmer

    $resultat = mysqli_query($mysql_conn, $query) or die("Error: ".mysqli_error($mysql_conn)); //Fråga mot databasen med två argument: strängen från db_connection som har koppling mot databasen samt SQL fråga

	while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
	echo "<div class='movie1'>";  
	echo $rad['title']; //Presenterar titel för de tre filmerna i olika divar (div movie1)
	echo "</br>"; 
	echo"<a href='onclickInfo.php?title=", $rad['title'],"'><img src=/F_images/", $rad['poster']," width='260' height='330' alt='movie_cover' /></a>";
	//Presenterar omslagsbild för filmerna samt skickar användaren + data vid klick av bild
	
	echo "</div>"; 

	}
?>
</div>

<h2 class="secondheader">Future performances </h2>
<div class="futuremovies">
    
<table class="table_statistic"> <!-- tabell som visar framtida spelningar. Sorterad på enbart aktiva datum -->
    <thead>
        <tr>
            <th>Movie</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>

    <?php
    
        $query = "SELECT movie.title, movieshow.date, movie.movieid
        FROM (movieshow
        INNER JOIN movie ON movie.movieid = movieshow.movieid)
        WHERE movieshow.date >= CURRENT_TIMESTAMP
        ORDER BY date DESC;";
    $resultat = mysqli_query($mysql_conn, $query) or die("Error: ".mysqli_error($mysql_conn));

    while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
        
        echo "<tr>";
    
        echo"<td><a href='onclickInfo.php?title=", $rad['title'],"'>", $rad['title'],"</td><td>", $rad['date'],"</td></a>";
?>
        </tr>
    <?php
    }
    echo "</tbody>
    </table>";
    ?>
</div>
</body>
</html>

