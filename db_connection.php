<?php
$mysql_conn=mysqli_connect("localhost","users","****", "jamie");
	if (mysqli_connect_errno())
	{
	echo "Något gick fel: " . mysqli_connect_error();
	}

?>