<?php 

session_start(); 
include 'db_connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>SignUp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1" charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="master.css">
</head>
<body>
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
  <a href="search.php">Search</a>
  <?php endif ?>
  <div class='topnav-right'>
    <a href="login.php">Log in</a>
  </div>
</div>
</nav>

<h2 class="titelstartpage">Sign up</h2>

<div class="login">



<form action="signup.php" method="post">
<?php if (isset($_GET['error'])) { ?> 

<p class="error"><?php echo $_GET['error']; ?></p>

<?php } ?>
<!-- Gör att det är möjligt att lägga till felmedelanden vid else -->

        <label>User Name (E-mail):</label>
        <input type="text" name="email" placeholder="User name/Email"><br>

        <label>Name:</label>
        <input type="text" name="name" placeholder="Name"><br>

        <label>Phone-number:</label>
        <input type="text" name="phonenumber" placeholder="Phonenumber"><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Password"><br> 

        <label>Repeat password:</label>
        <input type="password" name="password2" placeholder="Password"><br>

        <button type="submit" name="registerBtn">Register</button>
        <a class="signup" href="login.php">Already a member? Log in<a>
    </form>

     <?php

        if (isset($_POST['registerBtn'])){ //Hämtar all data från det ovanstående formuläret
            $email = $_POST['email']; 
            $name = $_POST['name']; 
            $phonenumber = $_POST['phonenumber']; 
            $password = $_POST['password']; 
            $password2 = $_POST['password2']; 
            
           

if ($email != "" && $password != "" && $password2 != "" && $name != "" && $phonenumber != ""){ //Verifierar att ingen data i formulären är tom
    if ($password === $password2){ //Försäkrar att lösenorden som fyllde i matchades
        if ( strlen($password) >= 5 != false ){ //Försökrar att lösenordskraven matchar (minst 6 tecken)

$query = mysqli_query($mysql_conn, "SELECT * FROM customer WHERE email='{$email}'"); //Anropar databasen för att se om emailadressen redan har ett konto
if (mysqli_num_rows($query) == 0){ //Är resultatet 0 går koden vidare, annars kommer ett felmeddelande

mysqli_query($mysql_conn, "INSERT INTO customer(name,email,phonenumber,password) VALUES (
    '{$name}', '{$email}', '{$phonenumber}', '{$password}'
)"); //Lägger till användaren i databasen med all ifylld data

$query = mysqli_query($mysql_conn, "SELECT * FROM customer WHERE email='{$email}'"); //Verifierar att kontot skapades

if (mysqli_num_rows($query) == 1){

    //Om det finns en användare med den mailadressen så betyder det att kontot skapades.
    echo "</br>";
    echo "<div class='signinmessage'>Your account is created. Click on Log in</div>";

}
else
    header("Location: signup.php?error=An error occurred and your account was not created."); //Felmeddelande om det inte skapades
}
else
header("Location: signup.php?error=The username is already taken. Please use another."); //Felmeddelande om mailadressen redan är registrerad


        }
        else
        header("Location: signup.php?error=Your password is not strong enough. Please use another."); //Felmeddelande om lösenordet är mindre än 6 tecken 
          
    }
    else
    header("Location: signup.php?error=Your passwords didn't match."); //Felmeddelande om lösenordet inte matchade
}
else
header("Location: signup.php?error=Please fill out all required fields."); //Felmeddelande om man inte fyllt i alla alternativ i formuläret

        }

 ?>



</div>
</body>
</html>

