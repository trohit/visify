<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Visitor Check-in</title>
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
	<script>
$(function() {
	$( "#phone_num" ).autocomplete({
		source: "search.php",
			minLength: 2,
			delay: 90,
			//autoFocus: true,
			select: function( event, ui ) {
/*
				log( ui.item ?
					"Selected: " + ui.item.value + " aka " + ui.item.id :
					"Nothing selected, input was " + this.value );
 */	
	//alert("Searchin for " + $("#phone_num"));
	//alert("Searchin for " + this.value);
	$("#phone_num").trigger("change");
			}
	});

});
</script>
<!--End of find phone by name-->
<!--
<link rel="stylesheet" href="js/parsley.min.js">
-->


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
	showVehicleinfo();
}

function startTime() {
	var today=new Date();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	var y=today.getFullYear();
	var d=today.getDate();

	//var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
	var days = ['Sleepy Sunday','Manic Monday','Teriffic Tuesday','Wacky Wednesday','Thrilling Thursday','Finally Friday','Shopping Saturday'];
	var months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

	var day = days[ today.getDay() ];
	var month = months[ today.getMonth() ];

	m = checkTime(m);
	s = checkTime(s);
	//document.getElementById('txt_date').innerHTML = d+" "+month+" "+day+" "+h+":"+m+":"+s;
	//document.getElementById('hiddenFormStartTime').innerHTML = d+" "+month+" "+day+" "+h+":"+m+":"+s;
	
	//$('txt_date').innerHTML = d+" "+month+" "+day+" "+h+":"+m+":"+s;
	//$('hiddenFormStartTime').innerHTML = d+" "+month+" "+day+" "+h+":"+m+":"+s;
	
	$('#txt_date').html(d+" "+month+" "+day+" "+h+":"+m+":"+s);
	//$('#hiddenFormStartTime').html(d+" "+month+" "+day+" "+h+":"+m+":"+s);
	//var t = setTimeout(function(){startTime()},500);
	var t = setTimeout(function(){startTime()},100);
}

function checkTime(i) {
	if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
	return i;
}


function getBannerDate()
{
	//var today = new Date();
	var today = new Date().getTime() / 1000;
	today = Math.floor(today);
	//alert(today);
	$("#hiddenFormStartTime").val(today);
}
function resetOtherFields()
{
	//alert("resetting");
	block_changed(this.value);
	//$("#flat_num").value="0";
	$('#flat_num').trigger('reset');
	$("#block").trigger("change");
	//alert("changing txt_visitor_history");
	//$("#txt_visitor_history").value="0";
	$("#txt_side_msg").html("");
}

/*
$('#isVehicleSelected').click(function () {
	alert("heloo");
    $("#vehicle_num").toggle(this.checked);
    $("#vehicle_type").toggle(this.checked);
});
*/
function showVehicleinfo()
{
	//alert("showVehicleinfo");
	if ($('#isVehicleSelected').is(':checked')) {	
		$('#vehicleTable').attr('border', '1');
		$("#vehicle_num").show("fast");
		$("#vehicle_type").show("fast");
		$("#txt_display_vehicle_labels").show("fast");
		$("#vehicle_type").focus();
	} else {
		$('#vehicleTable').attr('border', '0');
		$("#vehicle_num").hide();
		$("#vehicle_type").hide();
		$("#txt_display_vehicle_labels").hide();
	}
	 //   document.getElementById('block_other').style.display='inline';
}

function ValidateForm()
{
	//alert("Doing validation");
	/*
	if($("#isVehicleSelected").is(':checked')) {
		// check that vehicle type is selected and vehicle registration number entered
		if(!$('input[name=vehicle_type]').is(":checked")) {
			alert( "Vehicle Added but 'Vehicle Type' is not selected");
			$("#4w").focus();
			return false;
		} else if($('#vehicle_reg_num').val().length == 0) {
			alert( "Vehicle Added but 'Registration Number' is not entered");
			$("#vehicle_reg_num").focus();
			return false;
		}
	}
	*/

	if($("#isVehicleSelected").is(':checked')) {
		if(($('input[name=vehicle_type]').is(":checked")) && ($('#vehicle_reg_num').val().length == 0)) {
			alert( "Vehicle Added but 'Registration Number' is not entered. Uncheck 'Add Vehicle' if there is no vehicle.");
			$("#vehicle_reg_num").focus();
			return false;
		} else if (($('#vehicle_reg_num').val().length != 0) && !($('input[name=vehicle_type]').is(":checked") )) {
			alert( "Select Vehicle Type Car/Bike OR uncheck 'Add Vehicle' if there is no vehicle.");
			$("#vehicle_type").focus();
			return false;

		} 
	} else {
			//alert($('#vehicle_reg_num').val().length);
			//alert("setting isVehicleSelected to false");
			$('#isVehicleSelected').prop('checked', false);
			$('#isVehicleSelected').val('FALSE');
		}

	if (document.getElementById("block_other").value=="" && 
		document.getElementById("flat_num").value=="") {

			if (document.getElementById("block").value =="") {
				//alert("no block selected,so returning");
				return;
			}
			if (document.getElementById("block").value !="Others") {
				alert("Need to specify Flat Number");
				return false;
			} else if (document.getElementById("block").value=="Others") {
				alert("Need to specify where exactly the visitor is going");
				return false;
			}
			//alert("both fields empty");
			//return false;
			return;
		} else {
			//alert("either flat_num or other_detail provided, so thumbs up");	
			return true;
		}
}
/*
function showNameHint(str)
{
var xmlhttp;
if (str.length==0)
  { 
  document.getElementById("txtHint_name").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint_name").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","gethint_name.php?name="+str,true);
xmlhttp.send();
}
 */

$(document).ready(function(){
	if ($("#block").val() != "") {
		$("#block").trigger("change");
	}
/*
	$("#phone_num").change(function(){
		alert(" phone nume Clicked");
	});
		
	$("#phone_num").mouseleave(function(){
		alert(" Mouse Leave");
	});

	$("#phone_num").focusout(function(){
		alert(" Mouse Leave");
	});
*/
	//$("#phone_num").change(function(){
	$("#name").focusin(function(){
		//alert("The text has been changed.");
		$("#txt_side_msg").html("<img src='images/ajax-loader.gif' /> checking...");


		var phone_num=$("#phone_num").val();
		var num_limit = 5;

		var url = 'visitor_fetch_details.php' + '?phone_num=' + phone_num + '&num_limit=' + num_limit;
		//alert(url);

/*
		$.getJSON(url,function(result){
			$.each(result, function(i, field){
				$("div").append(field + " ");
			});
		});
*/

		$.getJSON(url,function(result1){
			$("#txt_side_msg").html("");


			var result;
			var isFirstRow = true;
			$.each(result1.slice(0,num_limit), function(i, data) {
				//use latest row to pre-fill form
				if (isFirstRow) {
					isFirstRow = false;
					result = data;
				}
				// headcount not populated intentionally
				// vehicle type not populated intentionally
				// TODO: purpose
				var ul_data = data.vitime+" "+data.vtomeet+" "+data.vblock+" "+data.vflatnum;
				if (data.vvehicle_type) {
					ul_data = ul_data + " " + data.vvehicle_type;
				}
				if (data.vvehicle_reg_num) {
					ul_data = ul_data + " " + data.vvehicle_reg_num;
				}
				ul_data = ul_data + "<BR>";
				$("#txt_side_msg").append(ul_data);

			});

			if (result) {
				//$("#txt_side_msg").html("");
				//alert("As per records, name is " + result.vname + " filling other fields");
				// vname,vphone,vctime,vtomeet,vblock,vflatnum,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid AND visitor.vphone='$phone_num' ORDER BY vrecordid DESC LIMIT 1");
				$("#name").val(result.vname);
				$('#'+result.vvehicle_type).prop('checked',true);
				$("#vehicle_reg_num").val(result.vvehicle_reg_num);
				$("#to_meet").val(result.vtomeet);
				$("#block").val(result.vblock);
				// notify other listeners that this field changed
				$("#block").trigger("change");
				if ($("#block").val() == "Others") {
					$("#block_other").val(result.vflatnum);
				} else {
					$("#flat_num").val(result.vflatnum);
				}
				$("#comments").val(result.vcomments);
				$("#purpose").val(result.vpurpose);
				//alert(result.vid);
				/*
				var img = $("<img />").attr('src', 'disp_photo.php?vid=' + result.vid)
				$("#hint_visitor_pic").append('<img src=\"disp_photo.php?vid=' + result.vid+'"/>');
				 */

				//var img = $("<img />").attr('src', 'images/eco-logo.jpg')
				var img = $("<img />").attr('src', 'disp_photo.php?vid=' + result.vid)
					.load(function() {
						if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
							alert('broken image!');
						} else {
							$("#hint_visitor_pic").html("");
							$("#hint_visitor_pic").append(img);
						}
					}).height(144).width(192);
					//}).height(180).width(240);
				// Next get details and poulate "txt_past_visits"
				//getPastVisits();

				// now that historical data for visitor is populated
				// the user needs to scroll down. do it for him
				//alert("scrolling down");
				//alert("gonna scroll");
				//window.scrollBy(0, 300);
				$('html, body').animate({scrollTop:$(document).height()}, 'fast');


			} else {
				//alert("Guessing its a NEW Visitor!");
				var prev_phone_num=$("#phone_num").val();
				$('#new_visitor').trigger('reset');
				$("#block").trigger("change");
				//alert(prev_phone_num);
				$("#phone_num").val(prev_phone_num);
			}
		}); //end of getJSON



	}); //end of phone num change
}); // end doc ready




/*
	function getPastVisits()
	{
		alert("Populating past visits.");
		$("#txt_side_msg").html("<img src='images/ajax-loader.gif' /> checking past visits...");


		var phone_num=$("#phone_num").val();

		var url = 'visitor_fetch_details.php' + '?phone_num=' + phone_num + '&num_limit=5';
		alert(url);


		$.getJSON(url,function(result){
			$("#txt_side_msg").html("");
			//$.each(result, function(i, field){
			//	$("div").append(i+":"+field + " ");
			//});
			//alert("got json callback");
			$("#txt_past_visits").html("");
			if (result) {
				//alert("As per records, name is " + result.vname + " filling other fields");
				$("#txt_past_visits").val(result);
				//$("#block").trigger("change");
			}

		}); //end of getJSON


	}
}); // end doc ready
*/



</script>





<div id="loading">

	
<?php
require_once("common.php");	
display_menu_common("Visitor Checkin");

// check if phone num was already provided
$vphone			= ((!empty($_REQUEST["phone_num"	]))?$_REQUEST["phone_num"]:NULL);
if (strlen($vphone)==0) {
	unset($vphone);
} 
?>
<div align="center">
<!i-- need to keep both imgs together without any spaces for no gaps between images -->
<img src="images/eco-logo.jpg" alt="Logo" border="0" height="90" width="100"/><img src="images/eco_building.jpg" alt="Logo"  border="0" height="90" width="700">
</div>

<div id="form_container" data-parsley-validate>
<!--
<div align="center">
<img src="images/eco-logo.jpg" alt="Logo" height="64" width="64"/>
<img src="images/eco_building.jpg" alt="Logo" height="90" width="800">
<div id="txt_date"></div>
</div>
-->
<img src="images/entry-icon.png" alt="Entry" style="float:left;margin:0 5px 0 0;" />Visit
<div id="txt_date" align="center"></div>
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="visitor_add.php" data-validate="parsley">

		<div class="form_description">
<!--
			<h2><img src="images/entry-icon.png" alt="Entry"> Visitor Check-in</h2>
-->
<!--
			<p>Please enter details carefully. Visitors are tracked by their contact number</p>
-->
		</div>						

<table border="0" width="100%">
<!--
   <tr>
     <td span="3"> <label class="description" for="element_10"> Visitor Details</label></td>
   </tr>
-->
   <tr>
     <td> <label class="description" for="element_10"> <img src="images/contact.jpg"/> Visitor Mobile Num*</label></td>
     <td> <label class="description" for="element_1"><img src="images/person.jpg"/> Visitor Name * </label> </td>
     <td> <label class="description" for="element_2"><img src="images/num_ppl.png"/> Headcount * </label> </td>
   </tr>
   <tr>
     <td> <input id="phone_num" name="phone_num" type="text" class="element text medium" autofocus data-trigger="keyup"  data-type="digits" data-rangelength="[10,10]" data-required="true" title="phone consist of 10 numeric characters." <?php echo (isset($vphone))?('value="'.$vphone.'"'):""; ?>/></td>
<!--
     <td> <input id="name" name="name" type="text" maxlength="32" value="" class="element text large"  placeholder="FirstName LastName" data-regexp="^[A-Za-z ]+$" data-required="true" onkeyup="showNameHint(this.value)"/> </td>
-->
     <td> <input id="name" name="name" type="text" maxlength="32" value="" class="element text large"  placeholder="FirstName LastName" style="text-transform:capitalize" style="whitespace:nowrap" data-regexp="^[A-Za-z ]+$" data-required="true"/> </td>
     <td align="center"> <input id="num_visitors" name="num_visitors" type="number" style="width:32px;" size="4" maxlength="3" min="1" max="50" step="1" value="1" data-required="true" data-parsley-type="integer" data-parsley-min="1"/> </td>
   </tr>
   <tr>
     <td colspan="2"> <div id="txt_side_msg"></div> </td>
<!--
     <td> <div id="txtHint_name"></div> </td>
     <td> <div id="txt_past_visits"></div> </td>
-->
     <td> <div id="hint_visitor_pic" align="right"></div> </td>
   </tr>
</table>

<!--
<table border="1" id="vehicleTable" id="vehicleTable" width="100%" style='display:inline'>
-->
<table border="0" id="vehicleTable" id="vehicleTable" width="100%">
   <tr id="txt_display_vehicle_labels" style='display:none'>
     <td> <label class="description" for="element_10"> Add Vehicle</label></td>
     <td> <label class="description" for="element_10"> Vehicle Type</td>
     <td> <label class="description" for="element_10"> Registration Number</td>
   </tr>
   <tr>
     <td>
	<input class="description" type="checkbox" name="isVehicleSelected" id="isVehicleSelected" onclick="showVehicleinfo()" value="vehicle" checked title="Add Vehicle Details"></td>
     <td id="vehicle_type" style='display:none'> 
	<input class="description" for="element_10" type="radio" name="vehicle_type" id="4w" value="4w"><img src="images/car_bw.png" alt="car" height="48" title="Car"/>
<br>
	<input class="description" for="element_10" type="radio" name="vehicle_type" id="2w" value="2w"><img src="images/bike.png" alt="bike" height="36" title="Bike"/>
<!--
	<input class="description" for="element_10" type="radio" name="vehicle_type" value="4w"> Car
	<input class="description" for="element_10" type="radio" name="vehicle_type" value="2w"> Bike
-->

     </td>
     <td id="vehicle_num" name="vehicle_num" style='display:none'> 
	<input type="text" id="vehicle_reg_num" name="vehicle_reg_num" class="element text big" maxlength="25" value="" placeholder="KA03AB1234" data-trigger="keyup"  data-type="alphanum" data-rangelength="[7,10]"  style="text-transform:uppercase" title="phone consist of 10 numeric characters."/>
     </td>
<!--
     <td>
	<input type="color" name="favcolor"><br>
     </td>
-->

   </tr>
</table>
<!--
<input type="radio" name="vehicle_type" value="4w"><img src="images/car.jpg" alt="car" title="Car"/>
<input type="radio" name="vehicle_type" value="2w"><img src="images/bike.jpg" alt="bike" title="Bike"/>

		<div>
			<li id="li_7" >
			<label class="description" for="element_4"> <img src="images/car.jpg"/> Vehicle Registration Number </label>
			<input type="text" id="vehicle_reg_num" name="vehicle_reg_num" class="element text small" maxlength="25" value="" placeholder="KA03AB1234" data-trigger="keyup"  data-type="alphanum" data-rangelength="[7,10]" title="phone consist of 10 numeric characters."/>
			</li>
		</div> 
-->

<img id="top" src="images/top.png" alt="">
<!-- Begin Owners details-->
		<div>
			<li id="li_10" >
			<label class="description" for="element_6">  <img src="images/tomeet_icon.jpg" width="32" height="32"/>Name of Resident / Block Name / Flat Number *</label>
			<input id="to_meet" name="to_meet" class="element text small" type="text" maxlength="32" value="" placeholder= "Barrack Obama" style="text-transform:capitalize" data-required="true"/> 

			<select name="block" id="block" class="element select small" onChange="block_changed(this.value);" data-required="true">
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_block_options("block.ini", $display_dummy_option);
				?>
			</select>
			<select name="flat_num" id='flat_num' class="element select small" title="Flat number needs to be selected">
			</select>

			<label class="description" id="label_block_other" style='display:none'>Specify where exactly:</label>
			<input type="text" name="block_other" id="block_other" style='display:none'/>
			</li>
		</div> 




<!-- End Owners details-->
<img id="top" src="images/top.png" alt="">
		<div>
			<li id="li_11" >
			<label class="description" for="element_7">Purpose *</label>
<!--
			<input id="purpose" name="purpose" class="element text medium" type="text" maxlength="128" value="" placeholder="(optional)"/> 
-->
			<select class="element select small" id="purpose" name="purpose"> 
				<!--
				<option value="" selected="selected"></option>
				-->
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				//display_purpose_options("purpose.ini", $display_dummy_option);
				display_purpose_options("purpose.ini");
				?>
			</select>

			<label class="description" for="element_8">Comments </label>
			<input id="comments" name="comments" class="element text medium" type="text" maxlength="128" value="" placeholder="(optional)"/> 
			</li>
		</div> 



<!--
   <span class="btn btn" id="new_visitor-valid" onclick="javascript:$('#registration_form').parsley( 'validate' );"><i class="icon-ok"></i></span>
-->

<div>
	<li id="li_15" class="buttons">
<!--
			    <input type="hidden" name="form_id" value="826681" />
-->
<input type="hidden" name="hiddenFormStartTime" id="hiddenFormStartTime"/>
	</li>
</div>
              <div class="invalid-form-error-message"></div>
<!--
<input id="saveForm" class="button_text" type="submit" name="submit" value="Continue" />
-->
<input type="submit" class="btn btn-default validate"  name="submit" value="Continue" onclick="return ValidateForm();"/>
<button type="reset" value="Reset" onclick="resetOtherFields()">Reset</button>
</div> 
</form>

<div id="txt_visitor_history" style='display:none'></div>

<div id="footer" align="center"> 
&copy; Visify 2014<?php if(date("Y") != "2014") echo "-".date("Y")?> 
</div>
</div>
<!--
<script type="text/javascript">
$(document).ready(function () {
alert("checking");
  $('#new_visitor').parsley().subscribe('parsley:form:validate', function (formInstance) {

    // if one of these blocks is not failing do not prevent submission
    // we use here group validation with option force (validate even non required fields)
    if (formInstance.isValid('block1', true) || formInstance.isValid('block2', true))
      return;

    // else stop form submission
    formInstance.submitEvent.preventDefault();

    // and display a gentle message
    $('.invalid-form-error-message')
      .html("You must correctly fill at least one of these 2 blocks' fields!")
      .addClass("filled");
    return;

    $('.invalid-form-error-message').html('');
  });
});
</script>
-->

<!--
Important that this parsley script stays here. 
oving it anywhere else tends to break the form validation.
-->
				<script type="text/javascript" src="js/parsley.min.js"></script>
				<script type="text/javascript" src="js/slimbox2.js"></script>
				<!--
</div>
	<img id="bottom" src="images/bottom.png" alt="">
</div>
-->
</body>
</html>
