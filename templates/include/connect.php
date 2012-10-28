<?php

	$host = "host=";
	$port = "port=";
	$dbname = "dbname=";
	$username = "user=";
	$password = "password=";
	
	$full = $host." ".$port." ".$dbname." ".$username." ".$password;

	$connection = pg_connect($full);

	if(!$connection)
	{
		pg_close($connection);

	}

?>
