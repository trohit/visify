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

function get_image_by_vehicle_type($vehicle_type, $height=24)
{
	if ($vehicle_type == "2w") {
		$height += $height*0.4;
		return '<img id="bikeId" src="images/bike_checked.png" alt="bike" height="'.$height.'" title="Bike"/>';
	} else if ($vehicle_type == "4w") {
		return ' <img id="carId" src="images/car_checked.png" alt="car" height="'.$height.'" title="Car"/>';
	}
}

function prepare_vname($vname)
{
	$new_vname = ucwords(str_replace('_', ' ', $vname));
	return $new_vname;
}
function prepare_vtomeet($vtomeet)
{
	return prepare_vname($vtomeet);
}

function get_day_of_week($tempdate)
{
	//return date('l', strtotime( $tempdate));
	return date('D', strtotime( $tempdate));
}
function get_from_today($n)
{
	return date('Y-m-d',strtotime($n." days"));
}
function get_date_from_base_date($n, $base_date)
{
	return date('Y-m-d',strtotime($n." days", strtotime($base_date)));
}
function get_yesterday()
{
	return date('Y-m-d',strtotime("-1 days"));
}
/*
 * converts date like 2015-05-01 to 1st May 2015 Wed
 * no sanity checks, so make sure input is valid
 * formats ref: http://php.net/manual/en/function.date.php
 */
function convert_date_boring_to_interesting($date)
{
	$newDate = date("D, M j, Y", strtotime($date));
	return $newDate;
}
function is_mobile() 
{
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
		echo "Mobile detected";
		return true;
	} else {
		echo "Desktop detected";
		return false;
	}
}
?>
