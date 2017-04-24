<?php
$conn_string = "dbname=votedat user=postgres password=7e562d9b396eeb3!";
$dbConn = pg_connect($conn_string);
if(!$dbConn) { 
	die("Connection Failed: ". pg_last_error($db));
}

?>