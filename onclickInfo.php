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
	<title>Movie</title>
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
<?php  if (isset($_SESSION['email']) || isset($_SESSION['username'])): ?> 
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
  <a href="">Change movie</a>
  <a href="statistic.php">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>

<div class="movieclick">
<div class="movies">


<?php
    // $id=$_GET['movieid']; //Hämtar titel från tidigare sida
    $id=$_GET['title'];
    $query = mysqli_query($mysql_conn, "SELECT * FROM movie
    WHERE movie.title = '{$id}'
    "); 
    
    
    while($rad = mysqli_fetch_array($query)) //Loopar igenom all önskad data från databasen 
	{
    echo "<div class='movie2'>";
    echo $rad['title'];
    echo "</br>"; 
    echo "Category: ". $rad['category'];
    echo "</br>"; 
    echo "Runtime (min): ". $rad['runtime'];
  
    echo "</div>"; 
    echo "<div class='movie3'>";
    echo"<img src=/F_images/", $rad['poster']," width='260' height='330' alt='movie_cover'/>";
    
    $movieid = $rad['movieid'];
   
    echo "</div>"; 
    
  
  }
  echo "</div>";
  $result = mysqli_query($mysql_conn,"SELECT AVG(rate) AS avg FROM rate 
  WHERE movieid = '{$movieid}'"); 
  while($row = mysqli_fetch_array($result)) { 
    echo "<div class='movies'>";
    echo "<div class='rate'>";
    $value = $row['avg'];
    echo "<p style='margin-right: 50px'>Avg rate: ". round($value, 1); "</p>";
    echo "</br>";
    
  
    if( isset($_SESSION['email']) && !empty($_SESSION['email']) ) //Kan bara ratea när kund är inloggad
    {
      $customerid = $_SESSION['customerid'];
      ?>
   
      <form method="POST">
    <label>Rate movie</label>
    <select name="rate">
    <?php
    for ($i=1; $i<=10; $i++)
    {
      ?>
          <option value="<?php echo $i;?>"><?php echo $i;?></option>
      <?php
    }
?>
            </option>
        
    </select>
  </br>
    <input type="submit" value="Rate movie" name="submit">
</form>
<?php
    }
    if(isset($_POST['submit'])) 
    {
        $rate = mysqli_real_escape_string($mysql_conn,$_POST['rate']); //data för betyg sparas i en variabel
        $query = mysqli_query($mysql_conn, "SELECT * FROM rate WHERE movieid='{$movieid}' AND customerid='{$customerid}'"); 
    
    if (mysqli_num_rows($query) != 1){
    
        $sql_insert =
        "INSERT INTO `rate`(`customerid`, `movieid`, `rate`)
            VALUES ('$customerid','$movieid','$rate')";
    
        if(mysqli_query($mysql_conn,$sql_insert)) 
        {
            echo '<script>alert("Rate added")</script>';//Meddelandet som kommer fram om det lyckades
        }
    }
    else
    echo '<script>alert("You have already rated this movie")</script>';
    
    }
    echo "</div>";

  } 

  ?>


</div>
</div>
</body>
</html>

