<?php
	// Establish connection
	$host = "localhost";
	$user = "agh34";
	$password = "4217184";
	$dbname = "agh34";

	$connect = mysqli_connect($host, $user, $password, $dbname);

	//Error message for bad connection
	if(!mysql_connect($host, $user, $password)) {
  	exit('Error: could not establish database connection');
	}
?>
