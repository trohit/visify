<?php
error_reporting(-1);
require_once("common_lib.php");

function get_names_by_unit($block, $flat, $num_limit=4)
{
	require_once("parse_config.php");	
	$ini_array = parse_config();
	$host 		= $ini_array["host"];
	$username 	= $ini_array["username"];
	$password	= $ini_array["password"];
	$db		= $ini_array["db"];
	$port		= "";
	$socket		= "";
	//print_r($ini_array);
	$con = mysqli_connect($host, $username, $password, $db) or 
		die ("<script language='javascript'>alert('Unable to connect to database')</script>");;
	//$phone_num=$_REQUEST["phone_num"];
	//$phone_num=886330440;

	$result=mysqli_query($con, "SELECT DISTINCT(vtomeet) FROM vrecord WHERE vblock='$block' AND vflatnum='$flat'ORDER BY vrecordid DESC LIMIT $num_limit");
	//$result=mysqli_query($con, "SELECT vitime, vtomeet FROM vrecord WHERE vblock='$block' AND vflatnum='$flat'ORDER BY vrecordid DESC LIMIT $num_limit");

	$response = array();

	while ($row = mysqli_fetch_assoc($result)) {
		array_push($response, $row);
	}
	// remove 0 values from the array
	$count = count($response);
	for($i = 0; $i < $count; $i++) {
		$response[$i] = array_filter($response[$i]);
	}
	//print_r($response);

	//sleep(1);
	//echo($row[0]);
	mysqli_free_result($result);
	//return($row);
	return($response);
}

// Prevent caching.
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 01 Jan 1996 00:00:00 GMT');

// The JSON standard MIME header.
header('Content-type: application/json');

// This ID parameter is sent by our javascript client.
$block = sanitize(strip_tags($_REQUEST['block']));
$flat_num = sanitize(strip_tags($_REQUEST['flatnum']));
$num_limit = sanitize(strip_tags($_REQUEST['num_limit']));

//$phone_num = "9986439759";
//$phone_num = "9886330440";
//$num_limit = 5;

$data =  get_names_by_unit($block, $flat_num, $num_limit=5);
//print_r($data);
//exit;


// Here's some data that we want to send via JSON.
// We'll include the $id parameter so that we
// can show that it has been passed in correctly.
// You can send whatever data you like.
//$data = array("Hello", $phone_num, $phone_num+1);
//$data = array("Hello", $phone_num, $phone_num+1);


//Sleeping here can give the notion of work being done ;)
//sleep(1);
// Send the data.
echo json_encode($data);

?>

