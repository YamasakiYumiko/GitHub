<?php 
/* SVN FILE: $Id$ */
/* SchedulesController Test cases generated on: 2008-12-13 14:12:24 : 1229147604*/
App::import('Controller', 'Schedules');

class TestSchedulesController extends SchedulesController {
	function beforeFilter() {
		parent::beforeFilter();
		$this->AppAuth->allow('*');
	}
}

class SchedulesControllerTest extends CakeTestCase {
	var $Schedules = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest($method) {	// setupから変更
		Configure::write('Acl.database', 'test_suite');
		$this->Schedules = new TestSchedulesController();
	}
	function startController(&$controller, $params = array()) {
	}

	function testSchedulesControllerInstance() {
		$this->Schedules->constructClasses();
		$this->assertTrue(is_a($this->Schedules, 'SchedulesController'));
	}

	function testDefaultIndexAction() {
		$result = $this->testAction('/schedules/', array('return'=>'vars','fixturize'=>true, 'controller'=>'TestSchedules'));
		$this->assertEqual('week', $result['scope']);
		$this->assertEqual(date('Y/m/d'), $result['current']);
		$this->assertTrue(!empty($result['times']['from_time']));
		$this->assertTrue(!empty($result['times']['to_time']));
		$this->assertTrue(array_key_exists('schedules', $result));
	}
	
	function testMonthlyIndexAction() {
		$result = $this->testAction('/schedules/index/month/2008/12/', array('return'=>'vars','fixturize'=>true, 'controller'=>'TestSchedules'));
		$this->assertEqual('month', $result['scope']);
		$this->assertTrue('2008/12', substr($result['current'], 7));
		$this->assertTrue(!empty($result['times']['from_time']));
		$this->assertTrue(!empty($result['times']['to_time']));
		$this->assertTrue(array_key_exists('schedules', $result));
	}

	function testWeeklyIndexAction() {
		$result = $this->testAction('/schedules/index/week/2008/12/24', array('return'=>'vars','fixturize'=>true, 'controller'=>'TestSchedules'));
		$this->assertEqual('week', $result['scope']);
		$this->assertTrue('2008/12/24', $result['current']);
		$this->assertTrue(!empty($result['times']['from_time']));
		$this->assertTrue(!empty($result['times']['to_time']));
		$this->assertTrue(array_key_exists('schedules', $result));
	}

	function testDailyIndexAction() {
		$result = $this->testAction('/schedules/index/day/2008/12/24', array('return'=>'vars','fixturize'=>true, 'controller'=>'TestSchedules'));
		$this->assertEqual('day', $result['scope']);
		$this->assertTrue('2008/12/24', $result['current']);
		$this->assertTrue(!empty($result['times']['from_time']));
		$this->assertTrue(!empty($result['times']['to_time']));
		$this->assertTrue(array_key_exists('schedules', $result));
	}
	
	function testGetGroupId() {
		$this->Schedules->constructClasses();
		$this->Schedules->params['named'] = array();
		$group_id = $this->Schedules->_getGroupId();
		$this->assertNull($group_id);

		$this->Schedules->params['named'] = array('group'=>5);
		$group_id = $this->Schedules->_getGroupId();
		$this->assertEqual(5, $group_id);
		
		$this->Schedules->params['named'] = array();
		$group_id = $this->Schedules->_getGroupId();
		$this->assertEqual(5, $group_id);
	}
	
	function testGetUserIdsByPrivate() {
		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(10, null, 'week');
		$this->assertEqual(array(10), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(10, null, 'month');
		$this->assertEqual(array(10), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(10, null, 'day');
		$this->assertEqual(array(10), $user_ids);
	}

	function testGetUserIdsByGroup() {
		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(1, 2, 'week');
		$this->assertEqual(array('2'=>'2','3'=>'3'), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(1, 2, 'month');
		$this->assertEqual(array(1), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(1, 2, 'day');
		$this->assertEqual(array('2'=>'2','3'=>'3'), $user_ids);
	}

	function testGetUserIdsByGroupIncludeLoginUser() {
		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(3, 2, 'week');
		$this->assertEqual(array('0'=>'3', '1'=>'2'), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(3, 2, 'month');
		$this->assertEqual(array(3), $user_ids);

		$this->Schedules->constructClasses();
		$user_ids = $this->Schedules->_getUserIds(3, 2, 'day');
		$this->assertEqual(array('0'=>'3', '1'=>'2'), $user_ids);
	}

	function endTest($method) {	// tearDownから変更
		if(!empty($this->Schedules->Session)) $this->Schedules->Session->destroy();
		unset($this->Schedules);
	}
}
?>