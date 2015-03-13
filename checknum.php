<?php
 
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
mysql_connect($host, $username, $password);
mysql_select_db($db);
//$link = mysqli_connect("localhost","root","","test");
//$link = mysqli_connect($host, $username, $password, $db);

//mysql_connect("localhost","root","");
//  mysql_select_db("test");
 
  $phone_num=$_REQUEST["phone_num"];

  //$phone=$_GET["phone"];
  //$phone=886330440;
  $query=mysql_query("SELECT vname from visitor where vphone='$phone_num' ");
  //print_r($query);
  $row= mysql_fetch_row($query);
  //sleep(1);
  echo($row[0]);
  /*
  $find=mysql_num_rows($query);
 
  echo $find;
   */
?>
