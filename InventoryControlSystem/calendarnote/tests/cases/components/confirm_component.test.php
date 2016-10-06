<?php 
App::import('Controller', 'Users');

class ConfirmComponentTestController extends UsersController {
	var $autoRender = false;
	var $beforeConfirmed = false;
	var $renderPage = false;
	
	function _beforeConfirm($method) {
		$this->beforeConfirmed = true;
		return parent::_beforeConfirm($method);
	}
	function render($action = null, $layout = null, $file = null) {
		$this->renderPage = $action;
		parent::render($action, $layout, $file);
	}
}

class ConfirmComponentTest extends CakeTestCase {
	var $Controller = null;

	function startTest($method) {
		$this->Controller = new ConfirmComponentTestController();
		restore_error_handler();
		@$this->Controller->constructClasses();
		@$this->Controller->Component->initialize($controller);
		@$this->Controller->beforeFilter();
		set_error_handler('simpleTestErrorHandler');

	}
	function testUserConfirm() {
		$this->Controller->action = 'add';
		$this->Controller->data = array('User'=>array('username'=>'suzuki', 'password'=>'111111', 'confirm_password'=>'111111', 'fullname'=>'suzuki tarou', 'email'=>'suzuki@japan.jp', 'tel'=>'', 'memo'=>''), 'Group'=>array('Group'=>array(1)));
		$this->Controller->Confirm->autoExit = false;
		
		ob_start();
		$this->Controller->Confirm->startup($this->Controller);
		ob_get_clean();
		
		$this->assertTrue($this->Controller->beforeConfirmed);
		$this->assertEqual('confirm', $this->Controller->renderPage);
		$this->assertEqual($this->Controller->data, $this->Controller->Confirm->Session->read('ConfirmComponentData'));
	}
	function testUserInvalid() {
		$this->Controller->action = 'add';
		$this->Controller->data = array('User'=>array('username'=>'suzuki', 'password'=>'111111', 'confirm_password'=>'222222', 'fullname'=>'suzuki tarou', 'email'=>'suzuki@japan.jp', 'tel'=>'', 'memo'=>''), 'Group'=>array('Group'=>array(1)));
		$this->Controller->Confirm->autoExit = false;

		ob_start();
		$this->Controller->Confirm->startup($this->Controller);
		ob_get_clean();

		$this->assertTrue($this->Controller->beforeConfirmed);
		$this->assertTrue($this->Controller->scaffoldError);
		$this->assertEqual('scaffold.edit', $this->Controller->renderPage);
	}

	function testUserAdd() {
		$this->Controller->action = 'add';
		$data = array('User'=>array('username'=>'suzuki', 'password'=>'111111', 'confirm_password'=>'111111', 'fullname'=>'suzuki tarou', 'email'=>'suzuki@japan.jp', 'tel'=>'', 'memo'=>''), 'Group'=>array('Group'=>array(1)));
		$this->Controller->data = $data;
		$this->Controller->Confirm->autoExit = false;

		ob_start();
		$this->Controller->Confirm->startup($this->Controller);
		ob_get_clean();

		$this->assertTrue($this->Controller->beforeConfirmed);
		$this->assertEqual('confirm', $this->Controller->renderPage);
		$this->assertEqual($data, $this->Controller->Confirm->Session->read('ConfirmComponentData'));
		
		$this->Controller->beforeConfirmed = false;
		$this->Controller->renderPage = false;
		$tokenData = unserialize($this->Controller->Confirm->Session->read('_Token'));
		$this->Controller->data = array('_Token'=>array('key'=>$tokenData['key']));
		$this->Controller->data = array('_Token'=>array('fields'=>'5389f0ec7e41f2c6a2c4f506e5168dbc1e81fd31%3An%3A0%3A%7B%7D'));
		ob_start();
		$this->Controller->Confirm->startup($this->Controller);
		ob_get_clean();
		
		$this->assertFalse($this->Controller->beforeConfirmed);
		$this->assertFalse($this->Controller->renderPage);
		$this->assertEqual($data, $this->Controller->data);
	}

	function endTest($method) {
		unset($this->Controller);
	}
}
?>