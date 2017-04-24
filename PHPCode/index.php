<?php

include('pgConnect.php');
pg_prepare($dbConn, "test", "SELECT * FROM voter WHERE fname = $1");

$voterInfo = pg_execute($dbConn, "test", array("Joshua") );
//$voterInfo = pg_fetch_array($voterInfo, null, PGSQL_ASSOC);
while( $line = pg_fetch_array($voterInfo, null, PGSQL_ASSOC))
{
	echo "\t<br>";
	foreach ($line as $value) {
	echo "\t$value\n";
	}
	echo "\t</br>\n";
}


?>