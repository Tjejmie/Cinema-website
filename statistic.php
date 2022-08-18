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
	<title>Statistic</title>
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
  <a href="changemovie.php">Change movie</a>
  <a href="#">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>
<h2 class="header">Statistics on orders</h2>
<div class="movieclick">
    
<div class="addmovie">
    
    <div class="table_statistic">
        
    <form method="POST" action="statistic.php">
        <input type="submit" name="nw_update1" value="Order by Date"/>
    </form>
    <form method="POST" action="statistic.php">
        <input type="submit" name="nw_update2" value="Order by Title"/>
    </form>
    <form method="POST" action="statistic.php">
        <input type="submit" name="nw_update3" value="Order by Name"/>
    </form>
    <a class="customer" href="statistic_customer.php">Would you like to see statistics for customers? </br> Click here<a>
  </div>

<table class="table_statistic">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Title</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
<!-- Rubriker för tabellerna som skapas med SQL-frågorna nedan  -->
    <?php
    if(isset($_POST['nw_update1'])){ //Vid klick av knapp skickas frågan till databasen
        $query = "SELECT movieshow.movieshowid, movieshow.date, movie.title, customer.name
        FROM (((movieshow
        INNER JOIN movie ON movie.movieid = movieshow.movieid)
        INNER JOIN ticket ON ticket.movieshowid = movieshow.movieshowid)
        INNER JOIN customer ON customer.customerid = ticket.customerid)
        ORDER BY date DESC;"; //Sorterar efter datum
    $resultat = mysqli_query($mysql_conn_admin, $query) or die("Error: ".mysqli_error($mysql_conn_admin));

    while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
        ?>
        <tr>
            <td><?php echo $rad['movieshowid']?></td>
            <td><?php echo $rad['name']?></td>
            <td><?php echo $rad['title']?></td>
            <td><?php echo $rad['date']?></td>
        </tr>

    <?php
    }
    
    echo "</tbody>
    </table>";
    }
    if(isset($_POST['nw_update2'])){ //Vid klick av knapp skickas frågan till databasen
        $query = "SELECT movieshow.movieshowid, movieshow.date, movie.title, customer.name
        FROM (((movieshow
        INNER JOIN movie ON movie.movieid = movieshow.movieid)
        INNER JOIN ticket ON ticket.movieshowid = movieshow.movieshowid)
        INNER JOIN customer ON customer.customerid = ticket.customerid)
        ORDER BY title;"; //Sorterat efter titel
    $resultat = mysqli_query($mysql_conn_admin, $query) or die("Error: ".mysqli_error($mysql_conn_admin));
    while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
        ?>
        <tr>
            <td><?php echo $rad['movieshowid']?></td>
            <td><?php echo $rad['name']?></td>
            <td><?php echo $rad['title']?></td>
            <td><?php echo $rad['date']?></td>
        </tr>

    <?php
    }
}

if(isset($_POST['nw_update3'])){ //Vid klick av knapp skickas frågan till databasen
    $query = "SELECT movieshow.movieshowid, movieshow.date, movie.title, customer.name
    FROM (((movieshow
    INNER JOIN movie ON movie.movieid = movieshow.movieid)
    INNER JOIN ticket ON ticket.movieshowid = movieshow.movieshowid)
    INNER JOIN customer ON customer.customerid = ticket.customerid)
    ORDER BY name;"; //sorterat efter namn
$resultat = mysqli_query($mysql_conn_admin, $query) or die("Error: ".mysqli_error($mysql_conn_admin));

while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
        ?>
        <tr>
            <td><?php echo $rad['movieshowid']?></td>
            <td><?php echo $rad['name']?></td>
            <td><?php echo $rad['title']?></td>
            <td><?php echo $rad['date']?></td>
        </tr>

    <?php
    }
}
?>
</div>
</div>
</body>
</html>

