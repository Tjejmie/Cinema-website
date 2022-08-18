<?php 
include 'db_connection_admin.php'; //Hämtar kopplingssträngen från db_connection
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
	<title>AddShow</title>
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

<h2 class="header">Add show</h2>

<div class="movieclick">
<div class="addmovie">
<?php

$sql = "SELECT * FROM `movie`";
$all_movies = mysqli_query($mysql_conn_admin,$sql);

if(isset($_POST['submit'])) 
{
    $date = mysqli_real_escape_string($mysql_conn_admin,$_POST['date']); //data för datum sparas i en variabel
    $id = mysqli_real_escape_string($mysql_conn_admin,$_POST['movieid']); //data för movieid sparars i en variabel
    $query = mysqli_query($mysql_conn_admin, "SELECT * FROM movieshow WHERE date='{$date}' AND movieshowid='{$id}'"); //Verifierar att filmen skapades

if (mysqli_num_rows($query) != 1){

    $sql_insert =
    "INSERT INTO `movieshow`(`date`, `movieid`)
        VALUES ('$date','$id')";

    if(mysqli_query($mysql_conn_admin,$sql_insert)) 
    {
        echo '<script>alert("Show added successfully")</script>';//Meddelandet som kommer fram om det lyckades
    }
}
else
echo '<script>alert("Show already exist")</script>';

}

?>
<form method="POST">
    <label>Date:</label>
    <input type="text" name="date" required><br>
    <label>Select a movie</label>
    <select name="movieid">
        <?php
            while ($movieid = mysqli_fetch_array( //loop som hämtar data (alla filmer) från $all_movies variabeln och visar alla individuellt
                    $all_movies,MYSQLI_ASSOC)):;
        ?>
            <option value="<?php echo $movieid["movieid"];
            ?>">
                <?php echo $movieid["title"]; //alla filmtitlar visas i en dropdownmeny
                ?>
            </option>
        <?php
            endwhile;
        ?>
    </select>
    <br>
    <input type="submit" value="Add show" name="submit">
</form>
<br>
</div>
</div>
</div>
</div>
</body>
</html>

