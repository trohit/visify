<?php
require_once("parse_config.php");	
require_once("common_lib.php"); //for sanity check
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
$link = mysqli_connect($host, $username, $password, $db);
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

function is_checked_out($vrecordid) {
	$query = "SELECT vitime,votime from vrecord where vrecordid=%i";
	$result = DB::queryFirstRow($query, $vrecordid);
	//print_r($result);
	if (empty(trim($result['votime']))) {
		return false;
	} else if($result['vitime'] == $result['votime']) {
		return false;
	}
	return true;
}

function when_checked_out($vrecordid) {
	$query = "SELECT vitime,votime from vrecord where vrecordid=%i";
	$result = DB::queryFirstRow($query, $vrecordid);
	//print_r($result);
	if (empty(trim($result['votime']))) {
		return false;
	} else if($result['vitime'] == $result['votime']) {
		return false;
	}
	#print_r($result);
	return $result['votime'];
	#return true;
}
function checkout_visit($vrecordid) {
	$checkout_time             = date("Y-m-d H:i:s");
	#$query = "UPDATE vrecord SET votime='$checkout_time' WHERE vrecordid=$vrecordid";
	DB::update('vrecord', array('votime' => $checkout_time), "vrecordid=%i", $vrecordid);

	$counter = DB::affectedRows();
	echo '<font size="6" color="green">Just checked out this visit at ' . $checkout_time. '</font>';
	
}
if(isset($_GET['checkout_vrecordid'])) {
	$vrecordid = $_GET['checkout_vrecordid'];
	$res = is_checked_out($vrecordid);
	if ($res) {
		// Nothing to do; aleady checked out; Ignore
		echo '<font size="6" color="red">Already checked out at ' . when_checked_out($vrecordid) . '</font>';
	} else {
		#echo "Not checked out";
		checkout_visit($vrecordid);
	}

	#$fp = fopen("/tmp/junk.txt", "a");
	#fwrite($fp, (int)$_GET['checkout_vrecordid']);
	#fwrite($fp, "\n");
	#fclose($fp);
}
?>
