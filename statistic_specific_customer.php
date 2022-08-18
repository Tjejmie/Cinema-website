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
	<title>StatisticCustomer</title>
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
  <a href="statistic.php">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>
<h2 class="header">Statistic for customer</h2>

<div class="movieclick">
    
<div class="addmovie">

<div class="moviechangeform">
<a class="customer" href="statistic_customer.php">Back to customers<a>

</div>
<table class="table_statistic">
    <thead>
        <tr>
            <th>TicketID</th>
            <th>Movie</th>
            <th>Customer name</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
<?php
    $id=$_GET['customerid']; //Hämtar ID från tidigare sida
    $query = mysqli_query($mysql_conn_admin, "SELECT ticket.ticketid, movie.title, customer.name, movieshow.date
    FROM (((ticket
    INNER JOIN movieshow ON movieshow.movieshowid = ticket.movieshowid)
    INNER JOIN customer ON customer.customerid = ticket.customerid)
    INNER JOIN movie ON movie.movieid = movieshow.movieid)
    WHERE customer.customerid = '{$id}'
    ORDER BY date DESC;"); //Hämtar information från filmen baserat på tidigare sida

    while($rad = mysqli_fetch_array($query)) //Loopar igenom all önskad data från databasen 
	{
        ?>
        <tr>
        <td><?php echo $rad['ticketid']?></td>
            <td><?php echo $rad['title']?></td>
            <td><?php echo $rad['name']?></td>
            <td><?php echo $rad['date']?></td>
        </tr>

    <?php
    }
    echo "</tbody>
    </table>";
?>
</div>
</div>

</body>
</html>

