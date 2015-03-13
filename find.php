<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Find Records</title>
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
	
<?php
require_once("common.php");	
display_menu_common("Find Records");
?>
	<div id="form_container">
	<div align="center">
	<img src="images/eco-logo.jpg" alt="Logo" height="64" width="64"/>
	<img src="images/eco_building.jpg" alt="Logo" height="90" width="800">
	<div id="txt"></div>
	</div>
	
		<!--
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="new_visitor.php" data-parsley-validate>
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="returningvisitor_action.php">
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="find_action.php">
			-->	
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="get" action="find_action.php">
					<div class="form_description">
			<h2><img src ="images/find_icon.png" alt="Find Icon"</img> Find Records</h2>
			<p>Please enter details carefully. Records are tracked by their name and contact number</p>

		</div>						
			<ul >
			

    <div>



		<div>
		<li id="li_2" >
			<label class="description" for="element_1">Name</label>
			<input id="name" name="name" class="element text medium" type="text" maxlength="32" value="" placeholder="FirstName LastName""text-transform:capitalize" />
		</li>
		</div> 

<!--
		<div>
		<li id="li_4" >
			<label class="description" for="element_44">Age</label>
			<input id="age" name="age" class="element text small" type="text" maxlength="3" value=""/> 
		</li>
		</div> 
-->


		<div>
			<li id="li_6" >
			<label class="description" for="element_10"> <img src="images/contact.jpg"></img> Phone Number</label>
			<input id="phone_num" name="phone_num" class="element text small" type="text" value="" placeholder="9886110220" data-parsley-type="digits"/> 
			</li>
		</div> 

		<div>
			<li id="li_7" >
			<label class="description" for="element_7"> <img src="images/car.jpg"></img> Vehicle Registration Number </label>
			<input id="vehicle_reg_num" name="vehicle_reg_num" class="element text small" type="text" maxlength="255" value="" placeholder="KA01ME1234"/> 
			</li>
		</div> 

		<div>
			<li id="li_8" >
			<label class="description" for="element_8">Block</label>
			<select name="block" id="block" class="element select small" onChange="block_changed(this.value);">
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
			<label class="description" for="element_8">Flat Number:</label>
			<span id='flat_span'>
				<select name="flat_num" style='display:block'>
					<option value="0">All</option>
				</select>
			</span>
<!--
			<label class="description" for="element_5">Flat Number</label>
			<input id="flat_num" name="flat_num" class="element text small" type="text" maxlength="8" value="" placeholder="GF-01"/> 
-->
			</li>
		</div> 


		<div>
			<li id="li_15" class="buttons">
				<input type="hidden" name="form_id" value="826681" />
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
				<button type="reset" value="Reset">Reset</button>
			</li>
		</div>

			</ul>
		</form>	
	<div id="footer"> &copy; Visify 2014<?php if(date("Y") != "2014") echo "-".date("Y")?> </div>
	</div>
	<img id="bottom" src="images/bottom.png" alt="">
	</body>
</html>
