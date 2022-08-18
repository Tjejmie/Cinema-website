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
	<title>Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="master.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
  <link type="text/css" rel="stylesheet" href="master.css">
  <style>
  #result {
   position: absolute;
   width: 100%;
   max-width:500px;
   cursor: pointer;
   overflow-y: auto;
   max-height: 400px;
   box-sizing: border-box;
   z-index: 1001;
  }
  .link-class:hover{
   background-color:#f1f1f1;
  }
  </style>
</head>
<body style="background: black">
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
  <a href="#">Search</a>
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


<h2 class="titelstartpage">Search</h2>

<div class="container" style="width:400px">
  
   <br />
   <form method="post">
    <input type="text" name="search" id="search" placeholder="Search for a movie or category" class="form-control" />
   <ul style="color:black" class="list-group" id="result"></ul>
   <input type="submit" value="Search" name="submit">

</form>
  </div>

  
<script>
$(document).ready(function(){
 $.ajaxSetup({ cache: false });
 $('#search').keyup(function(){ //kod som blir aktiv när en användare skriver något i textrutan
  $('#result').html('');
  var searchField = $('#search').val(); //Hämtar value från textboxen search
  var expression = new RegExp(searchField, "i");
  $.getJSON('data.json', function(data) { //Hämtar data från jsonfilen
   $.each(data, function(key, value){ //Hämtar varje värde som finns i jsonfilen
    if (value.title.search(expression) != -1 || value.category.search(expression) != -1) //Gör det möjligt att söka både titel och kategori. Data som existerar kommer köra en block av kod
    {
     $('#result').append('<li class="list-group-item link-class"> '+value.title+' | <span class="text-muted">'+value.category+'</span></li>'); //Block av kod presenteras.
     $('#result').on('click', 'li', function() { //Vid klick av något av resultatet i listan sker koden nedanför
  var click_text = $(this).text().split('|');
  $('#search').val($.trim(click_text[0])); //Visar titel och inte kategori i rutan efter klick
  $("#result").html('');
 });
    }
   });   
  });
 });
 
 
});
</script>

<?php
if(isset($_POST['submit'])) 
{
    $title = mysqli_real_escape_string($mysql_conn,$_POST['search']); //data för film-titel sparas i en variabel
    
    header("Location: onclickinfo.php?title=" . $title); //vid klick av knapp skickas användare till onclickinfo med information om den valda filmen

}

?>



    
</div>
</body>
</html>

