<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';

require_once("common.php");	
require_once("parse_config.php");	
$host 		= $ini_array["host"];
$username 	= $ini_array["username"];
$password	= $ini_array["password"];
$db		= $ini_array["db"];
$port		= "";
$socket		= "";
DB::$user = $username;
DB::$password = $password;
DB::$dbName = $db;
DB::$host = $host;
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
$vid		= ((!empty($_REQUEST["vid"	]))?$_REQUEST["vid"]:0);
//$vid=77;

	/* also get some helpful hints on where this visitor has been to in the past. */
	$results = DB::query("SELECT vflatnum,vblock,vitime FROM vrecord WHERE vid=%s ORDER BY vrecordid DESC LIMIT 5", $vid);
	echo "\n";
	echo '<table border="1">';
	echo "\n";
	echo '<tr>';
	echo '<th colspan="3">';
	echo "\n";
	echo "Flats most recently Visited";
	echo '</th>';
	echo '</tr>';
	echo "\n";
	foreach ($results as $row) {
		echo '<tr>';
		echo "<td>".$row['vblock']."</td>"."<td>".$row['vflatnum']."</td>";
		echo "<td>".$row['vitime']." ".  get_weekday_by_date($row['vitime'])."</td>";
		echo '</tr>';
		echo "\n";
		/*
		foreach ($row as $key => $val) {
			echo "$key:"; 
		}
		echo "<BR>\n";
		foreach ($row as $key => $val) {
			echo "$val"; 
		}
		 */
	}
	echo '</table>';

?>
