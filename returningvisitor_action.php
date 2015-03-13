<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Returning Visitor</title>
    <style>
        body{font-family:Sans-Serif;}
        canvas{position:absolute; left: -9999em;}
        #button{background-color: Yellow; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #mandatory{background-color: Green; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #preview{margin: 20px 0;}
	label{display: block;}
	td {white-space:nowrap}
	th {white-space:nowrap}

</style>
<link rel="stylesheet" type="text/css" href="css/view.css" media="all">
<link rel="stylesheet" href="css/zdnet.css">
<link rel="stylesheet" href="css/parsley.css">
<script type="text/javascript" src="js/view.js"></script>

</head>
<body id="main_body" >
<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
/* 
 * Postpone displaying the menu.
 * We need to redirect this page if its a new user to register for the first time.
 */
//display_menu_returning_visitor();
global $results;
$vto_meet		= ((!empty($_POST["vto_meet"		]))?trim($_POST["vto_meet"             ]):"");
$vblock			= ((!empty($_POST["block"		]))?trim($_POST["block"                ]):"");
$vflatnum		= ((!empty($_POST["flat_num"		]))?trim($_POST["flat_num"             ]):"");
$flat_span		= ((!empty($_POST["flat_span"		]))?trim($_POST["flat_span"            ]):"");
$vname			= ((!empty($_POST["name"		]))?trim($_POST["name"     ]):"");
$vphone_num		= ((!empty($_POST["phone_num"		]))?trim($_POST["phone_num"     ]):0);
$vvehicle_reg_num	= ((!empty($_POST["vehicle_reg_num"	]))?trim($_POST["vehicle_reg_num"     ]):"");




#$vname			= "Viol";
#$vnumber		= "234";

DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
/*
$base_query = "SELECT vid,vname,vphone,vcomments,vctime FROM visitor WHERE 1=1 AND ";

if ($vname != "") {
	$query_add = "WHERE vname LIKE%ss";
	$query_end = 
}
 */

/*
foreach($_POST as $name=>$value){
	    $arrFields[] = $name." = '".$value."'";
}
 */
foreach($_POST as $name=>$value){
	switch($name){
	case "name":
		if (!empty($_POST["name"])) {
			$arrFields[] = "vname LIKE '%".$value."%'";
		}
		break;
	case "phone_num":
		if (!empty($_POST["phone_num"])) {
			$arrFields[] = "vphone LIKE '%".$value."%'";
		}
		break;
	case "vehicle_reg_num":
		if (!empty($_POST["vehicle_reg_num"])) {
			$arrFields[] = "vvehicle_reg_num LIKE '%".$value."%'";
		}
		break;
		/*
	case "block":
		if (!empty($_POST["block"])) {
			$arrFields[] = "vblock LIKE '%".$value."%'";
		}
		break;
	case "flat_num":
		if (!empty($_POST["flat_num"])) {
			$arrFields[] = "vflatnum LIKE '%".$value."%'";
		}
		break;
		 */
	case "submit":
		break;
	case "form_id":
		break;
	}
}
if (empty($arrFields) || count($arrFields) == 0) {
	echo "Too few selectors to Match. Click <a href=\"returningvisitor.php\">here</a> to go back\n.";
	exit(1);
}
display_menu_common("Visitor Checkin");
/*
$query = "SELECT DISTINCT(visitor.vid),vname,vphone,vctime,vblock,vflatnum,vvehicle_reg_num,vcomments FROM visitor,vrecord WHERE vrecord.vid=visitor.vid  AND ".implode(" AND ",$arrFields);
$query .= " GROUP BY visitor.vid ORDER BY vrecord.vrecordid DESC LIMIT 10";
 */

$query = "SELECT DISTINCT(visitor.vid),vname,vphone,vctime FROM visitor, vrecord WHERE 1=1 AND ".implode(" AND ",$arrFields);

$results = DB::query($query);

$counter = DB::count();
if ($counter <= 0) {
	/* 
	 * New Visitor. Show the registration page and auto-populate the information already provided. 
	 */
	/* Redirect to a different page in the current directory that was requested */
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = 'visitor_add.php';
	$extra = 'visitor_add.php?name='.$vname.'&phone='.$vphone_num.'&block='.$_POST['block'].'&flat_span='.$_POST['flat_span'].'&vehicle_reg_num='.$_POST['vehicle_reg_num'];
/*
	$is_first_field = true;
	foreach($_POST as $name=>$value) {
		if ($is_first_field) {
			$extra .= '?'.$name.'='.htmlspecialchars($value);
		} else {
			$extra .= '&'.$name.'='.htmlspecialchars($value);
		}
	}
 */
	header("Location: http://$host$uri/$extra");
	exit;
} else {
	//echo "query is <br>\n$query\n";
	//exit;
	$is_first_criteria = true;
	echo "Searching for records where ";
	foreach ($arrFields as $criteria) {
		if ($is_first_criteria) {
			echo $criteria;
			$is_first_criteria = false;
		} else {
			echo "AND ".$criteria;
		}
	}
	echo "\n<BR>\n";


	echo $counter . " matches found";
	echo "<BR>\n";
}

foreach ($results as $row) {
        echo '<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="add_visitor_existing.php">';
	echo "\n<table cellpadding=\"0\" cellspacing=\"0\">";
	echo "\n<tr>\n";
	echo "<th>Selector</th>\n";
	foreach ($row as $key => $value) {
		//if ($key == "vid") {
		//	echo "<th style=\"display:none;\">";
		//	echo $key;
		//	echo "<\th>\n";
		//}
		echo "<th>".$key."</th>\n";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td><input type=\"submit\" value=\"Select\"></td>\n";
	foreach ($row as $key => $value) {
		//echo "<td>" . $value . "</td>";
		echo "<td><input type=\"text\" name=\"".$key."\" value=\"". $value."\" readonly /></td>";
		echo "\n";
	}
	//echo "<td><input type=\"submit\" value=\"Change\"></td>\n";
	//echo "<td><input type=\"submit\" value=\"Delete\"></td>\n";
	echo "</tr>\n</table>\n</form>\n\n";
}
?>
	</body>
</html>
