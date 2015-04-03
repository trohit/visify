<?php
/* 
 * Fetch details of a visitor based on his contact number.
 * return as a JSON
 */ 
 
require_once("parse_config.php");	
require_once("common_lib.php");	
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

//$link = mysqli_connect("localhost","root","","test");
$link = mysqli_connect($host, $username, $password, $db);
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

$name=sanitize($_REQUEST["name"]);
$name = strtoupper($name);
//$name=$_GET["name"];
//$name="Sachin";
//$name="Testing";
//$name="soongwah";
//$name="Khatmalli Shah";
//$name="Kha";
$result=mysqli_query($link, "SELECT vname from visitor where UPPER(vname) LIKE '$name%'");

if ($result) {
	/* determine number of rows result set */
	$row_cnt = $result->num_rows;

	if ($row_cnt == 0) {
		/* no results. */
		return;
	} else if ($row_cnt == 1) {
		/* only one result, so we are sure. */
		$row = mysqli_fetch_row($result);
		echo($row[0]);
	} else {
		$out = NULL;
		while(1) {
			$row = mysqli_fetch_row($result);
			if ($row) {
				if ($out == NULL) {
					$out = $row[0];
				} else {
					$out = $out.",".$row[0];
				}
			} else {
				break;
			}
		}
		
		echo $out;

	}
	/* free result set */
	mysqli_free_result($result);
}
/* close connection */
mysqli_close($link);
/*
if (0) {
	} else {
		
	//	too many same names, unsure of result,
	//	return special value to indicate many matches.
		
		echo $row_cnt;
		return;
	}
*/
?>


