<?php
class CalendarComponent extends Object {
	function startup(&$controller) {
		$this->Controller = & $controller;
	}
	function scopeToTimes(&$scope, $year, $month, $day) {
		$time = mktime(0,0,0,$month,$day,$year);
		switch($scope) {
		case 'month' :	// 指定月の初週の日曜日から、最終日の土曜日まで
			$firstday = mktime(0,0,0,$month,1,$year);
			$from_time = mktime(0,0,0,$month,1 - date('w', $firstday), $year);
			$days = ceil((date('t', $time) + date('w', $firstday)) / 7) * 7;
			break;
		case 'day' :	// 指定された日
			$from_time = $time;
			$days = 1;
			break;
		case 'week' :	// 指定された日から１週間
		default :
			$from_time = $time;
			$days = 7;
			$scope = 'week';
			break;
		}
		$to_time = $from_time + $days * DAY - 1; // n日後の1秒前

		return compact('from_time', 'to_time');
	}
}
?>
