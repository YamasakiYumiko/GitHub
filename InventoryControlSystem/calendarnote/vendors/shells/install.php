<?php
/**
 * #!/bin/sh
 * /path/to/cake/console/cake install run ja SJIS-win -app /path/to/calendarnote
 *
 */
App::import('Component', 'Acl');
App::import('Model', 'DbAcl');
class InstallShell  extends Shell {
	var $uses = array('User', 'Group');
	var $error = false;
	var $charset = false;

	function initialize() {
		parent::initialize();
		if (isset($this->args[1])) {
			Configure::write('Config.language', $this->args[1]);
		}
		if (isset($this->args[2])) {
			$this->charset = $this->args[2];
		}
		$this->out('CalendarNote Install Program');
		$this->hr();
	}

	function run() {
		$this->check();
		$this->input();
		$this->save();
	}
	function check() {
		if($this->User->hasAny() || $this->Group->hasAny()) {
			$this->out(__('The user and the group are already registered and it is in the state which can be running.',true));
			$this->out(__('When you want to re-installation, please drop/create a database by the Schema command once.',true));
			$this->out('cake'.DS.'console'.DS.'cake schema run create -app calendarnote');
			$this->hr();
			$this->_stop();
		}
	}
	function input() {
		$group = array('Group'=>array('name'=>'','memo'=>''));
		$group_fields = array('name'=>'Group Name','memo'=>'Memo');
		$user = array('User'=>array('username'=>'','password'=>'','fullname'=>'','email'=>'','tel'=>'','memo'=>''));
		$user_fields = array('username'=>'Login Name','password'=>'Password','fullname'=>'Fullname','email'=>'Email','tel'=>'Tel','memo'=>'Memo');
		while(true) {
			$this->out(__('New Group',true));
			$this->hr();
			$group['Group'] = $this->inputs($group['Group'], $group_fields);
			$this->Group->set($group);
			$this->Group->validates();
			if(!empty($this->Group->validationErrors)) {
				$this->validationError($this->Group->validationErrors, $group_fields);
				continue;
			}
			break;
		}
		while(true) {
			$this->hr();
			$this->out(__('New User',true));
			$this->hr();
			$user['User'] = $this->inputs($user['User'], $user_fields);
			$user['User']['confirm_password'] = $user['User']['password'];
			$user['Group']['Group'][0] = 1;
			$this->User->set($user);
			$this->User->validates();
			if(!empty($this->User->validationErrors)) {
				$this->validationError($this->User->validationErrors, $user_fields);
				continue;
			}
			break;
		}
	}
	function save() {
		$this->hr();
		$ret = $this->in(__('Are you sure you want to save ?',true), array('y','n'), 'y');
		if($ret == 'y') {
			$this->Group->save();
			$this->User->data['Group']['Group'][0] = $this->Group->getLastInsertID();
			$this->User->save();
			$this->acl();
			$acl = new AclComponent();
			$controller = null;
			$acl->startup($controller);
			foreach($this->controllers as $controller) {
				$acl->allow(array('model'=>'Group','foreign_key'=>$this->Group->id), $controller);
			}
			$this->out(__('Installation All complete.',true));
		} else {
			$this->out(__('Installation was interrupted.',true));
		}
	}
	
	function inputs($model, $fields) {
		foreach($fields as $field => $label) {
			$model[$field] = $this->in(__($label, true), null, $model[$field]);
		}
		return $model;
	}
	
	function validationError($errors, $labels) {
		$this->out(__('Please correct errors below.',true));
		foreach($errors as $field => $error) {
			$this->out(__($labels[$field],true).' : '.$error);
		}
	}
	
	function main() {
		$this->help();
	}
	function help() {
		$this->out("Usage: cake install run <locale> <charset> ....");
		$this->hr();
		$this->out('Params:');
		$this->out("\n<locale>\n\tset display locale by <locale>. uses 'en' if none is specified");
		$this->out("\n<charset>\n\tset encoding charset by <charset>. uses 'UTF-8' if none is specified");
		$this->out("");
		$this->_stop();
	}
	function out($string, $newline = true) {
		if ($this->charset) {
			if(!is_array($string)) {
				$string = array($string);
			}
			foreach($string as $key => $str) {
				$string[$key] = mb_convert_encoding($str, $this->charset, "UTF-8");
			}
		}
		return parent::out($string, $newline);
	}
	function in($prompt, $options = null, $default = null) {
		if ($this->charset) {
			$prompt = mb_convert_encoding($prompt, $this->charset, "UTF-8");
			$default = mb_convert_encoding($default, $this->charset, "UTF-8");
			return mb_convert_encoding(parent::in($prompt, $options, $default), "UTF-8", $this->charset);
		}
		return parent::in($prompt, $options = null, $default = null);
	}
	var $controllers = array("groups", "schedules", "users");
	function acl() {
		$aco = new Aco();
		foreach($this->controllers as $controller) {
			$parent = $aco->node($controller);
			if($parent === false) {
				$aco->create();
				$aco->save(array('parent_id'=>null, 'alias'=>$controller));
				$parent_id = $aco->id;
			} else {
				$parent_id = $parent[0]['Aco']['id'];
			}

			App::import('Core', 'Controller');
			App::import('Controller', Inflector::camelize($controller));
			$ctrlClassName = $controller.'Controller';
			$ctrlClass =& new $ctrlClassName();
			$childMethods = get_class_methods($ctrlClassName);
			$parentMethods = get_class_methods('Controller');
			foreach ($childMethods as $key => $value) {
				$childMethods[$key] = strtolower($value);
			}
			foreach ($parentMethods as $key => $value) {
				$parentMethods[$key] = strtolower($value);
			}
			$methods = array_diff($childMethods, $parentMethods);
			$scaffoldActions = $ctrlClass->scaffold;
			if ($scaffoldActions !== false) {
				$admin = Configure::read('Routing.admin');
				if (empty($scaffoldActions)) {
					$scaffoldActions = array('index', 'view', 'add', 'edit', 'delete');
				} elseif (!empty($admin) && $scaffoldActions === $admin) {
					$scaffoldActions = array($admin .'_index', $admin .'_view', $admin .'_add', $admin .'_edit', $admin .'_delete');
				}
				$methods = array_merge($methods, $scaffoldActions);
			}

			foreach($methods as $method) {
				$node = $controller.'/'.$method;
				if(strpos($method, '_', 0) === 0 || $node == 'users/login' || $node == 'users/logout') {
					continue;
				}
				if($aco->node($node) === false) {
					$aco->create();
					$aco->save(array('parent_id'=>$parent_id, 'alias'=>$method));
				}
			}
		}
		$this->hr();
		$this->out(__('Initialization of ACL was completed.',true));
		$this->hr();
	}
}
?>
