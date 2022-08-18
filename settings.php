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
<?php  if (isset($_SESSION['email'])|| isset($_SESSION['username'])) : ?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Settings</title>
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
</div>
</nav>
    <?php endif ?>
</div>
<h2 class="header">Change Contact Information</h2>
<div class="movieclick">
<div class="addmovie">

<div class="moviechangeform">
<form action="" method="post"> 
<?php if (isset($_GET['error'])) { ?> 

<p class="error"><?php echo $_GET['error']; ?></p>

<?php } ?>
    <label>Name</label>
    <input type="text" name="name" placeholder="Full Name"><br>
    
    <label>Email:</label>
    <input type="text" name="email" placeholder="Email"><br>

    <label>Phonenumber:</label>
    <input type="text" name="phonenumber" placeholder="Phonenumber"><br>

    <button type="submit" name="registerBtn">Change contact information</button>

</form>

</div>
<?php
    $customerid = $_SESSION['customerid']; //Hämtar customerID från aktiv session
    $query = mysqli_query($mysql_conn, "SELECT * FROM customer WHERE customerid='$customerid'"); 

    $rad = mysqli_fetch_array($query);
	if (isset($_POST['registerBtn'])){ //Hämtar och deklarerar all data från det ovanstående formuläret
        $name = $_POST['name']; 
        $email = $_POST['email']; 
        $phonenumber = $_POST['phonenumber']; 

    if ($name != "" && $email != "" && $phonenumber != ""){ //Verifierar att ingen data i formulären är tom
    mysqli_query($mysql_conn, "UPDATE customer SET name='$name', email='$email', phonenumber='$phonenumber' WHERE customerid='{$customerid}'"); 
    
    echo "<div class='signinmessage'>Your contact information was changed</div>"; //Bekräftelsemeddelande om ändringen är lyckad
 
    }
    else
header("Location: settings.php?error=Please fill out all required fields."); //Felmeddelande om man inte fyllt i alla alternativ i formuläret
}
    echo "<div class='moviechange'>";
	echo $rad['name'];
    echo "</br>"; 
    echo "Email: ". $rad['email'];
	echo "</br>"; 
    echo "Phonenumber: ".$rad['phonenumber'];
	echo "</br>"; 
	echo "</div>"; 
?>

</div>
</div>
</body>
</html>

<?php endif ?>