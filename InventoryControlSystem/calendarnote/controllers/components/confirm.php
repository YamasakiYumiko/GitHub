<?php
class ConfirmComponent extends Object {
	var $components = array('Session','AppSecurity');
	var $autoExit = true;

	function startup(&$controller) {
		$this->Controller = & $controller;
		$this->AppSecurity->startup($controller);

		$method = $controller->action;
		if(($method == 'add' || $method == 'edit') && !empty($controller->data)) {
			$this->confirm($method, $controller->data);
		}
	}
	private function confirm($method, $data) {
		if(!isset($data['_Token']) || !isset($data['_Token']['fields'])) {
			if(!$this->Controller->_beforeConfirm($method)) {
				return;
			}
			$model = &$this->Controller->{$this->Controller->modelClass};
			$model->set($data);
			$this->loadAssoc($model);
			if($model->validates()) {
				$this->AppSecurity->validate(false);
				$this->Session->write('ConfirmComponentData', $data);
				$this->Controller->set(array('submit'=>$method));
				$this->Controller->render('confirm');
			} else {
				$this->Controller->scaffoldError = true;
				$this->Session->setFlash(__('Please correct errors below.', true));
				$methods = array_flip($this->Controller->methods);
				if (!isset($methods[strtolower($method)])) {
					$name = $method;
					if ($name === 'add') {
						$name = 'edit';
					}
					$this->Controller->render('scaffold.'.$name);
				} else {
					$this->Controller->render($method);
				}
			}
			$this->Controller->afterFilter();
			e($this->Controller->output);
			if($this->autoExit) exit;
		} else {
			$this->AppSecurity->validate();
			$this->Controller->data = $this->Session->read('ConfirmComponentData');
		}
	}
	
	private function loadAssoc($model) {
		foreach ($model->belongsTo as $assocName => $assocData) {
			$varName = Inflector::variable(Inflector::pluralize(preg_replace('/(?:_id)$/', '', $assocData['foreignKey'])));
			$this->Controller->set($varName, $model->{$assocName}->find('list'));
		}
		foreach ($model->hasAndBelongsToMany as $assocName => $assocData) {
			$varName = Inflector::variable(Inflector::pluralize($assocName));
			$this->Controller->set($varName, $model->{$assocName}->find('list'));
		}
	}
	
}
?>
