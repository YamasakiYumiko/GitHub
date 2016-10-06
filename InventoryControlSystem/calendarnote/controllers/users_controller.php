<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $scaffold;
	var $components = array('Confirm');

	function _beforeScaffold($method) {
		if($method == 'delete') {
			$id = $this->params['pass'][0];
			if($this->AppAuth->user('id') == $id || $this->User->find('count') == 1) {
				$this->Controller->scaffoldError = true;
				$this->Session->setFlash(__('Can not delete because this is last user.', true));
				$this->redirect('index');
			}
		}
		$this->User->setWhiteList($method, $this->data);
		return parent::_beforeScaffold($method);
	}
	function _beforeConfirm($method) {
		$this->User->setWhiteList($method, $this->data);
		return parent::_beforeConfirm($method);
	}

	function beforeFilter() {
		if($this->action == 'add' || $this->action == 'edit') {
			$this->AppAuth->authenticate = $this->User;
		}
		if($this->User->findCount() == 0) {
			$this->AppAuth->allow('add');
			if($this->action != 'add') {
				$this->redirect('add');
				exit;
			}
		}
		return parent::beforeFilter();
	}

	function login() {
	}
	function logout() {
		clearCache('element_'.$this->AppAuth->user('id').'_action_menu', 'views', '');
		$this->redirect($this->AppAuth->logout());
	}
	function mobile_login() {
    $this->login();
    $this->Session->renew();
	}
	function mobile_logout() {
    $this->logout();
	}
}
?>
