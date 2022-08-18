<?php 
session_start(); 
include 'db_connection_admin.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>LoginAdmin</title>
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
  <a href="index.php">Home</a>
  <a href="ticket.php">Tickets</a>
  <a href="settings.php">Settings</a>
  <a href="orders.php">Orders</a>
  <a href="search.php">Search</a>
  <div class="topnav-right">
    <a href="admin.php">Log in</a>
  </div>
</div>
</nav>

<h2 class="titelstartpage">Login as admin</h2>

<div class="login">

<form action="admin.php" method="post">

        <?php if (isset($_GET['error'])) { ?>

            <p class="error"><?php echo $_GET['error']; ?></p>

        <?php } ?>
        <!-- Gör att det är möjligt att lägga till felmedelanden vid else -->

        <label>User Name (E-mail):</label>
        <input type="text" name="username" placeholder="User name"><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Password"><br> 

        <button type="submit">Login</button>
       
     </form>

     <?php
     if (isset($_POST['username']) && isset($_POST['password'])) {

        function validate($data){ //Funktion för att validera data
    
           $data = trim($data);
    
           $data = stripslashes($data);
    
           $data = htmlspecialchars($data); //konverterar specialtecken till HTML entiteter. Specialtecken översätts vilket undviker hackers från att utnyttja koden genom att injecera t.ex HTML
    
           return $data;
    
        }
        $username = validate($_POST['username']);
        $password = validate($_POST['password']);
        
        if (empty($username)) { //Felmeddelande om fältet för e-mail inte är ifylld

            header("Location: admin.php?error=User Name is required");
    
            exit();

        }else if(empty($password)){ //Felmeddelande om fältet för lösenord inte är ifylld

            header("Location: admin.php?error=Password is required");
    
            exit();
        }else{

            $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'"; //Anropar databas där email och lösenord kontrolleras
    
            $result = mysqli_query($mysql_conn_admin, $sql);
    
            if (mysqli_num_rows($result) === 1) { // Kollar om det finns ett resultat
    
                $row = mysqli_fetch_assoc($result);
    
                if ($row['username'] === $username && $row['password'] === $password) { //Ifall det ifyllda datat stämmer överens med databasen loggas admin in
      
                    $_SESSION['username'] = $row['username']; //Data som skapar en session
    

    
                    header("Location: index.php"); //Admin skickas till startsidan
    
                    exit();
                }else{

                    header("Location: admin.php?error=Incorect User name or password"); //felmeddelande data inte matchar mellan databas och det ifyllda fälten
    
                    exit();
    
                }
            }
             else{

                header("Location: admin.php?error=Incorect User name or password"); //felmeddelande om frågan till databasen är tom
    
                exit();
            }
        } 
    }
     ?>
</div>
</body>
</html>

