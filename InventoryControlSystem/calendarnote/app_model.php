<?php
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package		cake
 * @subpackage	cake.app
 */
class AppModel extends Model{
	/**
	 * validation messages
	 */
	static $error = array(
		'require' 	=> 'Please be sure to input.',
		'date_format' => 'Please input in the date format.',
		'compare_from_to' => 'Start time should specify the past from finish time.',
		'not_duplicate_schedule' => 'There are already other schedules.',
		'minLength' => 'Please input by %2$d or more characters.',
		'email' => 'Please input in mail address form.',
		'harfWidthChar'=> 'Please input a half-width alphanumeric character.',
		'equalPasswords' => 'Invalid Password Confirmation.',
		'select' => 'Please be sure to select.',
		'requireParticipant' => 'Please select a participant.'
	);
	
	function datetime($value) {
		$value = array_shift($value);
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$format = $db->columns['datetime']['format'];
		$dt = date($format, strtotime($value));
		return $dt == $value;
	}
	function compareFromTo($value) {
		$db =& ConnectionManager::getDataSource($this->useDbConfig);
		$format = $db->columns['datetime']['format'];
		$from = date($format, strtotime($this->data[$this->name]['from']));
		$to = date($format, strtotime($this->data[$this->name]['to']));
		return $from <= $to;
	}
	function isDuplicate($value) {
		$from = $this->data[$this->name]['from'];
		$to = $this->data[$this->name]['to'];
		$conditions = array('or' => array(
			array("from BETWEEN ? AND ?" => array($from, $to)),
			array("to BETWEEN ? AND ?" => array($from, $to))
		));
		if($this->id) {
			$conditions[$this->alias . '.' . $this->primaryKey] = '!= '.$this->id;
		}
		$count = $this->find('count', compact('conditions'));
		return $count == 0;
	}
	function equalPasswords($data, $target) {
		$source = '';
		if(isset($target)) {
			$source = array_shift($data);
		}
		return $source == $this->data[$this->name][$target];
	}
	function beforeValidate(&$options) {
		$options['fieldList'] = $this->whitelist;
		return parent::beforeValidate($options);
	}

	function invalidFields($options = array()) {
		$errors = parent::invalidFields($options);
		foreach($errors as $key => $value) {
			$model = false;
			if(is_array($value)) {
				$values = each($value);
				$model = $values['key'];
				$value = $values['value'];
			}
			$rule = array();
			if(!empty($this->validate[$key][$value]['rule'])) {
				$rule = $this->validate[$key][$value]['rule'];
			}
			if(array_key_exists($value, AppModel::$error)) {
				$error = vsprintf(__(AppModel::$error[$value],true), $rule);
			} else {
				$error = __($value,true);
			}
			if(!empty($model)) {
				$error = array($model=>$error);
			}
			$errors[$key] = $error;
		}
		$this->validationErrors = $errors;
		return $errors;
	}
}
?>