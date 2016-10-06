<?php
App::import('Component','Security');
class AppSecurityComponent extends SecurityComponent {
	function startup(&$controller) {
		$this->Controller = & $controller;
	}
	function validate($valid=true) {
		$this->validatePost = $valid;
		parent::startup($this->Controller);
	}
}
?>
