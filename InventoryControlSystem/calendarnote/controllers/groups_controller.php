<?php
class GroupsController extends AppController {

	var $name = 'Groups';
	var $scaffold;

	function _beforeScaffold($method) {
		if($method == 'delete') {
			$id = $this->params['pass'][0];
			if($this->Group->hasAnyUser($id)) {
				$this->Controller->scaffoldError = true;
				$this->Session->setFlash(__('Can not delete because some user belongs to the group.', true));
				$this->redirect('index');
			}
		}
		elseif($method == 'add' || $method == 'edit') {
			$acos = $this->Acl->Aco->find('threaded', array('fields'=>'Aco.*', 'recursive'=>-1));
			$aros = array();
			if($method == 'edit' && empty($this->data)) {
				$fields = array('Permission.id','Permission.aco_id');
				$conditions = array('Aro.model'=>'Group', 'Aro.foreign_key'=>$this->params['pass'][0]);
				$recursive = 1;
				$aros = $this->Acl->Aro->Permission->find('list', compact('fields', 'conditions', 'recursive'));
			}
			$this->set(compact('acos', 'aros'));
		}
		return parent::_beforeScaffold($method);
	}
	function _afterScaffoldSave($method) {
		if($method == 'edit') {
			$conditions = array('Aro.model'=>'Group', 'Aro.foreign_key'=>$this->Group->id);
			$this->Group->Aro->Permission->deleteAll($conditions, false);
		}
		if(is_array($this->data['Group']['Permission'])) {
			foreach($this->data['Group']['Permission'] as $alias) {
				$this->Acl->allow(array('model'=>'Group','foreign_key'=>$this->Group->id), $alias);
			}
		}
		return true;
	}
	
}
?>