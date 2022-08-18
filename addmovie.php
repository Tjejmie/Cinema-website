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
	<title>Add Movie</title>
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

<h2 class="header">Add movie</h2>



<div class="movieclick">
<div class="addmovie">
    <div class="addmovieform">

<form action="addmovie.php" method="post">
<?php if (isset($_GET['error'])) { ?> 

<p class="error"><?php echo $_GET['error']; ?></p>

<?php } ?>
<!-- Gör att det är möjligt att lägga till felmedelanden vid else -->

        <label>Title</label>
        <input type="text" name="title" placeholder="Movie title"><br>

        <label>Runtime:</label>
        <input type="text" name="runtime" placeholder="Runtime (min)"><br>

        <label>Category:</label>
        <input type="text" name="category" placeholder="Category"><br>

        <label>Poster:</label>
        <input type="text" name="poster" placeholder="Poster name"><br>

        <button type="submit" name="registerBtn">Add movie</button>

    </form>


    <?php
     if (isset($_POST['registerBtn'])){ //Hämtar all data från det ovanstående formuläret
        $title = $_POST['title']; 
        $runtime = $_POST['runtime']; 
        $category = $_POST['category']; 
        $poster = $_POST['poster']; 
 
        
if ($title != "" && $runtime != "" && $category != "" && $poster != "" ){ //Verifierar att ingen data i formulären är tom

$query = mysqli_query($mysql_conn_admin, "SELECT * FROM movie WHERE title='{$title}'"); //Anropar databasen för att se om filmen redan existerar
if (mysqli_num_rows($query) == 0){ //Är resultatet 0 går koden vidare, annars kommer ett felmeddelande

mysqli_query($mysql_conn_admin, "INSERT INTO movie(title,runtime,category,poster) VALUES (
'{$title}', '{$runtime}', '{$category}', '{$poster}'
)"); //Lägger till användaren i databasen med all ifylld data


$query = mysqli_query($mysql_conn_admin, "SELECT * FROM movie WHERE title='{$title}'"); //Verifierar att filmen skapades

if (mysqli_num_rows($query) == 1){

  $sql = "select * from movie";
  $result = mysqli_query($mysql_conn_admin, $sql) or die("Error: ".mysqli_error($mysql_conn));
  $table=array();
  while($row = $result->fetch_assoc()){
    $data[] = $row;
}
$data = json_encode($data);
 
    //skapar json fil
    $filename = 'data.json';
    if(file_put_contents($filename, $data)){
        echo 'Json file was created/updated';
    } 
    else{
        echo 'An error occured in creating/updating the file';
    }



//Om det finns en film med den titeln så betyder det att filmen lades till.
echo "</br>";
echo "<div class='signinmessage'>Your movie is added</div>";


}
else
header("Location: addmovie.php?error=An error occurred and your movie was not added."); //Felmeddelande om det inte skapades
}

else
header("Location: addmovie.php?error=The movietitle is already added."); //Felmeddelande om titlen redan är registrerad
}

else
header("Location: addmovie.php?error=Please fill out all required fields."); //Felmeddelande om man inte fyllt i alla alternativ i formuläret
}

?>

</div>
</div>
</div>
</body>
</html>

