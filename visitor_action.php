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
<link rel="stylesheet" href="css/zdnet.css">
<link rel="stylesheet" href="css/parsley.css">
<script type="text/javascript" src="js/view.js"></script>

</head>
<body id="main_body" align="center" class="appnitro">
	<div id="form_container">
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");	
display_menu_common("Visitor Checkin");

require_once(dirname(__FILE__) . "/print_pass.php");
?>
<div align="center">
	<img src='images/thankyou-banner.jpg' height='60' width='288' title='Thank You'/>
</div>
<div align="center">
<!--
	<img src='images/eco_floorplan.jpg' title='Please Proceed'/>
	<img src='images/redcarpet_med.jpg' title='Please Proceed'/>
-->
<?php
/* check if the form was submitted. */
#if ($_SERVER['REQUEST_METHOD'] != 'POST') {
#	exit(1);
#}

/*
DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
*/

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
	$vname			= ((!empty($_REQUEST["name"                ]))?ucwords(trim($_REQUEST["name"     ])):"");
	$vphone			= ((!empty($_REQUEST["phone_num"              ]))?trim($_REQUEST["phone_num"     ]):"");

	//optional fields.
	$vgender 		= ((!empty($_POST["gender"	]))?$_POST["gender"     ]:NULL);
	$vage			= ((!empty($_POST["age"		]))?$_POST["age"     ]:0);
	$vaddress		= ((!empty($_POST["vaddress"	]))?$_POST["vaddress"]:0);
	$vcomments		= ((!empty($_POST["comments"	]))?trim($_POST["comments"]):"");

	$start_time		= date("Y-m-d H:i:s");
	$vctime			= $start_time;
	$vmtime			= $start_time;
	$vatime			= $start_time;
	$vphoto 		= ((!empty($_POST["picinfo_hidden"]))?$_POST["picinfo_hidden"]:NULL);

	$vid		= ((!empty($_POST["vid"	]))?$_POST["vid"]:0);

	$is_exists_pic		= ((!empty($_POST["is_exists_pic"]))?$_POST["is_exists_pic"]:false);

	if (isset($is_debug) && $is_debug) {
		echo "<BR>DEBUG ENABLED<BR>";
		//DB::debugMode();
	}

	// insert a new account
	if (!$vid) {
		$is_returning_visitor = false;
		DB::insert('visitor', array(
			'vname' 		=> $vname,
			'vphone' 		=> $vphone,
			'vgender' 		=> $vgender,
			'vage'			=> $vage,
			'vaddress'		=> $vaddress, 
			'vcomments'		=> $vcomments,
			'vctime'		=> $vctime,
			'vmtime'		=> $vmtime,
			'vatime'		=> $vatime, #DB::sqleval("now()")
			'vphoto'            	=> $vphoto,
		));
		// new visitor
		$vid = DB::insertId(); // which id did it choose?!? tell me!!
		echo "Success! Inserted details of FIRST visit for NEW visitor with id $vid";
	} else {
		$is_returning_visitor = true;
		if ($is_exists_pic == "false") {
			DB::update('visitor', array( 'vphoto' => $vphoto), "vid=%i", $vid);
			//echo "Pic updated with vphoto len". strlen($vphoto)." \n";
			echo "Pic updated with vphoto len("; 
			echo strlen($vphoto)/1024;
			echo "KB) \n";

		} else {
		}

		/* handle updation of comments. */
		if ($vcomments) {
			DB::update('visitor', array( 'vcomments' => $vcomments), "vid=%i", $vid);
		}

		/* handle updation of visitor name. */
		DB::update('visitor', array( 'vname' => $vname), "vid=%i", $vid);

	}

	/* next insert record into the vrecord. */
	$vvehicle_reg_num	= ((!empty($_POST["vehicle_reg_num"		]))?strtoupper($_POST["vehicle_reg_num"]):0);
	$vvehicle_type		= ((!empty($_POST["vehicle_type"		]))?$_POST["vehicle_type"     ]:0);
	$vitime 		= $start_time;
	#$votime			= $start_time;
	$vphoto;
	$vflatnum		= $_POST["flat_num"];
	$vblock			= $_POST["block"];
	$vtomeet		= ((!empty($_REQUEST["to_meet"             ]))?ucwords(trim($_REQUEST["to_meet"     ])):"");
	$vnum_visitors		= $_POST["num_visitors"];
	$vpurpose		=  ((!empty($_POST["purpose"		]))?$_POST["purpose"     ]:0);

	//calculate the time taken to fill the form
	$curr_time = time();
	$vduration_fillup = $curr_time - $_REQUEST['hiddenFormStartTime'];

	// insert a new account
	DB::insert('vrecord', array(
		'vid' 			=> $vid,
		'vvehicle_reg_num' 	=> $vvehicle_reg_num,
		'vvehicle_type' 	=> $vvehicle_type,
		'vitime'		=> $vitime,
		//'votime'		=> $votime,
		//'vphoto'		=> $vphoto,
		'vflatnum'		=> $vflatnum,
		'vblock'		=> $vblock,
		'vtomeet'		=> $vtomeet,
		'vpurpose'		=> $vpurpose,
		'vnum_visitors'		=> $vnum_visitors,
		'vduration_fillup'	=> $vduration_fillup,
	));
	#echo "<BR>";
	$vrecordid = DB::insertId(); // which id did it choose?!? tell me!!
	if ($is_returning_visitor) {
		// Old Visitor
		echo "SUCCESS! Inserted details of visit for returning visitor with id $vid";
	}
	echo '<BR>';
	echo "Made note of the visit with recordid $vrecordid.\n";


	if ($is_exists_pic == "true" && strlen($vphoto)) {
		// if pic exists, but user clicked another pic, then push into backup pic db
		
		$result = DB::queryFirstRow("SELECT vphoto FROM vpic WHERE vpic.vid=%i", $vid);
		if (strlen($result['vphoto']) < 32000) {
			DB::insert('vpic', array( 
				'vphoto'	=> $vphoto,
				'vid'   	=> $vid,
			));
			echo "Pic inserted with len("; 
			echo strlen($vphoto)/1024;
			echo "KB) \n";
			if ((isset($is_debug) && $is_debug)) {
				echo "Old pic:" . strlen($result['vphoto']);
			}
		} else {

			DB::update('vpic', array( 'vphoto' => $vphoto), "vid=%i", $vid);
			echo "Pic updated with len("; 
			echo strlen($vphoto)/1024;
			echo "KB) \n";
		}
	}

	echo "<BR>";
	echo "Click <a href=\"index.php\">here to go back to the Main Page</a>.\n";
	echo "<br>\n";

	if (strlen($vphoto)) {
		echo '<img src="'.($vphoto).'" height="192" width="256" />';
	} else {
		echo '<img src="disp_photo.php?vid='.$vid.'" height="192" width=256" />';
	}
	echo "<br>\n";


	//Start of expt
echo "<table border=\"1\">";
// display details before inserting
foreach ($_REQUEST as $key => $value) {
	$value = sanitize($value);
	if ($key=="block_other") continue;
	if($is_debug == false) {
		if ($key=="isVehicleSelected") continue;
		if ($key=="isSkipPicSelected") continue;
		if ($key=="submit") continue;
		if ($key=="vid") continue;
		if ($key=="startdate") continue;
		if ($key=="is_exists_pic") continue;
	}
		
	//echo "\n<tr>";
	//echo "<td>";
	
	//if (isset($is_debug) && $is_debug) {
	//	echo $key . " / ";
	//}

	if ((isset($is_debug) && $is_debug)) {
	} else {
		if (strpos(strtoupper($key),"HIDDEN")) {
			// do not display hidden values
			continue;
		}
		if (strpos(strtoupper($key),"SUBMIT")) {
			// do not display submit button
			continue;
		}
		if (strpos($key,"is_exists_pic")) {
			// do not display hidden values
			continue;
		}
	}


	echo "\n<tr>";
	echo "<td>";

	if ($key==="vto_meet") {
		$key = "To Meet";
	}
	if($key == "picinfo_hidden") { 
		if (isset($is_debug) && $is_debug) {
			//Picture is already displayed separately 
			//print first 25 chars of val
			echo ucfirst(str_replace("_"," ",$key));
			echo "</td>";
			echo "<td>";
			echo mb_substr($value, 0, 25);
			echo "</td>";
			echo "</tr>";
			continue;  
		} else {
			continue;
		}
	}
	if ($key == "hiddenFormStartTime") { 
		$key = "Form Fill Duration";
		$res = secondsToTime($vduration_fillup);
		//print_r($res);
		if ($res['h']) {
			$value =  $res['h']. " hours " . $res['m'] . " mins ". $res['s'] . " secs";
		} else if ($res['m']) {
			$value =  $res['m'] . " mins ". $res['s'] . " secs";
		} else {
			$value = $res['s'] . " secs";
		}
	}

	echo ucfirst(str_replace("_"," ",$key));
	echo "</td>";
	echo "<td>";

	if ($key=="vehicle_type") {
		show_image_by_vehicle_type($value);
	} else {
		echo $value;
	}
	//if ($key == "name" && $vid == NULL) {
	if ($key == "name" && is_null($vid)) {
		echo "<img src='images/new-icon.png' title='First-Time Visitor'/>";
	} else if ($key == "name") {
		echo "<img src='images/repeat.png' title='Repeat Visitor'/>";
	}
	echo "</td>";
	echo "</tr>";
}



echo "\n</table>";
/* 
 * No need for a separate print button, just print. 
 */

$info = array(
		$vitime,
		$vrecordid,
		$vname,
		$vphone,
		//$vid,
		$vvehicle_reg_num,
		//$vvehicle_type,
		//$vphoto,
		$vnum_visitors,
		$vtomeet,
		$vflatnum,
		$vblock,
		$vpurpose,
		$vcomments,
		//$vcreatedby,
		$vduration_fillup
);
//print_r($info);
print_pass(
		$vitime,
		$vrecordid,
		$vname,
		$vphone,
		//$vid,
		$vvehicle_reg_num,
		//$vvehicle_type,
		//$vphoto,
		$vnum_visitors,
		$vtomeet,
		$vflatnum,
		$vblock,
		$vpurpose,
		$vcomments,
		//$vcreatedby,
		$vduration_fillup
	);
?>
<BR>
<BR>
<div align="center">
<form>
<!--
<input type="button" value="Print this page" onClick="window.print()">
<form method="get" action="posprint.php">
<input type="hidden" name="form_id" value="826681" />
<input type="SUBMIT" value="cLICKABLE bUTTON">
</form>
-->

</form>
</div>
<BR>
<BR>
<?php

?>
</div>
</body>
</html>
