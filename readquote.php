<?php

function getquote($path, $vid)
{
	$totlines =  count(file($path));
	$lines = file($path);//file in to an array
	echo "vid:" . $vid . " mod " . $totlines . ":";
	$line_to_read = $vid % $totlines;
	return $lines[$line_to_read];
}
/*
print(getquote("oneliners.txt", rand(0,1000000)));
print(getquote("oneliners.txt",0));
print(getquote("oneliners.txt",1));
print(getquote("oneliners.txt",74));
print(getquote("oneliners.txt",75));
print(getquote("oneliners.txt",50));
*/
?>
