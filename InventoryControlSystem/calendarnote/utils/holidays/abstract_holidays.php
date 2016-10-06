<?php
abstract class AbstructHolidays extends Object {
	// @return  array( day=>'name', .....)
	// @example array( 10=>'hoge holiday1', 15=>'hoge holiday2')
	abstract function getHolidayNames($year, $month);
}
?>