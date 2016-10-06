<?php 
/* SVN FILE: $Id$ */
/* UsersController Test cases generated on: 2009-01-14 16:01:53 : 1231918613*/
App::import('Controller', 'Users');

class TestUsersController extends UsersController {
	var $autoRender = false;
	var $_redirect = false;
	function beforeFilter() {
		parent::beforeFilter();
		$this->AppAuth->allow('*');
	}
	function redirect($url, $status = null, $exit = true) {
		$this->_redirect = $url;
	}
}

class UsersControllerTest extends CakeTestCase {
	var $Users = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest($method) {
		Configure::write('Acl.database', 'test_suite');
		$this->Users = new TestUsersController();
		$this->Users->constructClasses();
	}
	function startController(&$controller, $params = array()) {
	}
	function testUsersControllerInstance() {
		$this->assertTrue(is_a($this->Users, 'UsersController'));
	}
	function testDeleteUser() {
		$result = $this->testAction('/users/delete/2', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestUsers'));
		$this->assertTrue(empty($result->controller->Controller->scaffoldError));
		$this->assertFalse($this->Users->User->read(2));
		$result = $this->testAction('/users/delete/3', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestUsers'));
		$this->assertTrue(empty($result->controller->Controller->scaffoldError));
		$this->assertFalse($this->Users->User->read(3));

		$result = $this->testAction('/users/delete/1', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestUsers'));
		$this->assertTrue($result->controller->Controller->scaffoldError);
		$this->assertEqual(array('action'=>'index'), $result->controller->_redirect);
		$this->assertNotNull($this->Users->User->read(1));
	}
	function testCannotDeleteLoginUser() {
		$this->Users->AppAuth->login(array('User'=>array('username'=>'hide','password'=>'password')));
		$this->assertEqual('hide', $this->Users->AppAuth->user('username'));
		$result = $this->testAction('/users/delete/2', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestUsers'));
		$this->assertTrue($result->controller->Controller->scaffoldError);
		$this->assertEqual(array('action'=>'index'), $result->controller->_redirect);
		$this->assertNotNull($this->Users->User->read(2));
		$this->Users->AppAuth->logout();
	}
	
	function tearDown() {
		unset($this->Users);
	}
}
?>