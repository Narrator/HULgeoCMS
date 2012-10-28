<?php

	$host = "host=localhost";
	$port = "port=5432";
	$dbname = "dbname=hul_prod";
	$username = "user=hul";
	$password = "password={DIY}Syndr*me";
	
	$full = $host." ".$port." ".$dbname." ".$username." ".$password;

	$connection = pg_connect($full);

	if(!$connection)
	{
		pg_close($connection);

	}

?>
