<?php 
/* SVN FILE: $Id$ */
/* GroupsController Test cases generated on: 2009-01-14 14:01:17 : 1231911557*/
App::import('Controller', 'Groups');

class TestGroupsController extends GroupsController {
	var $autoRender = false;
	var $_redirect = false;
	function beforeFilter() {
		parent::beforeFilter();
		$this->AppAuth->allow('*');
		$this->Acl->Aro = ClassRegistry::init('Aro');
		$this->Acl->Aco = ClassRegistry::init('Aco');
	}
	function redirect($url, $status = null, $exit = true) {
		$this->_redirect = $url;
	}
}

class GroupsControllerTest extends CakeTestCase {
	var $Groups = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest($method) {
		Configure::write('Acl.database', 'test_suite');
		$this->Groups = new TestGroupsController();
		$this->Groups->constructClasses();
	}
	function startController(&$controller, $params = array()) {
	}
	function testGroupsControllerInstance() {
		$this->assertTrue(is_a($this->Groups, 'GroupsController'));
	}
	function testCanNotDeleteHasUser() {
		$result = $this->testAction('/groups/delete/2', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestGroups'));
		$this->assertTrue($result->controller->Controller->scaffoldError);
		$this->assertEqual(array('action'=>'index'), $result->controller->_redirect);
		$this->assertNotNull($this->Groups->Group->read(2));
	}
	function testCanDeleteNoUser() {
		$result = $this->testAction('/groups/delete/1', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestGroups'));
		$this->assertTrue(empty($result->controller->Controller->scaffoldError));
		$this->assertFalse($this->Groups->Group->read(1));
	}
	function testUpdateGroupPermission() {
		$data = array('Group' => array(
			'id' => 2,
			'name' => 'development',
			'memo' => 'System development team.',
			'Permission' => array('schedules', 'users/view', 'users/edit')
		));
		ob_start();
		$result = $this->testAction('/groups/edit/2', array('return'=>'result', 'fixturize'=>true, 'controller'=>'TestGroups', 'data'=>$data));
		ob_get_clean();
		$this->assertTrue($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'schedules'));
		$this->assertFalse($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'groups'));
		$this->assertTrue($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'users/view'));
		$this->assertTrue($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'users/edit'));
		$this->assertFalse($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'users/index'));
		$this->assertFalse($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'users/add'));
		$this->assertFalse($result->controller->Acl->check(array('model'=>'Group','foreign_key'=>'2'), 'users/delete'));
	}

	function tearDown() {
		unset($this->Groups);
	}
}
?>