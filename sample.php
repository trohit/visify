<?php
// check if phone num was already provided
$vphone			= ((!empty($_REQUEST["phone_num"	]))?$_REQUEST["phone_num"]:NULL);
if (strlen($vphone)==0) {
	unset($vphone);
} 
//(isset($vphone))?echo "value=\"".$vphone."\"":; 
echo (isset($vphone))?('value="'.$vphone.'"'):"";
?>
