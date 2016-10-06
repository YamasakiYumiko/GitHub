<?php 
App::import('Controller', 'Schedules');

class CalendarComponentTestController extends SchedulesController {
	var $autoRender = false;
}

class CalendarComponentTest extends CakeTestCase {
	var $Controller = null;

	function startTest($method) {
		$this->Controller = new CalendarComponentTestController();
		restore_error_handler();
		@$this->Controller->Component->init($this->Controller);
		set_error_handler('simpleTestErrorHandler');

		$this->Controller->Calendar->startup($this->Controller);
	}

	function testSchedulesControllerInstance() {
		$this->assertTrue(is_a($this->Controller->Calendar, 'CalendarComponent'));
	}

	function testMonthlyScope200901() {
		$scope = 'month';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2009, 1, 10);
		extract($result);
		$this->assertEqual(mktime(0,0,0,12,28,2008), $from_time);
		$this->assertEqual(mktime(23,59,59,1,31,2009), $to_time);
	}
	function testMonthlyScope200902() {
		$scope = 'month';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2009, 2, 7);
		extract($result);
		$this->assertEqual(mktime(0,0,0,2,1,2009), $from_time);
		$this->assertEqual(mktime(23,59,59,2,28,2009), $to_time);
	}
	function testMonthlyScope200904() {
		$scope = 'month';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2009, 4, 13);
		extract($result);
		$this->assertEqual(mktime(0,0,0,3,29,2009), $from_time);
		$this->assertEqual(mktime(23,59,59,5,2,2009), $to_time);
	}
	function testWeeklyScope20081228() {
		$scope = 'week';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2008, 12, 28);
		extract($result);
		$this->assertEqual(mktime(0,0,0,12,28,2008), $from_time);
		$this->assertEqual(mktime(23,59,59,1,3,2009), $to_time);
	}
	function testWeeklyScope20090114() {
		$scope = 'week';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2009, 1, 14);
		extract($result);
		$this->assertEqual(mktime(0,0,0,1,14,2009), $from_time);
		$this->assertEqual(mktime(23,59,59,1,20,2009), $to_time);
	}
	function testDailyScope20090114() {
		$scope = 'day';
		$result = $this->Controller->Calendar->scopeToTimes($scope, 2009, 1, 14);
		extract($result);
		$this->assertEqual(mktime(0,0,0,1,14,2009), $from_time);
		$this->assertEqual(mktime(23,59,59,1,14,2009), $to_time);
	}

	function endTest($method) {
		unset($this->Schedules);
	}
}
?>