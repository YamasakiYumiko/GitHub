<?php
class User extends AppModel {

	var $name = 'User';

	var $validate = array(
		'username' => array(
			'harfWidthChar' => array('rule' => array('custom', '/[^\\W]/u'), 'required' => true),
			'unique' => array('rule' => array('isUnique'), 'message'=>'The same login name exists.')
		),
		'password' => array(
			'minLength' => array('rule' => array('minLength', 4), 'required' => true),
			'equalPasswords' => array('rule' => array('equalPasswords', 'confirm_password'))
		),
		'fullname' => array(
			'require' => array('rule' => array('notEmpty'), 'required' => true)
		),
		'email' => array(
			'email' => array('rule' => array('email'), 'required' => true)
		)
	);
	var $hasAndBelongsToMany = array('Group' => array('with' => 'Belong'),'Schedule' => array('with' => 'Participant'));
	var $displayField = 'fullname';
	
	function setWhiteList($method, $data) {
		if($method == 'edit' && !empty($data)) {
			if(empty($data['User']['password']) && empty($data['User']['confirm_password'])) {
				$this->whitelist = array('id','username','fullname','email','tel','memo');
			}
		}
	}
	function hashPasswords($data) {
		return $data;
	}
	function beforeSave($options = array()) {
		App::import('Component','Auth');
		if(!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
	}
	function beforeValidate(&$options) {
		if(empty($this->data['Group']['Group']) && isset($this->hasAndBelongsToMany['Group'])) {
			$this->invalidate('Group', array('Group'=>'select'));
		}
		return parent::beforeValidate($options);
	}
}
?>