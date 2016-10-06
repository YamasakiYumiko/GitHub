<?php 
App::import(null, 'HolidayUtil', array('file' => APP.'utils'.DS.'holiday.php'));

class HolidayUtilTestCase extends CakeTestCase {
	function testJaHoliday200905() {
		$locale = Configure::write('Config.language', 'ja');
		// example Japanese 2009/05 calendar, into from <--> to
		$result = HolidayUtil::getHolidayNames(mktime(0,0,0,4,26,2009), mktime(0,0,0,6,6,2009));
		$expected = array(
			4=>array(29=>'昭和の日'),
			5=>array(3=>'憲法記念日', 4=>'みどりの日', 5=>'こどもの日', 6=>'振替休日'),
			6=>array()
		);
		$this->assertEqual($expected, $result);
	}
	function testJaHoliday200906() {
		$locale = Configure::write('Config.language', 'ja');
		// example Japanese 2009/06 calendar, Same month are ok even if out of from <--> to
		$result = HolidayUtil::getHolidayNames(mktime(0,0,0,5,31,2009), mktime(0,0,0,7,4,2009));
		$expected = array(
			5=>array(3=>'憲法記念日', 4=>'みどりの日', 5=>'こどもの日', 6=>'振替休日'),
			6=>array(),
			7=>array(20=>'海の日')
		);
		$this->assertEqual($expected, $result);
	}
}
?>