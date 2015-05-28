<?php

//$tempDate = '2012-07-10' returns Tue
function get_weekday_by_date($tempDate)
{
	return date('D', strtotime($tempDate));
}

function display_menu_common($menu_option)
{
	$menu_opts = array (
//		"Dashboard"		=>"dashboard.php",
		"Visitor Checkin"	=>"returningvisitor.php",
		"Active Visitors"	=>"active_visitors.php",
		"Find Records"		=>"find.php",
		"Reports"		=>"reports.php",
//		"Settings"		=>"settings.php"
	);

	$hdr = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
EOD;
	$ftr = <<<EOD
		</ul>
	</div>
EOD;
	$logo = <<<EOD
<li>
<img src="images/eco-logo.jpg" alt="Logo" height="48" width="48"/>
</li>
EOD;


	//the actual stuff begins
	echo $hdr;
	//echo $logo;
	echo "\n";
	foreach($menu_opts as $x=>$x_value) {
		$flag = NULL;
		if ($menu_option == $x) {
			$flag = " id=\"active\"";
		}	
		echo "\t\t<li".$flag."><a href=\"".$x_value."\">".$x."</a></li>\n";
	}
	echo $ftr;


}
/*
function display_menu()
{
	$menu = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
		<li><a href="returningvisitor.php">Visitor Checkin</a></li>
		<li><a href="active_visitors.php">Active Visitors</a></li>
		<li><a href="find.php">Find Records </a></li>
		<li><a href="reports.php">Reports</a></li>
		</ul>
	</div>
EOD;
	echo $menu;
}

function display_menu_returning_visitor()
{
	$menu = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
		<li id="active"><a href="returningvisitor.php" id="current">Visitor Checkin</a></li>
		<li><a href="active_visitors.php">Active Visitors</a></li>
		<li><a href="find.php">Find Records </a></li>
		<li><a href="reports.php">Reports</a></li>
		</ul>
	</div>
EOD;
	echo $menu;
}
function display_menu_active_visitors()
{
	$menu = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
		<li><a href="returningvisitor.php">Visitor Checkin</a></li>
		<li id="active"><a href="active_visitors.php">Active Visitors</a></li>
		<li><a href="find.php" id="current">Find Records </a></li>
		<li><a href="reports.php">Reports</a></li>
		</ul>
	</div>
EOD;
	echo $menu;
}

function display_menu_find_records()
{
	$menu = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
		<li><a href="returningvisitor.php">Visitor Checkin</a></li>
		<li><a href="active_visitors.php">Active Visitors</a></li>
		<li id="active"><a href="find.php" id="current">Find Records </a></li>
		<li><a href="reports.php">Reports</a></li>
		</ul>
	</div>
EOD;
	echo $menu;
}
function display_menu_reports()
{
	$menu = <<<EOD
	<div id="navcontainer">
		<ul id="navlist">
		<li><a href="returningvisitor.php">Visitor Checkin</a></li>
		<li><a href="active_visitors.php">Active Visitors</a></li>
		<li><a href="find.php" id="current">Find Records </a></li>
		<li id="active"><a href="reports.php">Reports</a></li>
		</ul>
	</div>
EOD;
	echo $menu;
}
 */
