<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<!--
<link rel="stylesheet" href="js/parsley.min.js">
-->
<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.4.js"></script>
<script type="text/javascript" src="js/block_insert.js"></script>

<script type="text/javascript">
function ValidateForm()
{
	//alert("Doing validation");
	if (document.getElementById("block_other").value=="" && 
		document.getElementById("flat_span").value=="") {

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

$(document).ready(function(){




	$("#phone_num").change(function(){
		//alert("The text has been changed.");
		$("#phone_message").html("<img src='images/ajax-loader.gif' /> checking...");


		var phone_num=$("#phone_num").val();

		$.ajax({
			type:"post",
				url:"checknum.php",
				data:"phone_num="+phone_num,
				success:function(data){
					if(data==0){
						//$("#phone_message").html("<img src='images/tick.jpg' /> New Phone Number");
						$("#phone_message").html("<img src='images/new-icon.png' />phone number indicates a new visitor");
						//alert('['+$("#name").val+']');
						/*
						if (document.getElementById("name").innerHTML) != NULL) {
							$("#name").val(data);
						} else {
							alert("name contains something");
						}
						*/
					}
					else{
						//$("#phone_message").html("<img src='images/tick.jpg' /> Number already belongs to a visitor mentioned below");
						$("#phone_message").html("<img src='images/tick.jpg' /> Belongs to visitor '" + (data) + "'");
						//$("#phone_message").html(data);
						//alert(data);
						$("#name").val(data);
						//this also works!
						//document.getElementById("name").value=data;
					}
				}
		});

	});
/*
	$("#name").change(function(){
		$("#name_message").html("<img src='images/ajax-loader.gif' /> checking for phone no of this visitor...");


		var name=$("#name").val();

		$.ajax({
			type:"post",
				url:"checkname.php",
				data:"name="+name,
				success:function(data){
					if(data==0){
						if ($("#phone_num").val() == "") {
							$("#name_message").html("<img src='images/new-icon3.png' />indicates a new visitor or many matches with the same name, please enter visitors phone number too, so he/she can be uniquely identified");
}
//$("#phone_num").val(data);
} else {
	$("#name_message").html("<img src='images/tick.jpg' /> Belongs to visitor with phone number '" + (data) + "'");
	//alert(data);
	$("#phone_num").val(data);
	//this also works!
	//document.getElementById("name").value=data;
}
}
}); //within name ajax change
}); //end name change
 */
}); // end doc ready
</script>

</head>
<body id="main_body" >
	
<?php
require_once("common.php");	
display_menu_common("Visitor Checkin");
?>
<div id="form_container" data-parsley-validate>
<h1> <img src="images/eco-logo.jpg" alt="Logo" height="64" width="64"/></h1>
<div align=center">
<img src="images/eco_building.jpg" alt="Logo" height="100" width="750">
</div>
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="returningvisitor_action.php" data-validate="parsley">

		<div class="form_description">
			<h2><img src="images/entry-icon.png" alt="Entry"> Visitor Check-in</h2>
			<p>Please enter details carefully. Visitors are tracked by their name and contact number</p>
		</div>						

		<div>
			<li id="li_10" >
			<label class="description" for="element_6">  <img src="images/tomeet_icon.jpg"></img>To Meet *</label>
			<input id="to_meet" name="vto_meet" class="element text small" type="text" maxlength="32" value="" placeholder= "Barrack Obama" required/> 
			</li>
		</div> 


		<div>
			<li id="li_8" >
			<label class="description" for="element_8"> Block Name / Flat Number *</label>
			<select name="block" id="block" class="element select small" onChange="block_changed(this.value);" data-required="true">
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_block_options("block.ini", $display_dummy_option);
				?>
			</select>

			<select name="flat_num" id='flat_span' class="element select small" title="Flat number needs to be selected">
<!--
				<option value="0">All</option>
-->
			</select>

			<label class="description" id="label_block_other" style='display:none'>Specify where exactly:</label>
			<input type="text" name="block_other" id="block_other" style='display:none'/>

			</li>
		</div> 

<img id="top" src="images/top.png" alt="">

		<div>
		<li id="li_21" >
			 <div id="txtHint_name"></div> 
		<label class="description" for="element_1"><img src="images/person.jpg"></img> Visitor Name</label>
			<input id="name" name="name" class="element text small" type="text" maxlength="32" value="" placeholder="FirstName LastName" data-regexp="^[A-Za-z ]+$" data-required="true" onkeyup="showNameHint(this.value)"/>
		</li>
		</div>

		<div id="name_message"></div>

		<div>
		<li id="li_2" >
			<label class="description" for="element_10"> <img src="images/contact.jpg"></img> Phone Number</label>
			<input id="phone_num" name="phone_num" class="element text small" type="text" data-trigger="keyup"  data-type="digits" data-rangelength="[10,10]" data-required="true" title="phone consist of 10 numeric characters."/>
		</li>
		</div> 

		<div id="phone_message"></div>



		<div>
			<li id="li_7" >
			<label class="description" for="element_4"> <img src="images/car.jpg"></img> Vehicle Registration Number </label>
			<input type="text" id="vehicle_reg_num" name="vehicle_reg_num" class="element text small" maxlength="25" value="" placeholder="KA03AB1234" data-trigger="keyup"  data-type="alphanum" data-rangelength="[10,10]" title="phone consist of 10 numeric characters."/>
			</li>
		</div> 

<!--
   <span class="btn btn" id="new_visitor-valid" onclick="javascript:$('#registration_form').parsley( 'validate' );"><i class="icon-ok"></i></span>
-->

<div>
	<li id="li_15" class="buttons">
			    <input type="hidden" name="form_id" value="826681" />
	</li>
</div>
              <div class="invalid-form-error-message"></div>
<!--
<input id="saveForm" class="button_text" type="submit" name="submit" value="Continue" />
-->
<input type="submit" class="btn btn-default validate"  name="submit" value="Continue" onclick="return ValidateForm();"/>
				<button type="reset" value="Reset">Reset</button>
</form>

<div id="footer"> 
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
Moving it anywhere else tends to break the form validation.
-->
<script type="text/javascript" src="js/parsley.min.js"></script>
</div>
	<img id="bottom" src="images/bottom.png" alt="">
</body>
</html>
