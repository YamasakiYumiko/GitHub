<?php
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
class AppController extends Controller {
	var $components = array('AppAuth', 'Acl', 'Mobile');
	var $helpers = array('AppForm', 'Form');
	var $scaffoldError = false;

	function _beforeConfirm($method) {
		return true;
	}
	function _scaffoldError($method) {
		return $this->scaffoldError;
	}
	function beforeFilter() {
    if (!empty($this->params['prefix'])) {
      $this->layout = $this->params['prefix'];
    }

		$this->set(array('loginUser'=>$this->AppAuth->user()));
		return parent::beforeFilter();
	}
}
?>
