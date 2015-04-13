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

function print_report_daily($file, $to_cut_paper, $feed_lines_before_cut = 3, $header = NULL, $footer = NULL)
{
	global $pass_footer;

	$content = file_get_contents($file)


	//return;

	$printer = new escpos();
	if (!$printer->is_valid()) {
		print("Unable to initialize printer\n");
		return;
	}


	/* Initialize */
	$printer -> initialize();
	$printer -> set_emphasis(false);
	//$printer -> select_print_mode(); // Reset
	$printer -> feed();

	$printer -> set_justification(escpos::JUSTIFY_LEFT);
	$printer -> text($content);
	$printer -> feed();

	$printer -> set_justification(escpos::JUSTIFY_CENTER);
	$printer -> text($pass_footer);
	$printer -> feed(); /* Line feeds */
	$printer -> set_justification(escpos::JUSTIFY_LEFT);
	//$printer -> text(getquote("oneliners.txt", $vrecordid));
	$printer -> feed($feed_lines_before_cut);
	$printer -> cut();

}
//dummy();

?>
