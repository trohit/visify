<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> Visitor Check-in Submission</title>
    <style>
        body{font-family:Sans-Serif;}
        canvas{position:absolute; left: -9999em;}
        #button{background-color: Yellow; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #mandatory{background-color: Green; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #preview{margin: 20px 0;}
        label{display: block;}
</style>
<link rel="stylesheet" type="text/css" href="css/view.css" media="all">
<link rel="stylesheet" href="css/parsley.css">
<link rel="stylesheet" href="css/zdnet.css">
<script type="text/javascript" src="js/view.js"></script>

</head>
<body id="main_body" align="center" class="appnitro">
	<div id="form_container">
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
//phpinfo();

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");	
display_menu_common("Visitor Checkin");

?>
<div align="center">
	<img src='images/thankyou-banner.jpg' title='Thank You'/>
</div>
<div align="center">
<?php
/* check if the form was submitted. */
#if ($_SERVER['REQUEST_METHOD'] != 'POST') {
#	exit(1);
#}

require_once("parse_config.php");	

$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

$is_debug 	= false;
$is_verbose 	= false;
#$is_debug 	= true;
#$is_verbose 	= true;

$is_returning_visitor = false;

if($is_verbose) {
	phpinfo();
}

/* extract all the data - PARSING. */
// mandatory fields.
$vname 			= $_POST["name"];
$vphone			= $_POST["phone_num"];

//optional fields.
$vgender 		= ((!empty($_POST["gender"	]))?$_POST["gender"     ]:NULL);
$vage			= ((!empty($_POST["age"		]))?$_POST["age"     ]:0);
$vaddress		= ((!empty($_POST["vaddress"	]))?$_POST["vaddress"]:0);
$vcomments		= ((!empty($_POST["comments"	]))?$_POST["comments"]:"");

$start_time		= date("Y-m-d H:i:s");
$vctime			= $start_time;
$vmtime			= $start_time;
$vatime			= $start_time;
$vphoto 		= ((!empty($_POST["picinfo_hidden"]))?$_POST["picinfo_hidden"]:NULL);

$vid		= ((!empty($_POST["vid"	]))?$_POST["vid"]:0);


$is_exists_pic		= ((!empty($_POST["is_exists_pic"]))?$_POST["is_exists_pic"]:false);

if (isset($is_debug) && $is_debug) {
	echo "<BR>DEBUG ENABLED<BR>";
	DB::debugMode();
}
//assert($is_returning_visitor == true);
if ($vphoto != NULL) {
	DB::update('visitor', array( 'vphoto' => $vphoto), "vid=%i", $vid);
	$counter = DB::affectedRows();
	//echo "Pic updated with vphoto len". strlen($vphoto)." \n";
	if (is_debug) {
		echo "Pic updated with vphoto len("; 
		echo strlen($vphoto)/1024;
		echo "KB) \n";
		echo "<br>";
		echo "last few pic chars:".md5($vphoto);
		echo $counter . " rows affected.";
		echo "<br>";
	}
}
DB::update('visitor', array( 
	'vname' => $vname,
	'vphone' => $vphone,
	'vcomments' => $vcomments,
), "vid=%i", $vid);
echo "SUCCESSFULLY updated details of Visitor\n";
?>
</body>
</html>
