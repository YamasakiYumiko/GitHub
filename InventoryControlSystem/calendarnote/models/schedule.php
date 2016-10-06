<?php
class Schedule extends AppModel {

	var $name = 'Schedule';
	var $validate = array(
		'from' => array(
			'date_format' => array('rule' => array('datetime'), 'required' => true),
			'compare_from_to'=> array('rule' => array('compareFromTo')),
			'not_duplicate_schedule' => array('rule' => array('isDuplicate'))
		),
		'to' => array(
			'date_format' => array('rule' => array('datetime'), 'required' => true)
		),
		'title' => array(
			'require' => array('rule' => array('notEmpty'), 'required' => true)
		)
	);
	var $hasAndBelongsToMany = array('User' => array('with' => 'Participant'));

	function isDuplicate($value) {
		if(empty($this->data['User']['User'])) {
			return true; // error by beforeValidate
		}
		$from = $this->data[$this->name]['from'];
		$to = $this->data[$this->name]['to'];
		$conditions = array('or' => array(
			array("Schedule.from BETWEEN ? AND ?" => array($from, $to)),
			array("Schedule.to BETWEEN ? AND ?" => array($from, $to))
		));
		if($this->id) {
			$conditions[$this->alias . '.' . $this->primaryKey] = '!= '.$this->id;
		}
		$conditions['Participant.user_id'] = $this->data['User']['User'];
		$recursive = 1;
		$count = $this->Participant->find('count', compact('conditions', 'recursive'));
		return $count == 0;
	}

	function findByTimes($times, $user_id) {
		extract($times);
		$from = date("Y-n-j H:i:s", $from_time);
		$to = date("Y-n-j H:i:s", $to_time);
		$conditions = array('or' => array(
			array("Schedule.from BETWEEN ? AND ?" => array($from, $to)),
			array("Schedule.to BETWEEN ? AND ?" => array($from, $to))
		),
			'Participant.user_id'=>$user_id
		);
		$order = 'from';
		$fields = 'Schedule.*';
		return $this->Participant->find('all', compact('conditions', 'order', 'fields'));
	}
	function findByUser($user_id) {
		$conditions = array('Participant.user_id'=>$user_id);
		$order = 'Schedule.id';
		$fields = 'Schedule.*';
		return $this->Participant->find('all', compact('conditions', 'order', 'fields'));
	}
	function beforeValidate(&$options) {
		if(empty($this->data['User']['User'])) {
			$this->invalidate('User', array('User'=>'requireParticipant'));
		}
		return parent::beforeValidate($options);
	}
}
?>
