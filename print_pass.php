<?php
require_once(dirname(__FILE__) . "/libs/escpos/escpos.php");
require_once(dirname(__FILE__) . "/readquote.php");
/* Text */
/*
$mystring = <<<EOT
    This is some PHP text.
    It is completely free
    I can use "double quotes"
    and 'single quotes',
    plus $variables too, which will
    be properly converted to their values,
    you can even type EOT, as long as it
    is not alone on a line, like this:
EOT;
 */
function print_barcode($printer, $vrecordid)
{
	$printer -> set_barcode_height(80);
	$printer -> barcode($vrecordid, escpos::BARCODE_CODE39);
	$printer -> feed();
}

function print_pass(
		$vitime,
		$vrecordid,
		$vname,
		$vphone,
		//$vid,
		$vvehicle_reg_num,
		//$vvehicle_type,
		//$vphoto,
		$vnum_visitors,
		$vtomeet,
		$vflatnum,
		$vblock,
		$vpurpose,
		$vcomments,
		//$vcreatedby,
		$vduration_fillup)
{
	global $pass_header;
	global $pass_body;
	global $pass_footer;

	/* sanitize data. */	
	if ($vvehicle_reg_num == "0") {
		$vvehicle_reg_num = "N.A.";
	} 

	$pass_header = <<<EOT
ALPINE ECO Security Gate Visitor Pass
SECURITY HELPLINE:9972-572-098
FEEDBACK: visitoratalpineeco@gmail.com
EOT;

	$pass_footer = <<<EOT
PLEASE RETURN SIGNED SLIP TO SECURITY
EOT;
//Residents are responsible for the conduct of their visitors
//SIGNED SLIP TO BE RETURNED TO SECURITY AT EXIT

	$pass_body = <<<EOT
Intime          : $vitime 
Serial Number   : $vrecordid
Visitor Name    : $vname
Mobile no       : $vphone
Vehicle No      : $vvehicle_reg_num 
No of Visitors  : $vnum_visitors
Name of Resident: $vtomeet
Block           : $vblock 
Flat Num        : $vflatnum
Purpose         : $vpurpose
Comment         : $vcomments

Outtime         :____________________________

Created By      :____________________________

Signature of Resident: ______________________

EOT;

	//return;

	$printer = new escpos();
	if (!$printer->is_valid()) {
		print("Unable to initialize printer\n");
		return;
	}


	/* Initialize */
	$printer -> initialize();

	print_barcode($printer, $vrecordid);

/*
	$mode |= (
		escpos::MODE_EMPHASIZED |
		escpos::MODE_DOUBLE_HEIGHT |
		escpos::MODE_DOUBLE_WIDTH
		);
	$printer -> select_print_mode($mode);
*/
	$printer -> set_emphasis(true);
	$printer -> set_justification(escpos::JUSTIFY_CENTER);
	$printer -> text($pass_header);
	$printer -> set_emphasis(false);
	//$printer -> select_print_mode(); // Reset
	$printer -> feed();

	$printer -> set_justification(escpos::JUSTIFY_LEFT);
	$printer -> text($pass_body);
	$printer -> feed();

	$printer -> set_justification(escpos::JUSTIFY_CENTER);
	$printer -> text($pass_footer);
	$printer -> feed(); /* Line feeds */
	$printer -> set_justification(escpos::JUSTIFY_LEFT);
	//$printer -> text(getquote("oneliners.txt", $vrecordid));
	//$printer -> feed();
	$printer -> cut();

}
//dummy();

?>
