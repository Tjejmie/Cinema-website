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
<h2 class="header">All customers. Click on ID for statistic</h2>
<div class="movieclick">
    
<div class="addmovie">
    
    <div class="table_statistic">
        
    
    <a class="customer" href="statistic.php">Would you like to see statistics for orders? </br> Click here<a>
  </div>



    <table class="table_statistic">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phonenumber</th>
        </tr>
    </thead>
    <tbody>

    <?php
    
        $query = "SELECT * FROM customer";
    $resultat = mysqli_query($mysql_conn_admin, $query) or die("Error: ".mysqli_error($mysql_conn_admin));

    while($rad = mysqli_fetch_array($resultat)) //Loopar igenom all önskad data från databasen 
	{
        
        echo "<tr>";

        echo"<td><a href='statistic_specific_customer.php?customerid=", $rad['customerid'],"'>", $rad['customerid'],"</td></a><td>", $rad['name'],"</td><td>", $rad['email'],"</td><td>", $rad['phonenumber'],"</td>"; //Skickar customerid vid klick

?>
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

