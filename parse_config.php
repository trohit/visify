<?php
// Parse without sections
function parse_config()
{
	$ini_array = parse_ini_file("credentials.ini");
	return $ini_array;
}

