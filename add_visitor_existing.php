<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>New Visitor</title>
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
<script src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/block.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="images/top.png" alt="">
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';

require_once("common.php");	
display_menu_common("Visitor Checkin");
DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted
?>
	<div id="form_container">
	
		<h1><a>New Visitor</a></h1>
		<!--
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="new_visitor.php" data-parsley-validate>
			-->	
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="add_visitor_existing_action.php">
					<div class="form_description">
			<h2>New Visitor Form</h2>
			<p>Just a few more details and you are done... :)</p>
			<p><a href="find.php">Returning Visitors, click here</a></p>

		</div>						
			<ul >
			
		<!--- take pic -->
    <div id="main">
        <video id="video"></video>
        <h1>Instructions</h1>

    </div>

    <div>
	    <li id="li_1" >
	    <a id="button">Smile and click here to take a picture :-)</a>
	    </li>

	    <!-- target for the canvas-->
	    <div id="canvasHolder" width="160" height="120"></div>

	    <!--preview image captured from canvas-->
	    <img id="preview" src="images/placeholder-hi.png" width="160" height="120" />

	    <!--
	    <form method="post" action="new_visitor.php">
		    -->
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

<script>

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
            canvas.width = video.videoWidth / 2;
            canvas.height = video.videoHeight / 2;
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
<?php
global $arr;
/*
	echo "<table>\n";
    foreach ($_POST as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        echo $value;
        echo "</td>";
	echo "</tr>";
	$arr[$key] = $value;
    }
echo "</table>\n";
 */
    foreach ($_POST as $key => $value) {
	    $arr[$key] = $value;
    }
//print_r($arr);
$row = DB::queryFirstRow("SELECT * FROM visitor WHERE vid=%s LIMIT 1", $arr['vid']);
//$results = DB::queryFirstRow("SELECT * FROM visitor WHERE vid=%s LIMIT 1", $arr['vid']);
//print_r($results);
	echo "<table>\n";
//foreach ($results as $row) {
	foreach ($row as $key => $value) {
		$arr[$key] = $value;
		//if ($key == "vid") $key = "ID";
		if ($key == "vid") continue;
		if ($key == "vname") $key = "Name";
		if ($key == "vphone") $key = "Phone";
		if ($key == "vgender") { $key = "Gender"; if ($value == 1) $value = "Female"; else $value = "Male"; }
		if ($key == "vage") continue;
		echo "<tr>";
		echo "<td>";
		echo $key;
		echo "</td>";
		echo "<td>";
		if($key == "vphoto") { 
			//print first 25 chars of val
			//echo mb_substr($value, 0, 25);
			echo "<td><a href=\"disp_photo.php?vid=".$arr['vid']."\" target=\"_blank\"><img src=\"disp_photo.php?vid=".$arr['vid']."\" alt=\"\" border=3 height=100 width=100/></a></td>\n";
			continue;  
		} else {
			echo $value;
		}
		echo "</td>";
		echo "</tr>";
	}
//}
	echo "</table>\n";
	echo "<input type=\"hidden\" name=\"vid\" value=\"".$arr['vid']."\" />";

	/* also get some helpful hints on where this visitor has been to in the past. */
	//$results = DB::query("SELECT DISTINCT vflatnum, vblock FROM vrecord WHERE vid=%s ORDER BY vrecordid DESC LIMIT 5", $arr['vid']);
	$results = DB::query("SELECT vflatnum,vblock,vitime FROM vrecord WHERE vid=%s ORDER BY vrecordid DESC LIMIT 5", $arr['vid']);
	echo "\n";
	echo '<table border="1">';
	echo "\n";
	echo '<tr>';
	echo '<th colspan="3">';
	echo "\n";
	echo "Flats most recently Visited";
	echo '</th>';
	echo '</tr>';
	echo "\n";
	foreach ($results as $row) {
		echo '<tr>';
		echo "<td>".$row['vblock']."</td>"."<td>".$row['vflatnum']."</td>";
		echo "<td>".$row['vitime']."</td>";
		echo '</tr>';
		echo "\n";
	}
	echo '</table>';

?>



<!--
		<div>
		<li id="li_4" >
			<label class="description" for="element_44">Age</label>
			<input id="age" name="age" class="element text small" type="text" maxlength="3" value=""/> 
		</li>
		</div> 
-->

		<div>
			<li id="li_5" >
			<label class="description" for="element_2">Total Number of Visitors *</label>
			<input id="num_visitors" name="num_visitors" class="element text small" type="text" maxlength="3" value="1" placeholder="Enter total number of visitors"/> 
			</li>
		</div> 
<!--
		<div>
			<li id="li_6" >
			<label class="description" for="element_10">Phone Number *</label>
			<input id="phone_num" name="phone_num" class="element text small" type="text" value="" placeholder="9886110220" data-parsley-type="digits" required/> 
			</li>
		</div> 
-->
		<div>
			<li id="li_7" >
			<label class="description" for="element_4">Vehicle Registration Number </label>
			<input id="vehicle_reg_num" name="vehicle_reg_num" class="element text small" type="text" maxlength="255" value="" placeholder="KA01ME1234"/> 
			</li>
		</div> 

		<div>
			<li id="li_8" >
			<label class="description" for="element_8">Block *</label>
			<select name="block" id="block" class="element select small" required onChange="block_changed(this.value);">
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_block_options("block.ini", $display_dummy_option);
				?>
			</select>
			</li>
		</div> 

		<div>
			<li id="li_81" >
			<label class="description" id="label_block_other" style='display:none'>Specify where exactly:</label>
			<input type="text" name="block_other" id="block_other" style='display:none'/>
			</li>
		</div> 

		<div>
			<li id="li_9" >
			<label class="description" for="element_8">Flat Number *</label>
			<span id='flat_span'>
				<select name="flat_num" style='display:block' required>
					<option value="0">All</option>
				</select>
			</span>
			</li>
		</div> 


		<div>
			<li id="li_10" >
			<label class="description" for="element_6">To Meet *</label>
			<input id="to_meet" name="vto_meet" class="element text medium" type="text" maxlength="32" value="" placeholder="Barrack Obama" required/> 
			</li>
		</div> 

		<div>
			<li id="li_11" >
			<label class="description" for="element_7">Purpose </label>
			<select class="element select small" id="purpose" name="purpose"> 
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_purpose_options("purpose.ini", $display_dummy_option);
				?>
			</select>
			</li>
		</div> 

		<div>
			<li id="li_12" >
			<label class="description" for="element_19">From Address/Area </label>
			<input id="address" name="address" class="element text medium" type="text" maxlength="255" value="" placeholder="(optional)"/> 
			</li>
		</div> 

<!--
		<div>
			<li id="li_13" >
			<label class="description" for="element_8">Comments </label>
			<textarea id="comments" name="comments" class="element textarea small"></textarea> 
			</li>
		</div> 

		<div>
			<li id="li_14" >
			<label class="description" for="element_8">Vehicle Description</label>
			<textarea id="comments" name="comments" class="element textarea small"></textarea> 
			<input id="vehicle_desc" name="vehicle_desc" class="element text medium" type="text" maxlength="255" value=""/> 
			</li>
		</div> 
-->
			
		<div>
			<li id="li_15" >
					<li class="buttons">
			    <input type="hidden" name="form_id" value="826681" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
				<button type="reset" value="Reset">Reset</button>
		</li>

			</ul>
		</form>	
		<div id="footer">
			Generated by <a href="http://www.phpform.org">pForm</a>
		</div>
	</div>

</div>

<!--
Important that this parsley script stays here. 
Moving it anywhere else tends to break the form validation.
-->
<div>
<script type="text/javascript" src="js/parsley.min.js"></script>
</div>
	<img id="bottom" src="images/bottom.png" alt="">
	</body>
</html>
<html>
<?php
//phpinfo();
//exit(1);
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
?>    
</html>
