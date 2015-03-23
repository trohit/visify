<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	

require_once("parse_config.php");	
$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];

$is_debug = 0;
if (isset($is_debug) && $is_debug) {
	echo "<table>";

	// display details before inserting
	foreach ($_REQUEST as $key => $value) {
		echo "<tr>";
		echo "<td>";
		echo $key;
		echo "</td>";
		echo "<td>";
		echo $value;
		echo "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
//$query = "SELECT vphoto FROM visitor WHERE visitor.vid=%i", $_REQUEST['vid']);

if (isset($is_debug) && $is_debug) {
//	echo $query;
}

$vid = $_REQUEST['vid'];
//$vid = 77;

$result = DB::queryFirstRow("SELECT vphoto FROM vpic WHERE vid=%i", $vid);
if (strlen($result['vphoto']) < 32000) {
	// fallback to primary photo repo
	$result = DB::queryFirstRow("SELECT vphoto FROM visitor WHERE visitor.vid=%i", $vid);
}


$raw_data = substr($result['vphoto'],22);
$pic= base64_decode($raw_data);

if ($pic===NULL) {
	echo " its === NULL";
} else if ($pic==NULL) {
	header("Content-type: image/jpg");
	echo file_get_contents("images/missing.jpg");

	/*
	// Only if we want to resize a small image
	$pic_path = "images/missing.jpg";
	list($width, $height) = getimagesize($pic_path);
	$newwidth = 320;
	$newheight = 240;
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	$source = imagecreatefromjpeg($pic_path);
	imagecopyresized($thumb, $source, 0,0,0,0, $newwidth, $newheight, $width, $height);
	// Content type
	header('Content-Type: image/jpeg');
	imagejpeg($thumb);
	exit;
	 */
} else {

	header("Content-type: image/png");
	echo $pic;
}

exit;
?>
