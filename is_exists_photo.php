<?php
# standard header
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common_lib.php");	
DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

$vid = sanitize($_REQUEST['vid']);
echo is_exists_pic($vid);

function is_exists_pic($vid)
{
	// get length of pic
	$len = DB::queryFirstField("SELECT length(vphoto) FROM visitor WHERE vid=%s", $vid);
	//echo "len is: " . $len . "\n";
	if ($len > 1000) {
		return 1;
	}
	return 0;
}

?>
