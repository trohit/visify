<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Visitor Reports</title>
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
<link rel="stylesheet" href="css/parsley.css">
<link rel="stylesheet" href="css/zdnet.css">
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/slimbox2.js"></script>

</head>
<body id="main_body" class="appnitro">
<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
?>
<div id="wide_container">
<?php
display_menu_common("Reports");
global $results;

require_once("parse_config.php");	

$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

/*
DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
 */

//$is_debug = true;
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
$is_condition_present = false;

foreach($_REQUEST as $name=>$value){
	switch($name){
	case "start_date":
		if (!empty($_REQUEST["start_date"])) {
			list($st_dd,$st_mm,$st_yyyy) = explode("/",$value);
			//$swizzled_value = $st_yyyy.$st_mm.$st_dd;
			$swizzled_value = $st_yyyy.$st_mm.$st_dd."235959";
			$arrFields[] = "vitime >= '".$swizzled_value."'";
			$is_condition_present = true;
		}
		break;
	case "end_date":
		if (!empty($_REQUEST["end_date"])) {
			list($end_dd,$end_mm,$end_yyyy) = explode("/",$value);
			//$swizzled_value = $end_yyyy.$end_mm.$end_dd;
			$swizzled_value = $end_yyyy.$end_mm.$end_dd."235959";
			$arrFields[] = "vitime <= '".$swizzled_value."'";
			$is_condition_present = true;
		}
		break;
	case "vehicle_reg_num":
		if (!empty($_REQUEST["vehicle_reg_num"])) {
			$arrFields[] = "vvehicle_reg_num LIKE '%".$value."%'";
			$is_condition_present = true;
		}
		break;
	case "block":
		if (!empty($_REQUEST["block"])) {
			$arrFields[] = "vblock LIKE '%".$value."%'";
			$is_condition_present = true;
		}
		break;
	case "flat_num":
		if (!empty($_REQUEST["flat_num"])) {
			$arrFields[] = "vflatnum LIKE '%".$value."%'";
			$is_condition_present = true;
		}
		break;
	case "submit":
		break;
	case "form_id":
		break;
	}
}
/*
if (empty($arrFields) || count($arrFields) == 0) {
	echo "Too few selectors to Match. Try <a href=\"find.php\">Searching Again</a> or <a href=\"visitor.php\">Register new Visitor</a>\n.";
	exit(1);
}
 */
//$query = "SELECT DISTINCT(visitor.vid),vname,vphone,vcomments,vctime,vblock,vflatnum,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid  AND ".implode(" AND ",$arrFields);
//searching for only certain vrecords
//SELECT (visitor.vid),vrecordid,vname,vphone,vctime,vblock,vflatnum,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid AND vrecordid>23;


//searching for records where visitor photo exists
//SELECT (visitor.vid),vrecordid,vname,vphone,vctime,vblock,vflatnum,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid AND visitor.vphoto <>'';

$query = "SELECT visitor.vid,vitime,vname,vphone,vctime,vblock,vflatnum,vtomeet,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid ";
if (isset($arrFields)) {
	$query .= "AND ".implode(" AND ",$arrFields);
}
$query .= " LIMIT 2000";

if (isset($is_debug) && $is_debug) {
	echo "<br>\n$query<br>\n";
	//exit;

	if (isset($arrFields)) {
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
	}
	echo "\n<BR>\n";
} else {
	if ($is_condition_present) {
		echo "Searching for records";
	}

	if (!empty($_REQUEST["block"])) {
		echo " in block ".$_REQUEST["block"];
	}

	if (!empty($_REQUEST["flat_num"])) {
		echo " in flat number ".$_REQUEST["flat_num"];
	}

	if (!empty($_REQUEST["vehicle_reg_num"])) {
		echo " with vehicle number ".$_REQUEST["vehicle_reg_num"];
	}

	if (!empty($_REQUEST["start_date"])) {
		list($st_dd,$st_mm,$st_yyyy) = explode("/",$_REQUEST["start_date"]);
		echo " from $st_dd.$st_mm.$st_yyyy";
	}
	if (!empty($_REQUEST["start_date"]) && (!empty($_REQUEST["end_date"]))) {
		echo " and";
	}
	if (!empty($_REQUEST["end_date"])) {
		list($end_dd,$end_mm,$end_yyyy) = explode("/",$_REQUEST["end_date"]);
		echo " ending before $end_dd.$end_mm.$end_yyyy" ;
	}
	echo "\n<BR>\n";
}
$results = DB::query($query);

$counter = DB::count();
if ($counter <= 0) {
	echo "No Match. Try <a href=\"reports.php\">searching again</a>\n.";
} else {
	echo $counter . " matches found";
	echo "<BR>\n";
}
$is_table_header_printed = false;
$row_num = 1;
//echo "\n<table cellpadding=\"0\" cellspacing=\"0\" width=\"33%\">";
echo "\n<table cellpadding=\"0\" cellspacing=\"0\" border=\"1\">";
foreach ($results as $row) {
	//echo "\n<table cellpadding=\"0\" cellspacing=\"0\" width=\"33%\">";
	if ($is_table_header_printed == false) {
		echo "\n<tr>\n";
		if (isset($is_show_selector) && ($is_show_selector)) {
			echo "<th>Selector</th>\n";
		}


		foreach ($row as $key => $value) {
			//if ($key == "vid") {
			//	echo "<th style=\"display:none;\">";
			//	echo $key;
			//	echo "<\th>\n";
			//}
			if ($key=="vctime") {
				continue;
			}

			//strip the first 'v' from each header name
			//so vitime become itime
			$new_key = substr($key, 1);
			echo "<th>".$new_key."</th>\n";
		}
		echo "</tr>\n";
		$is_table_header_printed = true;
	}
	echo "<tr>\n";
	if (isset($is_show_selector) && ($is_show_selector)) {
		echo "<td><input type=\"submit\" value=\"ShowPic\"></td>\n";
	}
	foreach ($row as $key => $value) {
		//echo "<td>" . $value . "</td>";
		//echo '<form id="select_record" name="select_record" class="appnitro"  method="REQUEST" action="disp_photo.php">';
		//echo "<td>";
		//echo '<form id="select_record" name="select_record" class="appnitro"  method="REQUEST" action="disp_photo.php?vid=';
		//echo $row['vid']."\"\>";
		//echo "</form>";
		//echo "</td>";

		if ($value == "0") {
			echo "<td></td>";
		} else if ($key=="vname") {	
			//echo "<td><a href=\"find_action.php?phone_num=".$row['vphone']."\" >$value</a></td>";
			$pic_title = $row['vname'];
			echo "<td><a href=\"disp_photo.php?vid=".$row['vid']."\" rel=\"lightbox-pic\" title=\"".$pic_title."\">".$row['vname']."</a></td>\n";
		} else if ($key=="vphone") {	
			echo "<td><a href=\"find_action.php?phone_num=".$row['vphone']."\" >$value</a></td>";
		} else if ($key=="vctime") {
			//do not print creation time
			continue;
		} else if (strpos($key,'time') !== false) {
			$weekday = get_weekday_by_date($value);
			$value .= $weekday;
			//echo "<td>". $value . " " . $weekday . "</td>";
			echo "<td>";
			echo "<span title=\"".$row['vctime']."\">$value</span>";
			echo "</td>";
		} else if ($key=="vid") {
			echo "<td>$row_num</td>";
		} else {
			//echo "<td><input type=\"text\" name=\"".$key."\" value=\"". $value."\" readonly /></td>";
			echo "<td>$value</td>";
		}
		echo "\n";
	}
	$row_num++;
	//echo "<td><input type=\"submit\" value=\"Change\"></td>\n";
	//echo "<td><input type=\"submit\" value=\"Delete\"></td>\n";
	//echo "</tr>\n</table>\n";
	//echo "</form>\n\n";
}
echo "</tr>\n</table>\n";
?>
</div>

<script> function goBack() { window.history.back() } </script>

<form>
<input type="button" value="Print this page" onClick="window.print()">
</form>


<form name="excel" method="post" action="export_xls.php">
   <button type="submit" value="Submit">Export to Excel</button>
</form>

<form name="pdf" method="post" action="export_pdf.php">
   <button type="submit" value="Submit">Export to PDF</button>
</form>

<button onclick="goBack()">Go Back and make Changes</button>
	</body>
</html>


