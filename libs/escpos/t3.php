<?php
/**
 * This is a test script for the functions of the PHP ESC/POS print driver,
 * escpos.php.
 *
 * Most printers implement only a subset of the functionality of the driver, so
 * will not render this output correctly in all cases.
 *
 * @author Michael Billington <michael.billington@gmail.com>
 */
require_once(dirname(__FILE__) . "/escpos.php");
$printer = new escpos();

/* Initialize */
$printer -> initialize();

/* Text */
$printer -> text("Hello world\n");

/* Line feeds */
$printer -> text("ABC");
$printer -> feed(7);
$printer -> text("DEF");
$printer -> feed_reverse(3);
$printer -> text("GHI");
$printer -> feed();
$printer -> cut();

?>
