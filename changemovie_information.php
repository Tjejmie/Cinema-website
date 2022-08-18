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
  <a href="changemovie.php">Change movie</a>
  <a href="statistic.php">Statistics</a>
  <a href="addshow.php">Add Show</a>
</div>
</nav>
    <?php endif ?>
</div>
<h2 class="header">Change movie</h2>

<div class="movieclick">
<div class="addmovie">
<div class="moviechangeform">

<!-- Formulär för att ändra film. Skickar inget i action då den behöver hämta ID från förgeånde webbsida -->
<form action="" method="post"> 
<?php if (isset($_GET['error'])) { ?> 

<p class="error"><?php echo $_GET['error']; ?></p>

<?php } ?>
    <label>Title</label>
    <input type="text" name="title" placeholder="Movie title"><br>
    
    <label>Category:</label>
    <input type="text" name="category" placeholder="Category"><br>

    <label>Runtime:</label>
    <input type="text" name="runtime" placeholder="Runtime (min)"><br>


    <label>Poster:</label>
    <input type="text" name="poster" placeholder="Poster name"><br>

    <button type="submit" name="registerBtn">Change movie</button>

</form>
</div>

<?php
    $id=$_GET['movieid']; //Hämtar ID från tidigare sida
    $query = mysqli_query($mysql_conn_admin, "SELECT * FROM movie WHERE movieid='{$id}'"); //Hämtar information från filmen baserat på tidigare sida

    $rad = mysqli_fetch_array($query);
	if (isset($_POST['registerBtn'])){ //Hämtar all data från det ovanstående formuläret
        $title = $_POST['title']; 
        $runtime = $_POST['runtime']; 
        $category = $_POST['category']; 
        $poster = $_POST['poster'];
        
    
    if ($title != "" && $runtime != "" && $category != "" && $poster != "" ){ //Verifierar att ingen data i formulären är tom
    mysqli_query($mysql_conn_admin, "UPDATE movie SET title='$title', runtime='$runtime', category='$category', poster='$poster' WHERE movieid='{$id}'"); 
    
    
    echo "<div class='signinmessage'>Your movie was changed</div>";
 
    }
    else
header("Location: addmovie.php?error=Please fill out all required fields."); //Felmeddelande om man inte fyllt i alla alternativ i formuläret
}
    echo "<div class='moviechange'>";
	echo $rad['title'];
    echo "</br>"; 
    echo "Category: ". $rad['category'];
	echo "</br>"; 
    echo "Runtime: ".$rad['runtime'];
	echo "</br>"; 
    echo"<img src=/F_images/", $rad['poster']," width='260' height='330' alt='movie_cover'/>";
	echo "</br>"; 
	echo "</div>"; 
?>
</div>
</div>

</body>
</html>

