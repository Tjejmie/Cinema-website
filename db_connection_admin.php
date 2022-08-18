<?php
$mysql_conn_admin=mysqli_connect("localhost","jamie","****", "jamie");
	if (mysqli_connect_error())
	{
	echo "Något gick fel: " . mysqli_connect_error();
	}

?>