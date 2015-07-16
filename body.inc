<body id="main_body" onload="doLoadStuff()">
<noscript>
<style> #loading {display:none} </style>
       <p align="center"><img src="images/javascript_disabled.png" /></p>
<form action="?ui=html&amp;param=c" method="post"><input type="hidden" name="at" value="someval"><font face=arial>JavaScript must be enabled in order for you to use this site in standard view. However, it seems JavaScript is either disabled or not supported by your browser. To use standard view, enable JavaScript by changing your browser options, then <a href="">try again</a>.</font></form>
</noscript>

<script type="text/javascript" src="js/view.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/block_insert.js"></script>
<script type="text/javascript" src="js/slimbox2.js"></script>


<script type="text/javascript">

function doLoadStuff()
{
	//alert("doing stuff");	
	getDate();
}

function getDate()
{
	var today = new Date();
	//var dd = today.getDate();
	//var mm = today.getMonth()+1; //January is 0!
	//var yyyy = today.getFullYear();
	//if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+mm+dd;
	//document.getElementById("todayDate").value = today;

	document.getElementById("todayDate").value = today;
}
</script>
<div id="loading">

<?php	
require_once("common.php");	
display_menu_common("Visitor Checkin");
?>
<input type="hidden" name="startdate" id="todayDate"/>
<div align="center">
<img src="images/eco-logo.jpg" alt="Logo" border="0" height="45" width="50"/><img src="images/eco_building.jpg" alt="Logo"  border="0" height="45" width="750">
</div>
<div id="form_container" data-parsley-validate>
<!--
		<form id="new_visitor" name="new_visitor" class="appnitro"  method="post" action="visitor_add.php" data-validate="parsley">

		<div class="form_description">
			<h2><img src="images/entry-icon.png" alt="Entry"> Visitor Check-in</h2>
-->
