<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
#require_once("array-to-texttable.php");
require_once("parse_config.php");	
#require_once("common_lib.php");	
$host 		= $ini_array["host"];
$username 	= $ini_array["username"];
$password	= $ini_array["password"];
$db		= $ini_array["db"];
$port		= "";
$socket		= "";
DB::$user = $username;
DB::$password = $password;
DB::$dbName = $db;
DB::$host = $host;
$link = mysqli_connect($host, $username, $password, $db);

/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

#DB::$user = 'root';
#DB::$password = '';
#DB::$dbName = 'test';
#DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

$send_to = "talukdar.rohit@yahoo.com";

# what fields to display in the report
# that goes in the mail
$sql_fields_arr = array(
        #"vrecordid" => "Serial#",
        "vitime" => "In Time",
        "vname" => "Visitor Name",
        "vphone" => "Visitor Phone",
        #"visitor.vid" => "Visitor Id",
        "vvehicle_reg_num" => "Vehicle Number",
        #"vvehicle_details" => "Vehicle Details",
        #"votime" => "Out Time",
        "vblock" => "Block",
        "vflatnum" => "Flat#",
        "vpurpose" => "Purpose",
        "vtomeet" => "To Meet",
);
function get_block_info()
{
	$file = 'block.ini';
	$block_info = parse_ini_file($file);
	return $block_info;
}

/*
 * Inputs:
 * $from, $to, $shorten_fieldnames
 *
 * Output:
 * block_arr
 */
function get_visitor_count_by_block_arr_by_period($block_arr, $from, $to, $shorten_fieldnames)
{
	$sql_from = str_replace("-", "", $from);
	$sql_to = str_replace("-", "", $to);
	for($iter = $from;$iter != $to; $iter = get_date_from_base_date(1, $iter)) {
		#print("comparing $iter to $to\n");
		$res = get_visitor_count_by_block_arr_by_date($block_arr, $iter, $shorten_fieldnames);
		$result_arr[$iter]=$res;
		#print_r(get_visitor_count_by_block_arr_by_date($block_arr, $iter));
	}
	/*
	foreach($block_arr as $blockname=>$blockval) {
		$query = "SELECT COUNT(*)  FROM vrecord WHERE vblock='" . $blockval ."' AND vitime >= '$from' AND vitime <= '$to'";
		$result = DB::queryFirstRow($query);
		$result_arr[$search_date] = $result['COUNT(*)'];
	}
	 */
	#print_r($result_arr);
	return($result_arr);

}
function get_visitor_report_heading_for_period($from, $to, $ncols, $heading)
{
	$heading1 =  "VISITOR COUNT REPORT" . " - $heading";
	$heading2 =  "" . $from . " TO " . $to;

	$line1 = str_pad($heading1, $ncols,'_', STR_PAD_BOTH);
	$line2 = str_pad($heading2, $ncols,'_', STR_PAD_BOTH);
	return $line1 ."\n" .  $line2 . "\n";;
}


function print_weekly_top_n_visitors_for($date)
{
$query = "SELECT visitor.vid,vitime,vname,vphone,vctime,vblock,vflatnum,vtomeet,vvehicle_reg_num FROM visitor,vrecord WHERE vrecord.vid=visitor.vid ";
$results = DB::query($query);

$counter = DB::count();

}


?>
