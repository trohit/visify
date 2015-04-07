<?php
require_once('header.inc');
require_once('body.inc');
require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");

$is_debug 	= false;
$is_verbose 	= false;
//$is_debug 	= true;
//$is_verbose 	= true;
if($is_verbose) {
	phpinfo();
}
?>

<script>
function goBack() {
	    window.history.back()
}
</script>
<script type="text/javascript">

function showCam()
{
	alert("hoo");
	if ($('#is_add_pic').is(':checked')) {	
		$("#main").show("slow");
		$("#main").focus();
	} else {
		$("#main").hide();
	}
}
$(document).ready(function(){
}); // end doc ready
</script>
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

global $vtomeet;
global $block;
global $flat_num;
global $block_other;

global $vname;
global $vnum_visitors;
global $vphone;
global $is_vehicle_selected;
global $vehicle_reg_num;
global $vehicle_type;
global $purpose;
global $comments;

function show_image_by_vehicle_type($vehicle_type)
{
	if ($vehicle_type == "2w") {
		echo '<img id="bikeId" src="images/bike_checked.png" alt="bike" height="32" title="Bike"/>';
	} else if ($vehicle_type == "4w") {
		echo ' <img id="carId" src="images/car_checked.png" alt="car" height="24" title="Car"/>';
	}
}

$vtomeet		= ((!empty($_REQUEST["to_meet"             ]))?trim($_REQUEST["to_meet"     ]):"");
$block			= ((!empty($_REQUEST["block"              ]))?trim($_REQUEST["block"     ]):"");
$flat_num		= ((!empty($_REQUEST["flat_num"           ]))?trim($_REQUEST["flat_num"     ]):"");
$block_other		= ((!empty($_REQUEST["block_other"              ]))?trim($_REQUEST["block_other"     ]):"");

$vname			= ((!empty($_REQUEST["name"                ]))?trim($_REQUEST["name"     ]):"");
$vnum_visitors		= ((!empty($_REQUEST["num_visitors"        ]))?trim($_REQUEST["num_visitors"     ]):"");
$vphone			= ((!empty($_REQUEST["phone_num"              ]))?trim($_REQUEST["phone_num"     ]):"");
$is_vehicle_selected	= ((!empty($_REQUEST["isVehicleSelected"      ]))?trim($_REQUEST["isVehicleSelected"     ]):"");

$vtomeet		= sanitize($vtomeet);
$block			= sanitize($block);
$flat_num		= sanitize($flat_num);
$block_other		= sanitize($block_other);
$vname			= sanitize($vname);
$vnum_visitors		= sanitize($vnum_visitors);
$vphone			= sanitize($vphone);
$is_vehicle_selected	= sanitize($is_vehicle_selected);


if ($is_vehicle_selected != "") {
	if ($is_debug) {
		echo "Setting Vehicle Num and Type";
	}
	$vehicle_reg_num= ((!empty($_REQUEST["vehicle_reg_num"              ]))?trim($_REQUEST["vehicle_reg_num"     ]):"");
	$vehicle_type	= ((!empty($_REQUEST["vehicle_type"              ]))?trim($_REQUEST["vehicle_type"     ]):"");
} else {
	unset($_REQUEST["vehicle_reg_num"]);
	unset($_REQUEST["vehicle_type"]);
}

$purpose		= ((!empty($_REQUEST["purpose"              ]))?trim($_REQUEST["purpose"     ]):"");
$comments		= ((!empty($_REQUEST["comments"              ]))?trim($_REQUEST["comments"     ]):"");

$purpose		= sanitize($purpose);
$comments		= sanitize($comments);

//print_r($_REQUEST);
if ($block=="Others") {
	$flat_num = $block_other;
	$_REQUEST["flat_num"]=$_REQUEST["block_other"];
	unset($block_other); 
	

	$new_request = array (
		$vtomeet,
		$block,
		$flat_num,

		$vname,
		$vnum_visitors,
		$vphone,
		$vehicle_reg_num,
		$purpose,
		$comments
	);
	//print_r($new_request);
}

/*
	// change order of REQUEST array
	list (
		$_REQUEST["vtomeet"],
		$_REQUEST["block"],
		$_REQUEST["block_other"],

		$_REQUEST["name"],
		$_REQUEST["num_visitors"],
		$_REQUEST["phone_num"],
		$_REQUEST["vehicle_reg_num"],
		$_REQUEST["purpose"],
		$_REQUEST["comments"]
	)=$a;

print_r( $a);
 */


/* 
 * check if old visitor or new one.
 * new visitors need an entry in the visitor db.
 */
//$link = mysqli_connect("localhost","root","","test","");
require_once("parse_config.php");	

$ini_array = parse_config();
$host 		= $ini_array["host"];
$username 	= $ini_array["username"];
$password	= $ini_array["password"];
$db		= $ini_array["db"];
$port		= "";
$socket		= "";

DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];

$link = mysqli_connect($host, $username, $password, $db);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$query="SELECT vid from visitor where vphone='$vphone'";
$result=mysqli_query($link, $query);
$row= mysqli_fetch_row($result);
$vid=$row[0];



if (isset($is_debug) && $is_debug) {
	echo "vphone ". $vphone ;
	printf("Select returned %d rows.\n", $result->num_rows);
	print_r($result);
	echo "query:" . $query;
	echo "vid id: ". $vid ;
	print_r($row);

	if ($is_vehicle_selected != "") {
		echo "Vehicle Selected\n";
	} else {
		echo "Vehicle NOT Selected\n";
	}
}


require_once("common_lib.php");

if ($result) {
	/* free result set */
	mysqli_free_result($result);
}
/* close connection */
mysqli_close($link);


//meant for internal consumption
// TODO: process these variables here
unset($_REQUEST["startdate"]);
unset($_REQUEST["submit"]);
if ((isset($is_debug) && $is_debug)) {
	echo "Debug Enabled\n";
}
?>

<form id="visitor_add" name="visitor_add" class="appnitro"  method="post" action="visitor_action.php">

<div class="form_description" align="center">
<h2><img src="images/entry-icon.png" alt="Entry"> Visitor Check-in</h2>
<?php 
#echo "\n<p>";

#echo '<img align="left" src="disp_photo.php?vid='.$vid.'" height="180" width="240"/>';
echo "<table border=\"1\">";
echo "<tr>";
echo "<td>";
echo '<img align="left" src="disp_photo.php?vid='.$vid.'" height="180" width="240"/>';
echo "</td>";


echo "<td>";
echo "<table>";
echo "<td>";
// display details before inserting
foreach ($_REQUEST as $key => $value) {
	$value = sanitize($value);
	if ($key=="block_other") continue;
	if (!(strpos($key,"hidden")===false)) continue;
	//print "[$key] ";
	if ($is_vehicle_selected) {
		if ($key == "isVehicleSelected" && !$is_debug) continue;
	} else {

		if ($key == "vehicle_reg_num") continue;
		if ($key == "vehicle_type") continue;
	}
	echo "\n<tr>";
	echo "<td>";

	//$is_debug = true;
	if (isset($is_debug) && $is_debug) {
		echo $key . " / ";
	}

	if ($key==="vto_meet") {
		$key = "To Meet";
	}

	echo ucfirst(str_replace("_"," ",$key));
	echo "</td>";
	echo "<td>";
	if ($key=="vehicle_type") {
		show_image_by_vehicle_type($value);
	} else {
		echo $value;
	}
	if ($key == "name" && is_null($vid)) {
		echo "<img src='images/new-icon.png' title='First-Time Visitor'/>";
	} else if ($key == "name") {
		echo "<img src='images/repeat.png' title='Repeat Visitor'/>";
	}
	echo "</td>";
	echo "</tr>";
}
echo "\n</table>";

echo "</td>";
#echo "\n</p>";

echo "<td>";

?>

	    <!-- target for the canvas-->
	    <div id="canvasHolder" width="240" height="180"></div>

	    <!--preview image captured from canvas-->
	    <!-- CHANGE HEIGHT and WIDTH in the Line Below--> 
	    <!-- <img id="preview" src="images/placeholder-hi.png" width="640" height="480" /> -->
	    <img id="preview" src="images/placeholder-hi.png" width="240" height="180" />

	    <input id="picinfo_hidden" type="hidden" name="picinfo_hidden" type="text" value="" />

		    <!--
		    <input id="picinfo" name= "picinfo" class="element text small" type="text" maxlength="255" value=""/>
		    <input id="submit" class="button_text" type="submit" name="submit" value="Submit" />
		    <label>base64 image:</label>
		    -->
		    <!--
		    <input id="imageToForm" type="text" />
		    -->
		    <!--
	    </form>
	    -->
	    </div>
<?php
echo "</td>";
echo "\n</table>";
?>

<div id="content">

  <audio id="myTune">
    <source src="sounds/click.mp3">
    <source src="sounds/click.ogg">
  </audio>

  <button id="button" type="button" value="Click pic!" onclick="document.getElementById('myTune').play()">Click Pic</button>

  <input class="reset-this" type="checkbox" name="isSkipPicSelected" id="isSkipPicSelected" onclick="showSkipPicInfo()" value="isSkipPicSelected" style="margin-right: 0"/>Skip Pic
</div>

<BR>
<?php
echo '<input id="saveForm2" class="button_text" type="submit" name="submit" value="Confirm and Submit" onclick="return ValidateForm();"/>';


/*
if (is_exists_pic($vid)) {
	$is_exists_pic = true;
	echo "Pic exists\n";
} else {
	$is_exists_pic = false;
	echo "NO Pic exists\n";
}
if ($is_exists_pic) {
	echo '  <input type="checkbox" name="is_add_pic" value="is_add_pic" onclick="showCam()">Replace Pic<br>';
} else {
	echo '  <input type="checkbox" name="is_add_pic" value="is_add_pic" onclick="showCam()">Add Pic<br>';
}
 */
if (is_exists_pic($vid)) {
	if($is_debug) {
		echo ":Pic exists\n";
	}
}
?>

		<!--- take pic -->
    <div id="main">
	<video id="video"></video>
<!--
        <h3>Instructions</h3>
-->
    </div>

    <div>
<?php
	if (is_exists_pic($vid)) {
		$is_exists_pic = true;
		if($is_debug) {
			echo "Pic exists\n";
		}
		//echo '<font size="3" color="green">Pic exists, can Skip this Step</font>';
		//echo '<font size="3" color="green"><a href="index.php"><input type="button" value="Skip the pic!"></a></font>';
		echo "\n<input type=\"hidden\" name=\"is_exists_pic\" id=\"is_exists_pic\" value=\"true\" />";
	} else {
		$is_exists_pic = false;
		//echo "New Pic Needed\n";
		//echo '<font size="3" color="red">New Pic Needed, please take a pic</font>';
		echo "\n<input type=\"hidden\" name=\"is_exists_pic\" id=\"is_exists_pic\" value=\"false\" />";
	}
?>
<?php


//print_r($_REQUEST);
// if no pic exists, these details become of secondary importance
// we need to take the pic first
if (!is_exists_pic($vid)) {
	if($is_debug) {
		echo "No pic exists\n";
	}
}

foreach ($_REQUEST as $key => $value) {
	$value = sanitize($value);
	if ($key == "block_other") continue;
	/*
	if (!(isset($is_debug) && $is_debug)) {
		if (strpos(strtoupper($key),"HIDDEN")) {
			// do not display hidden values
			continue;
		}
	}
	 */
	echo "\n<input type=\"hidden\" name=\"$key\" value=\"$value\" />";
}
	echo "\n<input type=\"hidden\" name=\"vid\" value=\"$vid\" />";
?>


</form>






<script>
function ValidateForm()
{
	//alert("Doing validation");
	 // if ($('input:text').is(":empty")) {
	//alert("Really Doing validation:returning false");
	//alert("val is " + document.getElementById('is_exists_pic').value);
	//return false;
	if (document.getElementById('is_exists_pic').value == "true") {
		//alert("pic exists, so continuing");
		return true;
	}
	if (document.getElementById('picinfo_hidden').value) {
		//alert(" checked that pic taken");
		return true;
	} else {
		if ($('#isSkipPicSelected').is(':checked')) {	
			return true;
		}

		alert("No pic exists, please get your pic clicked\n");
		return false;
	}
}

        var video;
        var dataURL;

        //http://coderthoughts.blogspot.co.uk/2013/03/html5-video-fun.html - thanks :)
        function setup() {
            navigator.myGetMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);
            navigator.myGetMedia({ video: true }, connect, error);
        }

        function connect(stream) {
            video = document.getElementById("video");
            video.src = window.URL ? window.URL.createObjectURL(stream) : stream;
            video.play();
        }

        function error(e) { console.log(e); }

        addEventListener("load", setup);

        function captureImage() {
            var canvas = document.createElement('canvas');

            canvas.id = 'hiddenCanvas';
            //add canvas to the body element
            document.body.appendChild(canvas);
            //add canvas to #canvasHolder
            document.getElementById('canvasHolder').appendChild(canvas);
            var ctx = canvas.getContext('2d');
            //canvas.width = video.videoWidth;
	    //canvas.height = video.videoHeight;
	    // lets save some space in the db
	    // 800kb should become 129KB from 1:3 ratio
            canvas.width = video.videoWidth/3;
            canvas.height = video.videoHeight/3;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            //save canvas image as data url
            dataURL = canvas.toDataURL();
            //set preview image src to dataURL
            document.getElementById('preview').src = dataURL;
            // place the image value in the text box
            //document.getElementById('imageToForm').value = dataURL;
            //document.getElementById('picinfo').value = dataURL;
            document.getElementById('picinfo_hidden').value = dataURL;
        }

        //Bind a click to a button to capture an image from the video stream
        var el = document.getElementById("button");
        el.addEventListener("click", captureImage, false);

    </script>
		<!--- stop take pic -->








<div> <li id="li_15" class="buttons"> <input type="hidden" name="startdate" id="todayDate"/> </li> </div>
<div class="invalid-form-error-message"></div>



</body>
</html>


<?php
//echo "name is [$vname]";
exit(1);

?>


