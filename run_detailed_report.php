<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';
require_once("common.php");	
#require_once("array-to-texttable.php");

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted

$send_to = "talukdar.rohit@yahoo.com";

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
 * gets dates for the last n days from current date
 * including current date
 *
 * so last 2 days will return 3 days including today
 */ 
function get_dates_n_days_arr($n)
{
	$cand_days = array();
	if ($n > 0) {
		for ($i = 0; $i <= $n; $i++) {
			$str = $i . " days";
			array_push($cand_days, date('Y-m-d',strtotime($str)));
		}
	} else {
		for ($i = 0; $i >= $n; $i--) {
			$str = $i . " days";
			array_push($cand_days, date('Y-m-d',strtotime($str)));
		}
	}
	return $cand_days;
}

/* 
 * removes vowels
 * removes repeated strings
 * truncates at n chars
 */ 
function shorten_str($str, $n=8)
{
	#$vowels = array("a", "e", "i", "o", "u");
	$vowels = array("a", "e", "i", "o", "u","n","t");
	$str = str_replace($vowels, "", $str);

	// http://stackoverflow.com/questions/13290649/how-remove-duplicate-letters-using-php
	$str = preg_replace('{(.)\1+}','$1',$str);

	//remove '-'
	$str = str_replace('-', "", $str);

	// truncates at n chars
	$str = substr($str, 0, $n);
	#echo "$str,";
	return $str;
}
/*
 * gets dates for the last n days from current date
 * excluding current date
 *
 * so last 2 days will return 2 days excluding today
 */ 
function get_dates_n_days_arr_exclude_today($n)
{
	$cand_days = array();
	if ($n > 0) {
		for ($i = 1; $i <= $n; $i++) {
			$str = $i . " days";
			array_push($cand_days, date('Y-m-d',strtotime($str)));
		}
	} else {
		for ($i = 1; $i >= $n; $i--) {
			$str = $i . " days";
			array_push($cand_days, date('Y-m-d',strtotime($str)));
		}
	}
	return $cand_days;
}
/*
 * gets dates for the last week from current date
 * including current date
 */ 
function get_dates_last_week_arr()
{
	return get_dates_n_days_arr(-7);
}
/*
 * Reporting does:
 * 1. # daily count of visitors
 * 2. # daily detailed report of visitors on a day
 */ 
function get_current_date()
{
	$year =  date('Y');
	$month =  date('m');
	$day =  date('d');

	# comment this line in production
	#$day = $day - 5;

	#$day = $day - 5;
	return $year."-".$month."-".$day;
}
function get_visitor_count_by_date($search_date)
{
	$query = "SELECT COUNT(*)  FROM vrecord,visitor WHERE visitor.vid=vrecord.vid AND vitime LIKE '$search_date%'";
	$result = DB::queryFirstRow($query);
	//print($result['COUNT(*)']);
	print($query);
	#print_r($result);
	return($result['COUNT(*)']);

}
function disp_visitor_count_by_date_arr($search_date_arr)
{

	foreach ($search_date_arr as $key => $value) {
		echo "$key " . get_day_of_week($key) . ": "; 
	#	$value = 200;
		for ($i = 0; $i < $value/4; $i++) {
			echo "*";
		}
		echo "$value";
		echo "\n";
	}


}
function get_visitor_count_by_date_arr($search_date_arr)
{
	foreach($search_date_arr as $search_date) {
		$query = "SELECT COUNT(*)  FROM vrecord,visitor WHERE visitor.vid=vrecord.vid AND vitime LIKE '$search_date%'";
		$result = DB::queryFirstRow($query);
		//print($result['COUNT(*)']);
		//print($query);
		//print_r($result);
		$result_arr[$search_date] = $result['COUNT(*)'];
	}
	#print_r($result_arr);
	return($result_arr);

}

function get_visitor_count_by_block_arr_by_date($block_arr, $cand_date, $shorten_fieldnames = false)
{
	foreach($block_arr as $blockname=>$blockval) {
		$query = "SELECT COUNT(*)  FROM vrecord WHERE vblock='" . $blockval ."' AND vitime  LIKE '$cand_date%'";
		$result = DB::queryFirstRow($query);
		if ($shorten_fieldnames) {
			$fieldname = shorten_str($blockname,2);
		} else {
			$fieldname = $blockname;
		}
		/* 
		 * if too many digits in value, display XX instead.
		 */
		if (strlen($result['COUNT(*)']) > strlen($fieldname)) {
			$result_arr[$fieldname] = "XX";
		} else {
			$result_arr[$fieldname] = $result['COUNT(*)'];
		}
	}
	#$superdate_arr["$cand_date"] = $result_arr;
	#print_r($superdate_arr);
	#print_r($result_arr);
	#return($superdate_arr);
	return($result_arr);
}

function get_visitor_count_by_block_arr_by_period($block_arr, $from, $to, $shorten_fieldnames=false)
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

function send_mail($subject, $message, $recipient)
{
	$res = mail($recipient, $subject, $message);
	return $res;
}

function get_register_log($search_date)
{
	global $sql_fields_arr;
	global $send_to;

	$sql_result_file = '/tmp/.visitor_'.$search_date.'.daily';
	#unlink($sql_result_file);


	#$query = "SELECT COUNT(*) FROM vrecord WHERE vitime LIKE '". $search_date . "%' INTO OUTFILE " . "'$sql_result_file'";
	#$result = DB::query($query);

	#$query = "SELECT visitor.vname,visitor.vphone, vrecord.*  INTO OUTFILE '$sql_result_file' FROM vrecord,visitor WHERE visitor.vid=vrecord.vid AND vitime LIKE '$search_date%'";
	$query = "SELECT ";
	$colnames = array_values($sql_fields_arr);
	for ($i = 0; $i < count($colnames); $i++) {
		if ($i) {
			$query .= ',';
		}
		$query .= "'" . $colnames[$i] . "'";
	}
	$query .= " UNION ALL ";

	$actual_fields_arr = array_keys($sql_fields_arr);
	$query .= "SELECT ";
	for ($i = 0; $i < count($actual_fields_arr); $i++) {
		if ($i) {
			$query .= ',';
		}
		$query .= $actual_fields_arr[$i];
	}
	$query .= " INTO OUTFILE '$sql_result_file' FROM vrecord,visitor WHERE visitor.vid=vrecord.vid AND vitime LIKE '$search_date%'";

	# for optional csv output
	# $query .= " FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
	# SELECT 'Visitor Name','Visitor Phone','VisitorId' UNION ALL SELECT vname, vphone, visitor.vid FROM vrecord,visitor WHERE visitor.vid=vrecord.vid AND vitime LIKE '2015-03-15%' INTO OUTFILE '/tmp/orders.csv' FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n';
	print($query);
	$result = DB::query($query);

	#exit(1);

	#print("Sending mail...");
	#send_mail("Security Report", file_get_contents($sql_result_file), $send_to);
	#print("Sent mail...");
}


function arr2textTable($table, $brevity=false) {
    function clean($var) { 
        $search=array("`((?:https?|ftp)://\S+[[:alnum:]]/?)`si","`((?<!//)(www\.\S+[[:alnum:]]/?))`si");
        $replace=array("<a href=\"$1\" rel=\"nofollow\">$1</a>","<a href=\"http://$1\" rel=\"nofollow\">$1</a>");
        $var = preg_replace($search, $replace, $var);
        return $var;
    }
    foreach ($table AS $rowname => $row) {
	    if ($brevity) {
		    $first_col_name = "Date";    
	    } else {
		    $first_col_name = "Date-of-Report";    
	    }
	$first_cell_length = strlen($first_col_name);    
	#print($rowname);    
        $cell_count = 0;
        foreach ($row AS $key=>$cell) {
            $cell_length = strlen($cell);
            $key_length = strlen($key);
            $cell_length = $key_length > $cell_length ? $key_length : $cell_length;
            $cell_count++;
            if (!isset($cell_lengths[$key]) || $cell_length > $cell_lengths[$key])
                $cell_lengths[$key] = $cell_length;
        }   
    }
    $bar = "+";
    $header = "|";
    if ($brevity) {
	    $header .= "".str_pad($first_col_name, $first_cell_length, " ", STR_PAD_RIGHT) . "|";
	    $bar .= str_pad("", $first_cell_length, "-")."+";
    } else {
	    $header .= " ".str_pad($first_col_name, $first_cell_length, " ", STR_PAD_RIGHT) . " |";
	    $bar .= str_pad("", $first_cell_length+2, "-")."+";
    }

    foreach ($cell_lengths AS $fieldname => $length) {
	if ($brevity)
		$bar .= str_pad("", $length, "-")."+";
	else	
		$bar .= str_pad("", $length+2, "-")."+";
        $name = $fieldname;
        if (strlen($name) > $length) {
            $name = substr($name, 0, $length-1);
        }
	if ($brevity)
		$header .= "".str_pad($name, $length, " ", STR_PAD_RIGHT) . "|";
	else	
		$header .= " ".str_pad($name, $length, " ", STR_PAD_RIGHT) . " |";
    }
    $output = "${bar}\n${header}\n${bar}\n";


    foreach ($table AS $rowname => $row) {
	$output .= "|";

	if ($brevity) {
		$rowname = substr($rowname, 8). "" . substr(get_day_of_week($rowname),0,2);
		#echo $rowname;
		$output .= "".str_pad($rowname, $first_cell_length, " ", STR_PAD_RIGHT) . "|";
	} else {
		// first two chars of year are not needed in this century viz. 2015-...
		// append day of week
		$rowname = substr($rowname, 2). " " . get_day_of_week($rowname);
		$output .= " ".str_pad($rowname, $first_cell_length, " ", STR_PAD_RIGHT) . " |";
	}
        foreach ($row AS $key=>$cell) {
		if ($brevity)
			$output .= "".str_pad($cell, $cell_lengths[$key], " ", STR_PAD_RIGHT) . "|";
		else
			$output .= " ".str_pad($cell, $cell_lengths[$key], " ", STR_PAD_RIGHT) . " |";
        }
        $output .= "\n";
    }
    $output .= $bar."\n";
    return clean($output);
}
function disp_visitor_count_blockwise($block_arr, $days=-30)
{
	echo "REPORT " . get_from_today($days) ." " .  get_day_of_week(get_from_today($days)) . " TO " 
		. get_from_today(0). " " . get_day_of_week(get_from_today(0)) . "\n";
	$r = (get_visitor_count_by_block_arr_by_period($block_arr, get_from_today($days), get_from_today(0), true));
	echo arr2textTable($r, true);
}
function arr2textTable2($a, $b = array(), $c = 0) {
    $d = array();
    $e = "+";
    $f = "|";
    $g = 0;
    foreach ($a as $h)
        foreach ($h AS $i => $j) {
            $j = substr(str_replace(array("\n","\r","\t","  "), " ", $j), 0, 48);
            $k = strlen($j);
            $l = strlen($i);
            $k = $l > $k ? $l : $k;
            if (!isset($d[$i]) || $k > $d[$i])
                $d[$i] = $k;
        }
    foreach ($d as $m => $h) {
        $e .= str_pad("", $h + 2, "-") . "+";
            if (strlen($m) > $h)
                $m = substr($m, 0, $h - 1);
            $f .= " " . str_pad($m, $h, " ", isset($b[$g]) ? $b[$g] : $c) . " |";
            $g++;
    }
    $n = "{$e}\n{$f}\n{$e}\n";
    foreach ($a as $h) {
        $n .= "|";
        $g = 0;
        foreach ($h as $i => $o) {
            $n .= " " . str_pad($o, $d[$i], " ", isset($b[$g]) ? $b[$g] : $c) . " |";
            $g++;
        }
        $n .= "\n";
    }
    $p = array(
        "`((?:https?|ftp)://\S+[[:alnum:]]/?)`si",
        "`((?<!//)(www\.\S+[[:alnum:]]/?))`si"
    );
    $q = array(
        "<a href=\"$1\" rel=\"nofollow\">$1</a>",
        "<a href=\"http://$1\" rel=\"nofollow\">$1</a>"
    );
    return preg_replace($p, $q, "{$n}{$e}\n");
}
$is_debug = 0;
#print_r(get_dates_last_week_arr());
#print_r(get_dates_n_days_arr(7));
#print_r(get_dates_n_days_arr_exclude_today(7));
$search_date_arr = (get_visitor_count_by_date_arr(get_dates_n_days_arr(-30)));


#disp_visitor_count_by_date_arr($search_date_arr);
$file = 'block.ini';
$block_arr = parse_ini_file($file);
#print_r($results);
#print_r(get_visitor_count_by_block_arr_by_date($results, get_from_today(-2)));
#print_r(get_visitor_count_by_block_arr_by_period($results, get_from_today(-3), get_from_today(0)));
#$r = (get_visitor_count_by_block_arr_by_period($results, get_from_today(-20), get_from_today(0), true));

#$r = (get_visitor_count_by_block_arr_by_period($block_arr, get_from_today(-2), get_from_today(0), true));
#echo arr2textTable($r, true);
disp_visitor_count_blockwise($block_arr);

#echo arr2textTable($r, false);
#echo arr2textTable2($r);
#$renderer = new ArrayToTextTable($r);
#$renderer->showHeaders(true);
#$renderer->render();



exit(1);
print(get_current_date());
print("\n");
print(get_yesterday());
print("\n");
#exit(1);
print("\ncount:");
print(get_visitor_count_by_date(get_current_date()));
#get_register_log(get_current_date());

exit(1);

?>
