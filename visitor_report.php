<?php
require_once 'visitor_report_lib.php';

$is_debug = 0;
#print_r(get_dates_last_week_arr());
#print_r(get_dates_n_days_arr(7));
#print_r(get_dates_n_days_arr_exclude_today(7));
#
#


function print_monthly_report($date_upto, $ncols)
{
	$from = date_sub(date_create($date_upto), date_interval_create_from_date_string("1 month"));
	$to = date_create($date_upto);	

	$from_date = date_format($from,"Y-m-d");
	$to_date = date_format($to,"Y-m-d");

	$block_info = get_block_info();

	$r = (get_visitor_count_by_block_arr_by_period($block_info, $from_date, $to_date, true));
	$report_heading = get_visitor_report_heading_for_period($from_date, $to_date, $ncols);
	print($report_heading);
	echo arr2textTable($r, true);
}
function print_weekly_report($date_upto, $ncols)
{
	$from = date_sub(date_create($date_upto), date_interval_create_from_date_string("1 week"));
	$to = date_create($date_upto);	

	$from_date = date_format($from,"Y-m-d");
	$to_date = date_format($to,"Y-m-d");

	$block_info = get_block_info();

	$r = (get_visitor_count_by_block_arr_by_period($block_info, $from_date, $to_date, true));
	$report_heading = get_visitor_report_heading_for_period($from_date, $to_date, $ncols);
	print($report_heading);
	echo arr2textTable($r, true);
}


$search_date_arr = (get_visitor_count_by_date_arr(get_dates_n_days_arr(-30)));


#disp_visitor_count_by_date_arr($search_date_arr);
#print_r($results);
#print_r(get_visitor_count_by_block_arr_by_date($results, get_from_today(-2)));
#print_r(get_visitor_count_by_block_arr_by_period($results, get_from_today(-3), get_from_today(0)));
#print_monthly_report(get_from_today(0), 45);
print_weekly_report(get_from_today(0), 45);
return;
$r = (get_visitor_count_by_block_arr_by_period($results, get_from_today(-30), get_from_today(0), true));
$report_heading = get_visitor_report_heading_for_period(get_from_today(-30), get_from_today(0));
/*
foreach ($r AS $k=>$row) {
	$cell_count = 0;
	print($k);
	print_r($row);
	foreach ($row AS $key=>$cell) {
	}
}
 */
print $report_heading;
echo arr2textTable($r, true);
#echo arr2textTable($r, false);
#echo arr2textTable2($r);
#$renderer = new ArrayToTextTable($r);
#$renderer->showHeaders(true);
#$renderer->render();



#exit(1);
#print(get_current_date());
#print("\n");
#print(get_yesterday());
#print("\n");
#exit(1);
#print("\ncount:");
#print(get_visitor_count_by_date(get_current_date()));
#get_register_log(get_current_date());

exit(1);

?>
