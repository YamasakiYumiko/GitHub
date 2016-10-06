<?php
class HolidayUtil extends Object {
	function getHolidayNames($from, $to) {
		$locale = Configure::read('Config.language');
		$find = App::import(null, 'Holidays', array('file' => APP.'utils'.DS.'holidays'.DS.$locale.DS.'holidays.php'));
		$holidays = array();
		if($find) {
			$time = $from;
			while(date('Ym',$time) <= date('Ym',$to)) {
				$year = date('Y', $time);
				$month = date('n', $time);
				$holidays[$month] = Holidays::getHolidayNames($year, $month);
				$time = mktime(0,0,0,$month+1,1,$year);
			}
		} else {
			$holidays = array();
		}
		return $holidays;
	}
}
?>