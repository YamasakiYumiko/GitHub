<?php
class Group extends AppModel {

	var $name = 'Group';
	var $actsAs = array('Acl');

	var $validate = array(
		'name' => array(
			'require' => array('rule' => array('notEmpty'), 'required' => true),
			'unique' => array('rule' => array('isUnique'), 'message'=>'The same group name exists.')
		),
		'memo' => array(
			'require' => array('rule' => array('notEmpty'), 'required' => true)
		)
	);
	var $hasAndBelongsToMany = array('User' => array('with' => 'Belong'));
	
	function hasAnyUser($id) {
		return $this->Belong->hasAny(array('group_id'=>$id));
	}
	function parentNode() {
		return null;
	}
}
?>