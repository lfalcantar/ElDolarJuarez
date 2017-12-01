<?php
	//Connect To Database
	$hostname='localhost';
	$username='jorgecobos';
	$password='01800025Eldolar2016';
	$dbname='CASAS_DE_CAMBIO';
	$usertable="CASAS_DE_CAMBIO";

	$db = new mysqli($hostname, $username, $password, $dbname);

	if($db->connect_errno > 0){
    	die('Unable to connect to database [' . $db->connect_error . ']');
	}
?>
