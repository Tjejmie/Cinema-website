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
	<title>BuyTicket</title>
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

<h2 class="header">Buy ticket</h2>

<div class="movieclick">
<div class="addticket">
<div class="addticket2">

<?php
$customerid = $_SESSION['customerid'];
@$cat=$_GET['cat']; 
@$subcat=$_GET['subcat']; 
?>


<script>

function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='ticket.php?cat=' + val ;
}
function disableselect()
{
<?Php
if(isset($cat) and strlen($cat) > 0){
echo "document.f1.subcat.disabled = false;";}
else{echo "document.f1.subcat.disabled = true;";}
?>
}

</script>

<?Php
$query2="SELECT DISTINCT title,movieid FROM movie order by title";

if(isset($_POST['submit'])) 
{
    $title = mysqli_real_escape_string($mysql_conn,$_POST['cat']); 
    $date = mysqli_real_escape_string($mysql_conn,$_POST['subcat']); 
    
    $sql =
    "SELECT movieshowid FROM movieshow WHERE movieid=$title AND date='$date'";

    $result = mysqli_query($mysql_conn, $sql);

    if (mysqli_num_rows($result) === 1) {
      $row = mysqli_fetch_assoc($result);
      $movieshowid = $row['movieshowid'];
    $sql_insert = "INSERT INTO ticket(customerid,movieshowid) VALUES (
      '{$customerid}', '{$movieshowid}')";

    if(mysqli_query($mysql_conn,$sql_insert)) 
    {
        echo '<script>alert("Ticket added successfully")</script>';
    }
  }
}

echo "<form method=post name=f1>";
//Droplist1 kategori
echo "<select name='cat' onchange=\"reload(this.form)\"><option value=''>Select a movie</option>";
if($stmt = $mysql_conn->query("$query2")){
	while ($row2 = $stmt->fetch_assoc()) {
	if($row2['movieid']==@$cat){echo "<option selected value='$row2[movieid]'>$row2[title]</option>";}
else{echo  "<option value='$row2[movieid]'>$row2[title]</option>";}

  }
}else{
echo $mysql_conn->error;
}

echo "</select>";
echo "</br>";
//Droplist2 subkategori
echo "<select name='subcat'><option value=''>Select date</option>";
if(isset($cat) and strlen($cat) > 0){
if($stmt = $mysql_conn->prepare("SELECT DISTINCT date FROM movieshow where movieid=? order by date")){
$stmt->bind_param('i',$cat);
$stmt->execute();
 $result = $stmt->get_result();
 while ($row1 = $result->fetch_assoc()) {
  echo  "<option value='$row1[date]' >$row1[date]</option>";
	}

}else{
 echo $mysql_conn->error;
} 

}else{

$query="SELECT DISTINCT date FROM movieshow order by date"; 

if($stmt = $mysql_conn->query("$query")){
	while ($row1 = $stmt->fetch_assoc()) {
	
echo  "<option value='$row1[date] '>$row1[date]</option>";

  }
}else{
echo $connection->error;
}
} 
echo "</select>";

echo "</br>";
echo "<input type=submit name='submit' value='Buy Ticket'></form>";


?>

</form>
</div>
</div>
</div>
</div>

</body>
</html>

