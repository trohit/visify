<?php

error_reporting(-1);
function get_last_by_number($phone_num)
{
require_once("parse_config.php");	
$ini_array = parse_config();
$host 		= $ini_array["host"];
$username 	= $ini_array["username"];
$password	= $ini_array["password"];
$db		= $ini_array["db"];
$port		= "";
$socket		= "";
//$phone_num = htmlspecialchars(strip_tags($_REQUEST['term']));

try {
	$conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
	echo "Connected to $db at $host successfully.";
} catch (PDOException $pe) {
	die("Could not connect to the database $db:" . $pe->getMessage());
}
print "Exiting";
exit;
/*
DB::$user = $username;
DB::$password = $password;
DB::$dbName = $db;
DB::$host = $host;
 */
//print_r($ini_array);
mysql_connect($host, $username, $password);
mysql_select_db($db);

//$is_debug = true;
if ($is_debug) {
	echo "DEBUG:phone num:" . $phone_num;
}

 
  //$phone_num=$_REQUEST["phone_num"];
  //$phone_num=886330440;

  $result=mysql_query("SELECT (visitor.vid),vphone,vname FROM visitor WHERE visitor.vphone LIKE '$phone_num%' LIMIT 5");

  //print_r($result);
  //$row = mysql_fetch_assoc($result);

  while ($r[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
	  $row = end($r);
	  $row["id"]=$row['vid'];
	  $row["label"]=$row['vname'];
	  $row["value"]=$row['vphone'];
	  unset($row['vphone']);
	  unset($row['vname']);
	  unset($row['vid']);
	  $q[]=$row;
	  //print_r($row);
  }
 // print_r($r);
  //$row["id"]=$row['vid'];
  //$row["label"]=$row['vname'];
  //$row["value"]=$row['vphone'];
  //unset($row['vphone']);
  //unset($row['vname']);
  //unset($row['vid']);
  //sleep(1);
  //echo($row[0]);
  mysql_free_result($result);
  return($q);
}

// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

// The JSON standard MIME header.
header('Content-type: application/json');

// This ID parameter is sent by our javascript client.
//$phone_num = htmlspecialchars(strip_tags($_REQUEST['phone_num']));
$phone_num = htmlspecialchars(strip_tags($_REQUEST['term']));
//$phone_num = "9986439759";
$data=get_last_by_number($phone_num);
//print_r($data);
//exit;


// Here's some data that we want to send via JSON.
// We'll include the $id parameter so that we
// can show that it has been passed in correctly.
// You can send whatever data you like.
//$data = array("Hello", $phone_num, $phone_num+1);
//$data = array("Hello", $phone_num, $phone_num+1);
sleep(1);
 $json = json_encode($data);
// Send the data.
echo isset($_GET['callback'])
	? "{$_GET['callback']}($json)"
	: $json;
//echo json_encode($data);

?>

