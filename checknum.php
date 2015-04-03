<?php
 
require_once("parse_config.php");	
require_once("common_lib.php");//for sanity check
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
$con = mysqli_connect($host, $username, $password, $db);

// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

 
  $phone_num=sanitize($_REQUEST["phone_num"]);

  //$phone=$_GET["phone"];
  //$phone=886330440;
  $result=mysqli_result($con, "SELECT vname from visitor where vphone='$phone_num' ");
  //print_r($result);
  $row= mysqli_fetch_row($con, $result);
  //sleep(1);
  echo($row[0]);
  /*
  $find=mysqli_num_rows($result);
 
  echo $find;
   */
?>
