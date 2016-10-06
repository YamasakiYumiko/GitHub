<?php
class SchedulesController extends AppController {

	var $name = 'Schedules';
	var $scaffold;
	var $components = array('Calendar', 'Confirm', 'RequestHandler');
	var $helpers = array('ScheduleTable');
	var $uses = array('Schedule', 'Group');

	function index($scope='week', $year=false, $month=false, $day=false) {
		if(!$year) $year = date('Y');
		if(!$month) $month = date('m');
		if(!$day) $day = date('d');
		$current = sprintf("%d/%02d/%02d", $year, $month, $day);
		$this->Session->write('SCHEDULE_INDEX_CONDITION', compact('scope', 'current'));
		$group_id = $this->_getGroupId();
		$times = $this->Calendar->scopeToTimes($scope, $year, $month, $day);
		$user_ids = $this->_getUserIds($this->AppAuth->user('id'), $group_id, $scope);
		$schedules = array();
		foreach($user_ids as $user_id) {
			$schedules[] = array(
				'schedules'=> $this->Schedule->findByTimes($times, $user_id),
				'user'=> $this->Schedule->User->read(null, $user_id)
			);
		}
		$groups = $this->Group->find('list');
    $this->set(compact('schedules', 'scope', 'times', 'current', 'groups', 'group_id'));
	}

	function redirect($url, $status = null, $exit = true) {
		if(is_array($url) && $url['action'] == 'index') {
			$prev = $this->Session->read('SCHEDULE_INDEX_CONDITION');
			if(!empty($prev)) {
				extract($prev);
				$url['id'] = $scope;
				$url[] = $current;
			}
		}
		return parent::redirect($url, $status, $exit);
	}

	function mobile_index($scope='week', $year=false, $month=false, $day=false) {
    $this->index($scope, $year, $month, $day);
  }
	
	function _getGroupId() {
		$group_id = $this->Session->read('SCHEDULE_GROUP');
		if(array_key_exists('group', $this->params['named'])) {
			$group_id = $this->params['named']['group'];
			$this->Session->write('SCHEDULE_GROUP', $group_id);
		}
		return $group_id;
	}
	function _getUserIds($user_id, $group_id, $scope) {
		$user_ids = array($user_id);
		if(!empty($group_id) && $scope != 'month') {
			$user_ids = $this->Group->Belong->find('list', array(
				'conditions'=>array('Belong.group_id'=>$group_id), 
				'fields'=>array('Belong.user_id','Belong.user_id'), 
				'group'=>'Belong.user_id'));
			if(in_array($user_id, $user_ids)) {
				unset($user_ids[$user_id]);
				$user_ids = array_merge(array(0=>$user_id), $user_ids);
			}
		}
		return $user_ids;
	}

	function csv() {
    if (!$this->RequestHandler->isPost()) {
      exit;
    }

    $schedules = $this->Schedule->findByUser($this->AppAuth->user('id'));
    if (!$schedules) {
      exit;
    }

    $csv = array();
    foreach ($schedules as $schedule) {
      $rec = array();
      $rec[] = Set::extract($schedule, 'Schedule.id');
      $rec[] = Set::extract($schedule, 'Schedule.from');
      $rec[] = Set::extract($schedule, 'Schedule.to');
      $rec[] = Set::extract($schedule, 'Schedule.title');
      $rec[] = Set::extract($schedule, 'Schedule.contents');
      $rec[] = Set::extract($schedule, 'Schedule.created');
      $rec[] = Set::extract($schedule, 'Schedule.updated');

      $csv[] = mb_convert_encoding('"'.implode('","', $rec).'"', 'SJIS', Configure::read('App.encoding'));
    }
    
    $path = tempnam(TMP.DS.'work', 'csv');
    file_put_contents($path, implode("\r\n", $csv));

    $this->view = 'Media';
    $params = array(
          'id' => basename($path),
          'name' => 'schedule',
          'download' => true,
          'extension' => 'csv',
          'path' => dirname($path).DS,
          'isDelete' => true,
    );
    $this->set($params);
  }
}
?>
