<?php
class UserProfilesController extends AppController {

	var $name = 'UserProfiles';
	var $scaffold=array('edit','view');
	var $components = array('Confirm');
	var $uses = array('User');

	function _beforeScaffold($method) {
		$this->User->setWhiteList($method, $this->data);
		return parent::_beforeScaffold($method);
	}
	function _beforeConfirm($method) {
		$this->User->setWhiteList($method, $this->data);
		return parent::_beforeConfirm($method);
	}
	function beforeFilter() {
		$this->AppAuth->authenticate = $this->User;
		$this->User->unbindModel(array('hasAndBelongsToMany' => array('Group')), false);
		$user_id = $this->AppAuth->user('id');
		if(empty($this->params['pass']) || $user_id != $this->params['pass'][0]) {
			parent::redirect(array('action'=>$this->action, 'id'=>$user_id));
		}
		return parent::beforeFilter();
	}
	function _afterScaffoldSave($action) {
		$this->Session->setFlash(sprintf(__('The %1$s has been %2$s', true), __(Inflector::humanize($this->modelClass), true), __('updated', true)));
		$this->redirect('view');
	}
	
	function allowActions($user_id) {
		if (!isset($this->params['requested'])) {
			return false;
		}
		$acos = $this->Acl->Aco->find('threaded', array('fields'=>'Aco.*', 'recursive'=>-1));
		$groups = $this->User->Belong->find('list', array('conditions'=>array('user_id'=>$user_id), 'fields'=>array('Belong.id', 'Belong.group_id')));
		$fields = array('Permission.id','Permission.aco_id');
		$conditions = array('Aro.model'=>'Group', 'Aro.foreign_key'=>array_values($groups));
		$recursive = 1;
		$group = array('Permission.aco_id');
		$aros = $this->Acl->Aro->Permission->find('list', compact('fields', 'conditions', 'recursive', 'group'));

		$actions = array();
		foreach($acos as $controller) {
			foreach($controller['children'] as $action) {
				if(in_array($action['Aco']['id'], $aros) || in_array($controller['Aco']['id'], $aros)) {
					$actions[] = $controller['Aco']['alias'].'/'.$action['Aco']['alias'];
				}
			}
		}
		return $actions;
	}
}
?>