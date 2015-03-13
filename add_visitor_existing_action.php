<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Returning Visitor</title>
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

</head>
<body id="main_body" >
	<div id="form_container">
<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'meekrodb.2.2.class.php';

require_once("common.php");	
display_menu_common("Visitor Checkin");

/* check if the form was submitted. */
#if ($_SERVER['REQUEST_METHOD'] != 'POST') {
#	exit(1);
#}

echo "<table>";

// display details before inserting
    foreach ($_POST as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
	if($key == "picinfo_hidden") { 
		//print first 25 chars of val
		echo mb_substr($value, 0, 25);
		continue;  
	} else {
		echo $value;
	}
        echo "</td>";
        echo "</tr>";
    }
echo "</table>";

DB::$user = 'mysql';
DB::$password = '';
DB::$dbName = 'test';
DB::$host = 'localhost'; //defaults to localhost if omitted
#DB::$port = '12345'; // defaults to 3306 if omitted
#DB::$encoding = 'utf8'; // defaults to latin1 if omitted


/* extract all the data - PARSING. */
if(1) {
	echo "vid is ".$_POST['vid']."\n";
	$vid 			= $_POST['vid'];

	$start_time		= date("Y-m-d H:i:s");
	/* next insert record into the vrecord. */
	$vvehicle_reg_num	= ((!empty($_POST["vvehicle_reg_num"		]))?$_POST["vvehicle_reg_num"     ]:NULL);
	$vvehicle_details	= ((!empty($_POST["vvehicle_details"		]))?$_POST["vvehicle_details"     ]:NULL);
	$vitime 		= $start_time;
	$votime			= $start_time;
	$vphoto;
	//$vflatnum		= $_POST["flat_num"];
	$vflatnum		= ((!empty($_POST["flat_num"		]))?$_POST["flat_num"     ]:$_POST["block_other"]);
	$vblock			= $_POST["block"];
	$vpurpose		= ((!empty($_POST["purpose"		]))?$_POST["purpose"     ]:NULL);

	if (!empty($_POST['vphoto'])) {
		// insert a new account
		DB::insert('vrecord', array(
			'vid' 			=> $vid,
			'vvehicle_reg_num' 	=> $vvehicle_details,
			'vvehicle_details' 	=> $vvehicle_details,
			'vitime'		=> $vitime,
			'votime'		=> $votime,
			'vphoto'		=> $vphoto,
			'vflatnum'		=> $vflatnum,
			'vblock'		=> $vblock,
			'vpurpose'		=> $vpurpose,
		));
	} else {
		DB::insert('vrecord', array(
			'vid' 			=> $vid,
			'vvehicle_reg_num' 	=> $vvehicle_details,
			'vvehicle_details' 	=> $vvehicle_details,
			'vitime'		=> $vitime,
			'votime'		=> $votime,
			//'vphoto'		=> $vphoto,
			'vflatnum'		=> $vflatnum,
			'vblock'		=> $vblock,
			'vpurpose'		=> $vpurpose,
		));
	}
	echo "Phase2:Inserted details of new visit\n";
	$vrecordid = DB::insertId(); // which id did it choose?!? tell me!!
	echo "vrecordid is $vrecordid\n";
	echo "<br>\n";
	echo "Click <a href=\"returningvisitor.php\">here</a> to go back to the Main Page.\n";

} else if(0) {
	/* for debugging. */

	$vid;
	$vname 		= 'Nitin Joshi';
	$vgender 		= "Male";
	$vphone		= "1234567890";
	$vvehicle_reg_num	= "RJ1429M1234";
	$vaddress		= "Churu, Panchayat";
	$vphoto		= "abracadabra";
	$vcomments		= "No comments";
	$vctime;
	$vmtime;
	$vatime;

	// insert a new account
	DB::insert('visitor', array(
		'vname' 		=> $vname,
		'vgender' 		=> $vgender,
		'vphone' 		=> $vphone,
		//  'vvehicle_reg_num' 	=> $vvehicle_reg_num,
		//  'vvehicle_details' 	=> $vvehicle_details,
		'vaddress'		=> $vaddress, 
		'vcomments'		=> $vcomments,
		'vphoto'            	=> $vphoto,
		'vctime'			=> DB::sqleval("now()"),
		'vmtime'			=> DB::sqleval("now()"),
		'vatime'			=> DB::sqleval("now()")
	));
	echo "Yes Inserted it without extracting\n";
} else {

	 $vname 		= 'Sylvesr Stallone';
	 $vgender 		= "Male";
	 $vphone		= "3234567891";
	 $vaddress		= "Hollywood Panchayat";
// insert a new account
DB::insert('visitor', array(
  'vname' 		=> $vname,
  'vgender' 		=> $vgender,
  'vphone' 		=> $vphone,
//  'vvehicle_reg_num' 	=> 'KA01WE1234',
//  'vvehicle_details' 	=> 'Black Fiat',
  'vaddress'		=> $vaddress,
//  'vcomments'		=> 'Crazy Loony Character',
//  'vphoto'            	=> mysqleval,
  'vctime'			=> DB::sqleval("now()"),
  'vmtime'			=> DB::sqleval("now()"),
  'vatime'			=> DB::sqleval("now()")
));
	echo "Yes Inserted it without extracting\n";
}
/*
// insert a new account
DB::insert('accounts', array(
  'username' => 'Joe',
  'password' => 'hello'
));
 */
?>

    </div>
	</body>
</html>
