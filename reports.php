<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Reports</title>
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
  <script src="js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="css/jquery-ui.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<script language="javascript" type="text/javascript">

function doLoadStuff()
{
//	alert("doing stuff");	
//	$("#purpose").css('width', '5');
				//$("#block").trigger("change");
}
</script>

<body id="main_body" class="appnitro" onload="doLoadStuff()">

<script type="text/javascript" src="js/block.js"></script>
<script type="text/javascript">


function clickSubmit()
{
	$("#reports").submit();	
}

function setDates(period)
{
	var currentTime = new Date();
	var month = currentTime.getMonth()+1; //Beacause Jan starts from zero
	var year = currentTime.getFullYear();
	var day = currentTime.getDate();
	var maxdays=0;
	if(period==10) //Today
	{
		//var date = new Date('2012','02','01');
		//alert('the original date is '+date);
		//var newdate = new Date(date);
		////alert('new date is '+newdate);
		//newdate.setDate(newdate.getDate() - 1);
		//alert('prev date is '+newdate);

		//alert('today is'+currentTime);
		var prevTime = new Date(currentTime);
		prevTime.setDate(currentTime.getDate() - 1);
		//alert('yesterday was'+prevTime);
		var prev_month = prevTime.getMonth()+1; //Beacause Jan starts from zero
		var prev_year = prevTime.getFullYear();
		var prev_day = prevTime.getDate();

		//days and months from 1-9 need a leading '0' in front of them to prevent errors
		if(month < 10) { month = '0'+month; }
		if(day < 10) { day = '0'+day; }
		var current_to_date=day + "/" + month + "/" + year;

		if(prev_month < 10) { prev_month = '0'+prev_month; }
		if(prev_day < 10) { prev_day = '0'+prev_day; }
		var current_from_date=prev_day + "/" + prev_month + "/" + prev_year;

		//alert('from date is '+current_from_date);
		//alert('to date is '+current_to_date);


		document.getElementById('start_date').value=current_from_date;
		document.getElementById('end_date').value=current_to_date;
		clickSubmit();
		return;
	}
	if(period==20) //Last 2 days
	{
		var prevTime = new Date(currentTime);
		prevTime.setDate(currentTime.getDate() - 2);
		var prev_month = prevTime.getMonth()+1; //Because Jan starts from zero
		var prev_year = prevTime.getFullYear();
		var prev_day = prevTime.getDate();

		//days and months from 1-9 need a leading '0' in front of them to prevent errors
		if(month < 10) { month = '0'+month; }
		if(day < 10) { day = '0'+day; }
		var current_to_date=day + "/" + month + "/" + year;

		if(prev_month < 10) { prev_month = '0'+prev_month; }
		if(prev_day < 10) { prev_day = '0'+prev_day; }
		var current_from_date=prev_day + "/" + prev_month + "/" + prev_year;

		document.getElementById('start_date').value=current_from_date;
		document.getElementById('end_date').value=current_to_date;
		clickSubmit();
		return;
	}
	if(period==70) //Last 1 week
	{
		var prevTime = new Date(currentTime);
		prevTime.setDate(currentTime.getDate() - 7);
		var prev_month = prevTime.getMonth()+1; //Because Jan starts from zero
		var prev_year = prevTime.getFullYear();
		var prev_day = prevTime.getDate();

		//days and months from 1-9 need a leading '0' in front of them to prevent errors
		if(month < 10) { month = '0'+month; }
		if(day < 10) { day = '0'+day; }
		var current_to_date=day + "/" + month + "/" + year;

		if(prev_month < 10) { prev_month = '0'+prev_month; }
		if(prev_day < 10) { prev_day = '0'+prev_day; }
		var current_from_date=prev_day + "/" + prev_month + "/" + prev_year;

		document.getElementById('start_date').value=current_from_date;
		document.getElementById('end_date').value=current_to_date;
		clickSubmit();
		return;
	}
	if(period==0) //Current Month
	{
		var to_month=month;
		var to_year=year;
		maxdays=day;
	}
	else if(period==1) // Last Month
	{
		month=month-1;
		if(month==0) //if Present month January. Last month Means Deccember.
		{
			month=12;
			year--;
		}
		var to_year=year;
		var to_month=month;
	}
	else if(period==3 || period==6) // Last 3 months or 6 months
	{
		if(month==1)
		{
			to_month=12;
			to_year=year-1;
		}
		else
		{
			to_month=month-1;
			to_year=year;
		}
		for(i=0;i<period;i++)
		{
			if(month==1)
			{
				month=12;
				year--;
			}
			else
			{
				month--;
			}
		}
	}

	if(period==-1)
	{
		document.getElementById('start_date').value='';
		document.getElementById('end_date').value='';
	}
	else
	{
		if(month < 10)
		{
			month = '0'+month;
		}
		var current_from_date="01"+ "/" + month  + "/" + year;
		if(maxdays==0)
		{
			var maxdays=daysInMonth(to_month, to_year);
		}
		if(maxdays < 10)
		{
			maxdays = '0'+maxdays;
		}
		if(to_month < 10)
		{
			to_month = '0'+to_month;
		}
		var current_to_date=maxdays + "/" + to_month + "/" + to_year;
		document.getElementById('start_date').value=current_from_date;
		document.getElementById('end_date').value=current_to_date;
	}
	//alert("submitting");
	clickSubmit();
	//$("#reports").submit();	
	//$("#reports").trigger("submit");
	//$(this).closest('reports').trigger('submit');
}


function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}
function CompareDates()
{
     var str1 = document.getElementById("start_date").value;
     var str2 = document.getElementById("end_date").value;
     var currentdate = new Date();
     var dt1  = parseInt(str1.substring(0,2),10);
     var mon1 = parseInt(str1.substring(3,5),10); mon1=mon1-1;
     var yr1  = parseInt(str1.substring(6,10),10);
     var dt2  = parseInt(str2.substring(0,2),10);
     var mon2 = parseInt(str2.substring(3,5),10);mon2=mon2-1;
     var yr2  = parseInt(str2.substring(6,10),10);
     var date1 = new Date(yr1, mon1, dt1);
     var date2 = new Date(yr2, mon2, dt2);
     if(date2 < date1)
     {
         alert("To Date cannot be greater than From Date");
         return false;
     }
     else
     {
         if(currentdate<date1)
         {
             alert('The From Date should be less than or equal to current Date');
             return false;
         }
         if(currentdate<date2)
         {
             alert('The To Date should be less than or equal to current Date');
             return false;
         }
         return true;
     }
}
$(document).ready(function () {
	//alert('b');
	  //your code here
	$(function(){
/*
		$('#end_date').datepicker({
			//inline: true;
			dateFormat: "dd-mm-yy";
		});
		$('#start_date').datepicker({
			//inline: true;
			dateFormat: "dd-mm-yy";
		});
 */
    $( "#start_date" ).datepicker({ dateFormat: "dd-mm-yy" });
    $( "#end_date" ).datepicker({ dateFormat: "dd-mm-yy" });
    $( "#datepicker" ).datepicker({ dateFormat: "dd-mm-yy" });
	});
});
</script>


<body id="main_body" >
	
	<img id="top" src="images/top.png" alt="">
<?php
require_once("common.php");	
display_menu_common("Reports");
?>
<div align="center">
<img src="images/eco-logo.jpg" alt="Logo" height="90" width="100"/>
<img src="images/eco_building.jpg" alt="Logo" height="90" width="700">
<div id="txt_date"></div>
</div>
<!--
 <br> 
<input type="submit" name="submitButton" value="1d">
<input type="submit" name="submitButton" value="1w">
<input type="submit" name="submitButton" value="1m">
<input type="submit" name="submitButton" value="3m">
<input type="submit" name="submitButton" value="6m">
 <br>
--> 
<?php
//phpinfo();
//exit(1);
?>
	<div id="form_container">
<form id="reports" action="reports_action.php" method="get">		<table border="0">
<tr> 
	<td>
		<b><label>Block:</label></b> 
	<select name="block" id="block" onChange="block_changed(this.value);">
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_block_options("block.ini", $display_dummy_option);
				?>
	</select>
	</td>

	<td>
		<b><label id="label_block_other" style='display:none'>Specify where exactly:</label></b> 
		<input type="text" name="block_other" id="block_other" style='display:none'/>
	</td>

<td><b>
<label id="label_flat_num" style='display:block'>Flat Number:</label></b> 
<span id='flat_span'>
<select name="flat_num" style='display:block'>
<option value="0">All</option>
</select></span>
</td>
</tr>

		<tr>
		<td> <label>Visit Dates(dd/mm/yyyy)</label></td>
		<td><label></label></td>
		<td> <label>From:</label> 
<!--
		<input type="text" name="start_date" value="01/02/2014" id="start_date" size="10" class="element select medium" class="postField" onChange="return CompareDates();" /> </td>
-->
<input type="text" name="start_date" value="<?php echo date("d/m/Y", time()-(60*60*24)); ?>" id="start_date" size="12" class="element select medium" class="postField" onChange="return CompareDates();" /> </td>
		<td><label>To:</label> 
		<input type="text" name="end_date" value="<?php echo date("d/m/Y");?>" id="end_date" size="12"  class="element select medium" class="postField" onChange="return CompareDates();" /> </td>
		</tr>
		<tr>
		<td colspan=5>
		<a href='javascript:setDates(10);'>Today</a> &nbsp;
		<a href='javascript:setDates(20);'>Last 2 days</a> &nbsp;
		<a href='javascript:setDates(70);'>Last 1 wk</a> &nbsp;
		<a href='javascript:setDates(0);'>This Mnth</a> &nbsp;
		<a href='javascript:setDates(1);'>Last Mnth</a> &nbsp;
		<a href='javascript:setDates(3);'>Last 3 Mnths</a> &nbsp;
		<a href='javascript:setDates(6);'>Last 6 Mnths</a> &nbsp;
		<a href='javascript:setDates(-1);'>All</a></td>
		</tr>
		<tr>
			<td>
			<label class="description" for="element_7">Purpose *</label>
<!--
			<input id="purpose" name="purpose" class="element text medium" type="text" maxlength="128" value="" placeholder="(optional)"/> 
-->
			</td>

			<td>
			<select class="element select large" id="purpose" name="purpose"> 
				<!--
				<option value="" selected="selected"></option>
				-->
				<?php
				require_once("common_lib.php");
				$display_dummy_option = true;
				display_purpose_options("purpose.ini", $display_dummy_option);
				//display_purpose_options("purpose.ini");
				?>
			</select>
			</td>
			<td></td>
		</tr>

		<tr>
		<td colspan="2"><input type="submit" name="get_details" value="Get Details" id="get_details" class="primaryAction" />&nbsp;</td>
		</tr>
		</table>
<!--
		<input type='hidden' name='block_name' id='block_name' value=''/>
-->
</form>


	<div id="footer"> &copy; Visify 2014<?php if(date("Y") != "2014") echo "-".date("Y")?> </div>
	</div>
	</body>
</html>
