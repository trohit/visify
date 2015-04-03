<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Visitor Edit Details</title>
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

<link rel="stylesheet" href="css/slimbox2.css" type="text/css" media="screen" />
<!--Start of find phone by name-->
<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/style.css">
<style>
.ui-autocomplete-loading {
	background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
}
</style>


</head>
<body id="main_body" onload="doLoadStuff()">
<noscript>
<style> #loading {display:none} </style>
       <p align="center"><img src="images/javascript_disabled.png" /></p>
<form action="?ui=html&amp;param=c" method="post"><input type="hidden" name="at" value="someval"><font face=arial>JavaScript must be enabled in order for you to use this site in standard view. However, it seems JavaScript is either disabled or not supported by your browser. To use standard view, enable JavaScript by changing your browser options, then <a href="">try again</a>.</font></form>
</noscript>

<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/json.js"></script>
<!--
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
-->
<script type="text/javascript" src="js/jquery-ui-1.10.4.js"></script>
<script type="text/javascript" src="js/block_insert.js"></script>


<script type="text/javascript">

function doLoadStuff()
{
	//alert("doing stuff");	
	getBannerDate();
	startTime();
	$("#phone_num").trigger("change");
				//$("#block").trigger("change");
}

function startTime() {
	var today=new Date();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	var y=today.getFullYear();
	var d=today.getDate();

	var days = ['Sleepy Sunday','Manic Monday','Teriffic Tuesday','Wacky Wednesday','Thrilling Thursday','Finally Friday','Shopping Saturday'];
	var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

	var day = days[ today.getDay() ];
	var month = months[ today.getMonth() ];

	m = checkTime(m);
	s = checkTime(s);
	$('#txt_date').html(d+" "+month+" "+day+" "+h+":"+m+":"+s);
	var t = setTimeout(function(){startTime()},100);
}

function checkTime(i) {
	if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
	return i;
}


function getBannerDate()
{
	var today = new Date().getTime() / 1000;
	today = Math.floor(today);
	$("#hiddenFormStartTime").val(today);
}

function showVehicleinfo()
{
	//alert("showVehicleinfo");
	if ($('#isVehicleSelected').is(':checked')) {	
		$("#vehicle_num").show("slow");
		$("#vehicle_type").show("slow");
		$("#txt_display_vehicle_labels").show("slow");
		$("#vehicle_type").focus();
	} else {
		$("#vehicle_num").hide();
		$("#vehicle_type").hide();
		$("#txt_display_vehicle_labels").hide();
	}
	 //   document.getElementById('block_other').style.display='inline';
}

function ValidateForm()
{
	//alert("Doing validation");
	return true;
}

$(document).ready(function(){
}); // end doc ready



</script>
<div id="loading">

	
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("common_lib.php");	
display_menu_common("Find Records");

// check if phone num was already provided
$vphone			= ((!empty($_REQUEST["phone_num"	]))?$_REQUEST["phone_num"]:NULL);
$vphone			= sanitize($vphone);
if (strlen($vphone)==0) {
	unset($vphone);
} 
?>
<div align="center">
<!i-- need to keep both imgs together without any spaces for no gaps between images -->
<img src="images/eco-logo.jpg" alt="Logo" border="0" height="90" width="100"/><img src="images/eco_building.jpg" alt="Logo"  border="0" height="90" width="700">
</div>

<div id="form_container" data-parsley-validate>
<img src="images/entry-icon.png" alt="Entry" style="float:left;margin:0 5px 0 0;" />Visit
<div id="txt_date" align="center"></div>
		<form id="edit_visitor" name="edit_visitor" class="appnitro"  method="post" action="visitor_add.php" target="_blank">

<?php
$vid		= ((!empty($_REQUEST["vid"	]))?$_REQUEST["vid"]:0);
$vid 		= sanitize($vid);
?>

<table border="0" width="100%">
   <tr>
     <td> <label class="description" for="element_1"><img src="images/person.jpg"/> Visitor Name * </label> </td>
     <td> <label class="description" for="element_10"> <img src="images/contact.jpg"/> Visitor Mobile Num*</label></td>
     <td> <label class="description" for="element_10">Visitor Pic</label></td>
<?php

	if (1) {
		// if pic exists, but user clicked another pic, then push into backup pic db
		$result = DB::queryFirstRow("SELECT vname, vphone, vcomments, vphoto FROM visitor WHERE vid=%i", $vid);
		$vphone = $result['vphone'];
		$vname =  $result['vname'];
		//print_r($result);
	}
?>			

   </tr>
   <tr>
     <td> <input id="name" name="name" type="text" maxlength="32" value="<?php echo $vname;?> " class="element text large"  placeholder="FirstName LastName" style="text-transform:capitalize" style="whitespace:nowrap" data-regexp="^[A-Za-z ]+$" data-required="true"/> </td>
     <td> <input id="phone_num" name="phone_num" type="text" class="element text medium" autofocus data-trigger="keyup"  data-type="digits" data-rangelength="[10,10]" data-required="true" title="phone consist of 10 numeric characters." <?php echo (isset($vphone))?('value="'.$vphone.'"'):""; ?>/></td>
     <td> <img src="disp_photo.php?vid=<?php echo $vid;?>" height="220" width="220"</img></td>
   </tr>
	<td></td>
	<td></td>
	<td><a href="edit_pic.php">Edit Photo</a></td>
   <tr>
     <td colspan="2"> <div id="txt_side_msg"></div> </td>
     <td> <div id="hint_visitor_pic" align="right"></div> </td>
   </tr>
</table>


<img id="top" src="images/top.png" alt="">

			<label class="description" for="element_8">Comments </label>
			<input id="comments" name="comments" class="element text medium" type="text" maxlength="128" value="" placeholder="(optional)"/> 
			</li>
		</div> 

<div>
	<li id="li_15" class="buttons">
<!--
			    <input type="hidden" name="form_id" value="826681" />
-->
<input type="hidden" name="hiddenFormStartTime" id="hiddenFormStartTime"/>
	</li>
</div>
	      <div class="invalid-form-error-message"></div>

<div>
<!--
<input id="saveForm" class="button_text" type="submit" name="submit" value="Continue" />
-->
<input type="submit" class="btn btn-default validate"  name="submit" value="Submit" onclick="return ValidateForm();"/>
</div> 
</form>

<div id="txt_visitor_history" style='display:none'></div>

<div id="footer" align="center"> 
&copy; Visify 2014<?php if(date("Y") != "2014") echo "-".date("Y")?> 
</div>
</div>

<!--
Important that this parsley script stays here. 
Moving it anywhere else tends to break the form validation.
-->
				<script type="text/javascript" src="js/parsley.min.js"></script>
				<script type="text/javascript" src="js/slimbox2.js"></script>
				<!--
</div>
	<img id="bottom" src="images/bottom.png" alt="">
</div>
-->
    <div id="main">
	<video id="video"></video>
<!--
        <h3>Instructions</h3>
-->
    </div>

    <div>
	    <li id="li_1" >
<!--
	    <a id="button">Smile and click here to take a picture :-)</a>
-->

<div id="content">

  <audio id="myTune">
    <source src="sounds/click.mp3">
    <source src="sounds/click.ogg">
  </audio>

  <button id="button" type="button" value="Click pic!" onclick="document.getElementById('myTune').play()">Click Pic</button>

</div>
	    <!-- target for the canvas-->
	    <div id="canvasHolder" width="320" height="240"></div>

	    <!--preview image captured from canvas-->
	    <!-- CHANGE HEIGHT and WIDTH in the Line Below--> 
	    <!-- <img id="preview" src="images/placeholder-hi.png" width="640" height="480" /> -->
	    <img id="preview" src="images/placeholder-hi.png" width="320" height="240" />

	    <input id="picinfo_hidden" type="hidden" name="picinfo_hidden" type="text" value="" />
</div>
<script>
//scripty stuff to take photos
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
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
	    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
	    $('#video').toggle();

	    ctx.scale(-1, 1); //flip the image horizontally, for mirror image toggling
            //save canvas image as data url
            dataURL = canvas.toDataURL();
            //set preview image src to dataURL
            document.getElementById('preview').src = dataURL;
            // place the image value in the text box
            //document.getElementById('imageToForm').value = dataURL;
            //document.getElementById('picinfo').value = dataURL;
            document.getElementById('picinfo_hidden').value = dataURL;
	}

        //Change the mirror mode on click
        $(document).click(function () {
            $('#vidme').toggle();
            $('#mirror').toggle();
            console.log("toggling outputs");
        });

        //Bind a click to a button to capture an image from the video stream
        var el = document.getElementById("button");
        el.addEventListener("click", captureImage, false);
<!--- stop take pic -->

</script>
</body>
</html>
