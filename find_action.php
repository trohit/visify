<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Find Visitor</title>
    <style>
        body{font-family:Sans-Serif;}
        canvas{position:absolute; left: -9999em;}
        #button{background-color: Yellow; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #mandatory{background-color: Green; color: Red; padding: 3px 10px; cursor:pointer; display: inline-block; border-radius: 5px;}
        #preview{margin: 20px 0;}
	label{display: block;}
/*
	td {white-space:nowrap}
	th {white-space:nowrap}
*/
</style>
<link rel="stylesheet" type="text/css" href="css/view.css" media="all">
<link rel="stylesheet" href="css/parsley.css">
<link rel="stylesheet" href="css/zdnet.css">
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/slimbox2.js"></script>


<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>

</head>
<body id="main_body" class="appnitro">
	<img id="top" src="images/top.png" alt="">
<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");	
display_menu_common("Find Records");
echo '<div id="find_result_container">';
global $results;
$vname			= ((!empty($_REQUEST["name"		]))?trim($_REQUEST["name"     ]):"");
$vnumber		= ((!empty($_REQUEST["number"		]))?trim($_REQUEST["number"     ]):0);
$vvehicle_reg_num	= ((!empty($_REQUEST["vvehicle_reg_num"	]))?trim($_REQUEST["vvehicle_reg_num"     ]):"");
$vflatnum		= ((!empty($_REQUEST["flat_num"		]))?trim($_REQUEST["flat_num"             ]):0);
$vblock			= ((!empty($_REQUEST["block"		]))?trim($_REQUEST["block"                ]):"");

$vname			= sanitize($vname); 
$vnumber		= sanitize($vnumber); 
$vvehicle_reg_num	= sanitize($vvehicle_reg_num); 
$vflatnum		= sanitize($vflatnum); 
$vblock			= sanitize($vblock); 


require_once("parse_config.php");	

$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted


/*
foreach($_REQUEST as $name=>$value){
	    $arrFields[] = $name." = '".$value."'";
}
 */
foreach($_REQUEST as $name=>$value){
	$value = sanitize($value);
	switch($name){
	case "name":
		if (!empty($_REQUEST["name"])) {
			$arrFields[] = "vname LIKE '%".$value."%'";
		}
		break;
	case "phone_num":
		if (!empty($_REQUEST["phone_num"])) {
			$arrFields[] = "vphone LIKE '%".$value."%'";
		}
		break;
	case "vehicle_reg_num":
		if (!empty($_REQUEST["vehicle_reg_num"])) {
			$arrFields[] = "vvehicle_reg_num LIKE '%".$value."%'";
		}
		break;
	case "block":
		if (!empty($_REQUEST["block"])) {
			$arrFields[] = "vblock LIKE '%".$value."%'";
		}
		break;
	case "flat_num":
		if (!empty($_REQUEST["flat_num"])) {
			$arrFields[] = "vflatnum LIKE '%".$value."%'";
		}
		break;
	case "submit":
		break;
	case "form_id":
		break;
	}
}
if (empty($arrFields) || count($arrFields) == 0) {
	echo "Too few selectors to Match. Try <a href=\"find.php\">Searching Again</a>\n.";
	exit(1);
}
$query = "SELECT visitor.vid,vname,vphone,vcomments,vitime,votime,vblock,vflatnum,vtomeet,vvehicle_reg_num,vvehicle_type, vpurpose FROM visitor,vrecord WHERE vrecord.vid=visitor.vid  AND ".implode(" AND ",$arrFields);
//$query .= " GROUP BY visitor.vid ORDER BY vrecord.vrecordid DESC LIMIT 10";

//$is_debug = true;
if (isset($is_debug) && $is_debug) {
	echo "query is <br>\n$query\n";
	//exit;
}
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
$results = DB::query($query);

$counter = DB::count();
if ($counter <= 0) {
	echo "No Match. Try <a href=\"find.php\">Searching again</a>\n.";
} else {
	echo $counter . " matches found";
	echo "<BR>\n";
}
$here_th_find_result = <<<EOT
<table width="100%" class="table table-striped" border="1" cellpadding="0" cellspacing="0" border="1" style="font-size:80%;">
<tr>
<th col width="15%">Pic</th>
<th col width="4%">ID</th>
<th col width="10%">Name</th>
<th col width="7%">Phone</th>
<th col width="10%">Remark</th>
<th col width="10%">Last Visit</th>
<th col width="10%">Outtime</th>
<th col width="10%">Block</th>
<th col width="3%">Flatnum</th>
<th col width="10%">To Meet</th>
<th col width="8%">Vehicle Reg</th>
<th col width="10%">Purpose</th>
<th col width="1%">Do</th>
</tr>

EOT;
/*
 *
 
<td><a href=\"visitor-edit.php?vid=".$row['vid']."\" target=\"_blank\"><img src=\"images/vcard_edit.png\" alt=\"Edit Visitor\" border=3 style=\"width: 50%; height: 50%\"/></a></td>

 */
$here_tr_find_action = <<<EOT
<tr>
<td><img src=\"images/vcard_edit.png\" alt=\"Edit Visitor\" border=3 style=\"width: 25%; height: 25%\"/></td>
<td>A</td>
</tr>

EOT;
$link_style = <<< EOT
<style>
a:link {color:white;}    /* unvisited link */
a:visited {color:white;} /* visited link */
a:hover {color:yellow;}   /* mouse over link */
a:active {color:white;}  /* selected link */
</style>
EOT;

echo $here_th_find_result;
 
foreach ($results as $row) {
	echo "<tr>\n";

	/*
	 * flats last visited not needed as they are printed in the results.
	 *
	$big_chunk_str="";
	$hist = DB::query("SELECT vitime,vblock,vflatnum,vtomeet FROM vrecord WHERE vid=%s ORDER BY vrecordid DESC LIMIT 5", $row['vid']);
	$vctime = DB::queryFirstField("SELECT vctime from visitor where vid=%s",$row['vid']);
	foreach($hist as $chunk) {
		//print_r($chunk);
		$chunk['vitime'] .= " ". get_weekday_by_date($chunk['vitime']);
		//print_r(array_values($chunk));
		$chunk_str = implode(",",$chunk);
		$big_chunk_str = $big_chunk_str . "<BR> ". $chunk_str;
		//echo "<BR>";
	}
	//echo $big_chunk_str;
	//$pic_title = $row['vname']." ".$row['vphone']." ".$chunk_str;
	$pic_title = $big_chunk_str;
	 */

	$remark = DB::queryFirstField("SELECT vcomments from visitor where vid=%s",$row['vid']);
	$pic_title = $row['vname']. " " .$remark;

	$vctime = DB::queryFirstField("SELECT vctime from visitor where vid=%s",$row['vid']);


	//$pic_title = $row['vname']." ".$row['vphone'];
	echo "<td><a href=\"disp_photo.php?vid=".$row['vid']."\" target=\"_blank\" rel=\"lightbox\" title=\"".$pic_title."\"><img id=\"magnify\" class=\"resize\" src=\"disp_photo.php?vid=".$row['vid']."\" alt=\"\" border=3 style=\"width: 50%; height: 50%\"/></a></td>\n";
	foreach ($row as $key => $value) {
		if ($key == "vname") {
			echo "<td>";
			//echo $link_style;
			echo "<a href=\"disp_photo.php?vid=".$row['vid']."\" >$value</a>";
			echo "</td>";
		} else if ($key == "vitime") {
			// also show the time at which the visitor first visited the place
			// as a tooltip

			//echo "<span title=\"My tip\">text</span>";
			//echo "<td>$value ".get_weekday_by_date($value)."</td>";
			$my_itime = $row['vitime'] ." ".  get_weekday_by_date($row['vitime']);
			$my_ctime = $vctime ." ".  get_weekday_by_date($vctime);

			echo "<td>";
			echo "<span title=\"".$my_ctime."\">$my_itime</span>";
			echo "</td>";
		} else if ($key == "vctime") {
			// not printing to save screen space
			continue;

		} else if ($key == "vvehicle_type") {
			// not printing to save screen space
			continue;
		} else {
			if ($value != "0") {
				echo "<td>$value</td>";
			} else {
				echo "<td></td>";
			}
		}
		echo "\n";

		//print actions
		//echo "$here_tr_find_action";

	}
	echo "\n<td>";
	echo "\n<a href=\"edit_visitor.php?vid=".$row['vid']."\"> <img src=\"images/vcard_edit.png\" title=\"Edit Visitor\" style=\"width:18px; height:18px\"/>";
	//echo "\n<img src=\"images/phone.png\" title=\"SMS Visitor\" style=\"width:16px; height:16px\"/>";
	//echo "\n<img src=\"images/exit-icon.png\" title=\"Check-out Visitor\" style=\"width:18px; height:18px\"/>";
	echo "<br>";
	echo "\n<a href=\"returningvisitor.php?phone_num=".$row['vphone']."\"><img src=\"images/exit-icon.png\" title=\"Check-in Visitor again\" style=\"width:18px; height:18px\"/></a>";
	echo "\n</td>";
	echo "\n</tr>\n";
	
}
echo "</table>\n";
?>

<div align="center">
	<style scoped> form { display: inline; } </style>
	<form> <input type="button" value="Print this page" onClick="window.print()"> </form>
	<form action="find.php"> <input type="submit" value="Find Again"> </form>
</div>
</div>



<script>
/*

//get by tag
$('img').hover(makeBigger, returnToOriginalSize);

//get by id
$('#magnify').hover(makeBigger, returnToOriginalSize);

function makeBigger() {
	//alert('make bigger');
	$(this).css({height: '+=50%', width: '+=50%'});
}
function returnToOriginalSize() {
	//alert('make smaller');
	$(this).css({height: '', width: ''});
}
*/
var current_h = null;
var current_w = null;
var magni=1.5


//get by class
$('.resize').hover(
    function(){
        current_h = $(this, 'img')[0].height;
        current_w = $(this, 'img')[0].width;
        $(this).stop(true, false).animate({width: (current_w * magni), height: (current_h * magni)}, 300);
    },
    function(){
        $(this).stop(true, false).animate({width: current_w + 'px', height: current_h + 'px'}, 300);
    }
);
</script>

</body>
</html>

