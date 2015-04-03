<?php
error_reporting(-1);
require_once("parse_config.php");	
$ini_array = parse_config();
$host 		= $ini_array["host"];
$username 	= $ini_array["username"];
$password	= $ini_array["password"];
$db		= $ini_array["db"];
$port		= "";
$socket		= "";
$link = mysqli_connect($host, $username, $password, $db)  or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} 
$phone_num=9886330440;
$query="SELECT vname from visitor where vphone='$phone_num' ";
if ($stmt = mysqli_prepare($link, $query)) {
	$query=mysqli_stmt_execute($stmt);
}

if($query === FALSE) {
	printf("Query failed: %s\n", mysqli_connect_error());
	exit();
}
/* store result */
mysqli_stmt_store_result($stmt);

/* bind result variables */
mysqli_stmt_bind_result($stmt, $vname);

/* fetch values */
while (mysqli_stmt_fetch($stmt)) {
	printf ("(%s)\n", $vname);
}
printf("Number of rows: %d.\n", mysqli_stmt_num_rows($stmt));

?>
