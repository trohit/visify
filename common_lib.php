<?php
# statdard header
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
require_once("parse_config.php");	

$ini_array = parse_config();
DB::$user 	= $ini_array["username"];
DB::$password 	= $ini_array["password"];
DB::$dbName 	= $ini_array["db"];
DB::$host 	= $ini_array["host"];
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted




/*
 * <option value="Aronia-A" selected="selected">Aronia-A</option>
 */

function display_block_options($file, $is_dummy_displayed = 0)
{
	$results = parse_ini_file($file);
	$is_first = true;

	//echo "<table>";
	if ($is_dummy_displayed) {
			$is_first = false;
			//echo "<option value=\"\" selected=\"selected\">None</option>";
			echo "<option selected disabled hidden value=''></option>";
	}

	foreach ($results as $row => $val) {
		echo "\n";
		if ($is_first) {
			echo "<option value=\"".$val."\" selected=\"selected\">".$row."</option>";
			$is_first = false;
		} else {
			echo "<option value=\"".$val."\">".$row."</option>";
		}
	}
	//echo "</table>";
}


function display_purpose_options($file, $is_dummy_displayed = 0)
{

	$results = parse_ini_file($file);
	$is_first = true;

	if ($is_dummy_displayed) {
			$is_first = false;
			echo "<option selected disabled hidden value=''></option>";
	}

	foreach ($results as $row => $val) {
		echo "\n";
		if ($is_first) {
			echo "<option value=\"".$val."\" selected=\"selected\">".$row."</option>";
			$is_first = false;
		} else {
			echo "<option value=\"".$val."\">".$row."</option>";
		}
	}
}

function is_exists_pic($vid)
{
	// get length of pic
	$len = DB::queryFirstField("SELECT length(vphoto) FROM visitor WHERE vid=%s", $vid);
	//echo "len is: " . $len . "\n";
	if ($len > 1000) {
		return 1;
	}
	return 0;
}

/**
 * Convert number of seconds into hours, minutes and seconds
 * and return an array containing those values
 *
 * @param integer $seconds Number of seconds to parse
 * @return array
 */
function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
 
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
    return $obj;
}

function getlastvrecord()
{
	$ini_array = parse_config();
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

	$result=mysqli_query($link, "SELECT MAX(vrecordid) from vrecord");
	/* associative array */
	$row = $result->fetch_array(MYSQLI_NUM);
	$vrecordid = $row[0];;
	print($row[0]);
	$result=mysqli_query($link, "SELECT * from vrecord where vrecordid='$vrecordid'");
	/* fetch associative array */
	$vrecord = $result->fetch_assoc();
	//while ($vrecord = $result->fetch_assoc()) {
	//}

	/* close connection */
	mysqli_close($link);
	return $vrecord;
}

/*
 * functions to sanitize inputs
 * source: http://www.catswhocode.com/blog/10-awesome-php-functions-and-snippets
 */

function cleanInput($input) 
{

	$search = array(
		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
		'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
	);

	$output = preg_replace($search, '', $input);
	return $output;
}

function sanitize($input, $link=NULL) 
{
	if (is_array($input)) {
		foreach($input as $var=>$val) {
			$output[$var] = sanitize($val);
		}
	} else {
		if (get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		$input  = cleanInput($input);
		if ($link) {
			$output = @mysqli_real_escape_string($link, $input);
		} else {
			$output = @mysql_real_escape_string($input);
		}
	}
	return $output;
}

function show_image_by_vehicle_type($vehicle_type)
{
	if ($vehicle_type == "2w") {
		echo '<img id="bikeId" src="images/bike_checked.png" alt="bike" height="32" title="Bike"/>';
	} else if ($vehicle_type == "4w") {
		echo ' <img id="carId" src="images/car_checked.png" alt="car" height="24" title="Car"/>';
	}
}


?>
