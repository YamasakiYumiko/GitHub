<?php
App::import('Component','Auth');
class AppAuthComponent extends AuthComponent {
	var $authorize = 'actions';

	function initialize(&$controller) {
		parent::initialize($controller);
		$this->_methods = $this->filterScaffoldActions($controller);
	}
	function startup(&$controller) {
    if (!empty($controller->params['mobile'])) {
      $this->loginAction = '/m' . $this->loginAction;
      $this->Session->write('Auth.redirect', '/');
    }

		$_methods = $controller->methods;
		$controller->methods = $this->filterScaffoldActions($controller);
		parent::startup($controller);
		$controller->methods = $_methods;
	}
	function filterScaffoldActions($controller) {
		$actions = $controller->methods;
		$admin = Configure::read('Routing.admin');
		$scaffoldActions = $controller->scaffold;
		if ($scaffoldActions !== false) {
			if (empty($scaffoldActions)) {
				$scaffoldActions = array('index', 'list', 'view', 'add', 'create', 'edit', 'update', 'delete');
			} elseif (!empty($admin) && $this->scaffoldActions === $admin) {
				$scaffoldActions = array($admin .'_index', $admin .'_list', $admin .'_view', $admin .'_add', $admin .'_create', $admin .'_edit', $admin .'_update', $admin .'_delete');
			}
			$actions = array_merge($actions, $scaffoldActions);
		}
		return $actions;
	}
	function isAuthorized($type = null, $object = null, $user = null) {
		if (empty($user) && !$this->user()) {
			return false;
		} elseif (empty($user)) {
			$user = $this->user();
		}
		$valid = false;
		extract($this->__authType($type));
		switch ($type) {
		case 'actions':
			if($this->action() == 'Users/logout') {
				return true;
			}
			if($this->action() == 'Users/mobile_logout') {
				return true;
			}
			if($this->action(':controller') == 'UserProfiles') {
				return true;
			}
			$model = ClassRegistry::init('Belong');
			$belongs = $model->find('list', array('conditions'=>array('user_id'=>$user['User']['id']),'fields'=>array('id','group_id')));
			foreach($belongs as $belong) {
				if($this->Acl->check(array('model'=>'Group','foreign_key'=>$belong), $this->action())) {
					$valid = true;
					break;
				}
			}
			break;
		default:
			$valid = parent::isAuthorized($type, $object, $user);
			break;
		}
		return $valid;
	}

	function action($action = ':controller/:action') {
    $_action = $this->params['action'];
    if (!empty($this->params['prefix'])) {
      $prefix = $this->params['prefix'].'_';
      $this->params['action'] = substr($this->params['action'], strlen($prefix));
    }

    $ret = parent::action($action);

    $this->params['action'] = $_action;

    return $ret;
  }
}
?>
