<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Active Visitors</title>
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
	div.ui-datepicker { font-size: 70%; }
</style>
<link rel="stylesheet" type="text/css" href="css/view.css" media="all">
<link rel="stylesheet" href="css/parsley.css">
<link rel="stylesheet" href="css/zdnet.css">
<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />

<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/slimbox2.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/style.css">

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

$is_debug = false;
//$is_debug = true;

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");	
display_menu_common("Active Visitors");
?>
<!--
<div id="scanner">
    <input type="text" id="barcode" placeholder="Waiting for barcode ..." size="40">
</div>
-->
<?php
$selectedDate		= ((!empty($_REQUEST["selected_date"	]))?trim($_REQUEST["selected_date"]):"");
echo '<div id="find_result_container">';
?>
<div id="scanner" align="center">
<div id="txt_side_msg"></div>
<table id="active_visitors_table" border="0">
<tr>
	<td>
	<form id="form_datepicker" action="active_visitors.php" method="get">
 <div class="input-group date" id="timepicker">
  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span>
	<input type="text" id="datepicker" value="<?php echo $selectedDate;?>" width="20">
</div>

	<input type="hidden" id="selected_date" name="selected_date" value="">
<!--
	<div class="form-group">
 <div class="input-group date" id="timepicker">
  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span>
  <input type="text" class="form-control" />
 </div>
</div>
-->


<!--
	<input type="submit" value="Just do it"> 
-->
	</form>


	</td>
	<td>
	<div id="checkout_visitor">
	<audio id="checkout_sound">
    	<source src="sounds/button-3.mp3">
    	<source src="sounds/button-3.ogg">
  	</audio>

	<input type="text" id="barcode" onkeypress="handleKeyPress(event)" placeholder="Enter Barcode ..." size="40">
	</div>
	</td>
	<td><img id="doStuff" src="images/go_48.png" href="#" height="20" width="20"/></td>
</tr>
</table>
</div>

<script>
function handleKeyPress(e){
	var key=e.keyCode || e.which;
	if (key==13){ //Enter key pressed
		//alert("key pressed");
		doDirtierWork(e);
	}
}

</script>
<?php
global $results;

require_once("parse_config.php");	

$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

if ($is_debug == true) {
	$limit = 10;
} else {
	$limit = 500;
}

if ($selectedDate) {
	echo '<input type="hidden" id="dateSelected" name="dateSelected" value="true">';
	echo '<input type="hidden" id="actualDate" name="actualDate" value="'.$selectedDate.'">';
} else {
	echo '<input type="hidden" id="dateSelected" name="dateSelected" value="false">';
}
function get_query_active_visitors($selectedDate, $limit=500)
{
	$query = "SELECT vrecordid, visitor.vid AS vid, vname,vphone,vnum_visitors, vcomments,vitime, vblock,vflatnum,vtomeet,vvehicle_type, vvehicle_reg_num,vnum_visitors FROM visitor,vrecord WHERE  vitime LIKE '" . $selectedDate . "%' AND (votime is NULL OR vitime<=>votime) AND vrecord.vid=visitor.vid LIMIT " . $limit;
	return $query;
}


#$query = "SELECT visitor.vid,vname,vphone,vcomments,vitime,vblock,vflatnum,vtomeet,vvehicle_reg_num FROM visitor,vrecord WHERE vitime!=votime AND  vitime> '2015-05-05' AND vrecord.vid=visitor.vid LIMIT 10"; 


#$cutoff_date = "2015-03-10";
$cutoff_date = get_from_today(0);
if (!empty($selectedDate)) {
	$cutoff_date = $selectedDate;
}
$query = get_query_active_visitors($cutoff_date, $limit);

if ($is_debug == true) {
	echo $query;
}

	#echo $query;

if (isset($is_debug) && $is_debug) {
	echo "query is <br>\n$query\n";
	//exit;
}
?>

<?php
$is_first_criteria = true;
echo "<div id=\"active_visitors_query_status\">Searching for active visitors on " . convert_date_boring_to_interesting($cutoff_date). "</div>";
echo "\n<BR>\n";
$results = DB::query($query);

$counter = DB::count();
if ($counter <= 0) {
	echo "No Match. Try choosing a different date\n.";
} else {
	echo $counter;
	if ($counter >= $limit) {
		echo "(Maxlimit Reached) ";
	}
	echo " matches found";
	echo "<BR>\n";
}
$here_th_find_result = <<<EOT
<table width="100%" cellpadding="0" cellspacing="0" border="1">
<tr>
<th col width="2%">#</th>
<th col width="5%">SNo</th>
<th col width="10%">Name</th>
<th col width="7%">Phone</th>
<th col width="2%">Ppl</th>
<th col width="20%">Remark</th>
<th col width="10%">Intime</th>
<th col width="10%">Block</th>
<th col width="5%">Flatnum</th>
<th col width="10%">To Meet</th>
<th col width="8%">Vehicle Reg</th>
<th col width="2%">Do</th>
</tr>

EOT;
/*
 *
 
<td><a href=\"visitor-edit.php?vid=".$row['vid']."\" target=\"_blank\"><img src=\"images/vcard_edit.png\" alt=\"Edit Visitor\" border=3 style=\"width: 50%; height: 50%\"/></a></td>

 */
$here_tr_find_action = <<<EOT
<tr>
<td><img src=\"images/vcard_edit.png\" alt=\"Edit Visitor\" border=3 style=\"width: 50%; height: 50%\"/></td>
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
$count = 1;
echo $here_th_find_result;
 
foreach ($results as $row) {
	#print_r($row);

	echo "<tr id=\"".$row["vrecordid"]."\">\n";
	$remark = DB::queryFirstField("SELECT vcomments from visitor where vid=%s",$row['vid']);
	$pic_title = $row['vname']. " " .$remark;

	$vctime = DB::queryFirstField("SELECT vctime from visitor where vid=%s",$row['vid']);

	echo "<td>$count</td>";
	$count++;
	#echo "<td><a href=\"disp_photo.php?vid=".$row['vid']."\" target=\"_blank\" rel=\"lightbox\" title=\"".$pic_title."\"><img id=\"magnify\" class=\"resize\" src=\"disp_photo.php?vid=".$row['vid']."\" alt=\"\" border=3 style=\"width: 50%; height: 50%\"/></a></td>\n";
	foreach ($row as $key => $value) {
		if ($key == "vid") {
			continue;
		} else if ($key == "vname") {
			$p_vname = prepare_vname($value);
			/*
			echo "<td>";
			#echo "<a href=\"disp_photo.php?vid=".$row['vid']."\" >$value</a>";
			echo "<a href=\"disp_photo.php?vid=".$row['vid']."\" >$p_vname</a>";
			echo "</td>";
			 */
			echo "<td><a href=\"disp_photo.php?vid=".$row['vid']."\" target=\"_blank\" rel=\"lightbox\" title=\"".$p_vname."\">$p_vname</a></td>\n";
		} else if ($key == "vtomeet") {
			$p_val = prepare_vtomeet($value);
			echo "<td>";
			echo $p_val;
			echo "</td>";
		} else if ($key == "vitime") {
			// also show the time at which the visitor first visited the place
			// as a tooltip

			//echo "<span title=\"My tip\">text</span>";
			//echo "<td>$value ".get_weekday_by_date($value)."</td>";
			#$my_itime = $row['vitime'] ." ".  get_weekday_by_date($row['vitime']);
			#$my_ctime = $vctime ." ".  get_weekday_by_date($vctime);

			echo "<td>";
			#echo "<span title=\"1st Entry:".$my_ctime."\">$my_itime</span>";
			echo $row['vitime'];
			echo "</td>";
		} else if ($key == "vctime") {
			// not printing to save screen space
			continue;
		} else if ($key == "vvehicle_type") {
			continue;
		} else if ($key == "vvehicle_reg_num") {
			$img_size = 24;
			echo "<td>";
			$vehicle_img =  get_image_by_vehicle_type($row['vvehicle_type'],$img_size);
			if (!empty($vehicle_img)) {
				echo $vehicle_img;
			}
			if (!empty($value)) {
				$value = strtoupper($value);
				echo "$value";
			}

			#echo $row['vvehicle_type'];
			#print_r($row);
			echo "</td>";

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
	echo "\n<td id=\"btn".$row["vrecordid"]."\">\n";
	#echo "\n<a href=\"edit_visitor.php?vid=".$row['vid']."\"> <img src=\"images/vcard_edit.png\" title=\"Edit Visitor\" style=\"width:32px; height:32px\"/>";
	//echo "\n<img src=\"images/phone.png\" title=\"SMS Visitor\" style=\"width:16px; height:16px\"/>";
	#echo "\n<a class=\"checkout\" href=\"checkout_visitor.php?phone_num=".$row['vphone']."\"><img src=\"images/exit-icon.png\" title=\"Check-out Visitor\" style=\"width:32px; height:32px\"/></a>";
	echo "\n<a class=\"checkout\"> <img src=\"images/exit-icon.png\" title=\"Check-out Visitor\" style=\"width:32px; height:32px\"/></a>";
	#echo "\n<a href=\"returningvisitor.php?phone_num=".$row['vphone']."\"><img src=\"images/exit-icon.png\" title=\"Check-in Visitor again\" style=\"width:32px; height:32px\"/></a>";
	echo "\n</td>";
	echo "\n</tr>\n";
	
}
echo "</table>\n";
?>

<div align="center">
	<style scoped> form { display: inline; } </style>
	<form> <input type="button" value="Print this page" onClick="window.print()"> </form>
<!--
	<form action="active_visitors.php"> <input type="submit" value="Find Again"> </form>
-->
</div>
</div>



<script type="text/javascript">
	var delayBefore = 200;
	var delayAfter = 200;
	function doDirtyWork(parent, tickOffVal) {
		$.ajax({
			type: 'get',
			url: 'checkout_visitor.php',
			data: 'ajax=1&checkout_vrecordid=' + tickOffVal,
			beforeSend: function() {
				parent.animate({'backgroundColor':'#fb6c6c'},delayBefore);
			},
			success: function() {
				parent.slideUp(delayAfter,function() {
					parent.remove();
					console.log('Checked out!' + tickOffVal);
					//$("#checkout_sound").get(0).play();
					document.getElementById('checkout_sound').play();
				});
				$("#barcode").focus();
			}// end function success
		});//end ajax
		$("#barcode").val("");
	}//end doDirtyWork
	function doDirtierWork(e) {
		e.preventDefault();
		var tickoffId = $("#barcode").val();
		var parent = $('#'+tickoffId); 
		//alert("yeah, i know" + tickoffId);
		doDirtyWork(parent, tickoffId);
	}//end doDirtierWork
/*
	function getActiveRecordsByDate(candidateDate) {
		alert("getActiveRecordsByDate:Fetching "+ $( "#datepicker" ).val());
		$(location).attr('href','active_visitors.php?date='+candidateDate );
	}//end getActiveRecordsByDate
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
$(document).ready(function() {

	$("#datepicker").datepicker({dateFormat: "yy-mm-dd"});
	//should not set date if date is already provided
	if ($("#dateSelected").val() == "false") {
		$('#datepicker').datepicker().datepicker('setDate', 'today');
	} else{
		//alert("need to poulate with selected date" +  $("#actualDate").val());
		$('#datepicker').datepicker().datepicker('setDate', $("#actualDate").val());
		
	}
	$("#datepicker" ).datepicker( "option", "autoSize", true );
	$("#datepicker" ).datepicker( "option", "maxDate", "+0d" );
	$("#datepicker" ).datepicker({
		showOn: "both",
		showWeek: true,
		todayHighlight: true,
		clearBtn: true,
		todayBtn: true,
		dateFormat: "yy-mm-dd",
		buttonImage: "images/DatePicker.gif",
		changeMonth: true,
	});

	$("#barcode").focus();
	var isFocus = false;
	// do stuff when the checkout button is pressed next to a row
	$('a.checkout').click(function(e) {
		e.preventDefault();
		var parent = $(this).parent().parent();
		var toBetickedOff = parent.attr('id');
		doDirtyWork(parent, toBetickedOff);
	});//end click

	// do stuff when a number is submitted from the scanner or into the textbox
	$("#doStuff").click(function(e){
		var tickoffId = $("#barcode").val();
		var parent = $('#'+tickoffId);
		doDirtyWork(parent, tickoffId);


	}); //end of submit
/*
	$(function() {
		//alert("date picker");
		$("#datepicker").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker").datepicker({todayHighlight: true});
		$("#datepicker").datepicker({todayBtn: true});
		$("#datepicker").datepicker({todayBtn: "linked"});
		//$("#datepicker").css({ font-size:10px; });
		//div.ui-datepicker{ font-size:10px; };
		//alert($( "#datepicker" ).val());
	});//end datepicker
*/



	$("#datepicker").on("change", function(e){	
		$("#active_visitors_table").hide();
		$("#txt_side_msg").html("");
		$("#txt_side_msg").html("<img src='images/ajax-loader.gif' /> checking...");
		$("#active_visitors_query_status").html("");
		$("#active_visitors_query_status").html("Fetching records...");
		var tmpDate = $("#datepicker").val()
		$("#selected_date").val(tmpDate);
		//alert("selected_date:"+$("#selected_date").val());
		$("#form_datepicker").trigger('submit');

	});



/*
	$("#datepicker").on("change", function(e){	
		e.preventDefault();
		//var candidateDate = $( "#datepicker" ).val());
		//alert("picking "+ $( "#datepicker" ).val());
		getActiveRecordsByDate($( "#datepicker" ).val());
		alert("boo");
	});
*/
});// end of ready


</script>

</body>
</html>

