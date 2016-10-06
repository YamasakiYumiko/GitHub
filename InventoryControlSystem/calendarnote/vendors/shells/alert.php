<?php
App::import('Core', 'Controller');
App::import('Component', 'Email');

class AlertShell extends Shell {
  var $uses = array('Schedule');
  var $Email = null;

	function startup() {
		$this->out("");
		$this->out("Alert Shell");
		$this->hr();
		$this->out('App : '. $this->params['app']);
		$this->out('Path: '. $this->params['working']);
		$this->hr();
	}


  function main() {
    $schedules = $this->_findSchedules();

    if (!empty($schedules)) {
      $controller = new Controller();
      $this->Email = new EmailComponent();
      $this->Email->initialize($controller);
      
      foreach ($schedules as $schedule) {
        $this->_send($schedule);
      }
    }
  }

  function _findSchedules() {
    $now = time();
    $from = date('Y-n-j H:00:00', $now);
    $to = date('Y-n-j H:00:00', strtotime('+1 day', $now));
    $conditions = array('from BETWEEN ? AND ?' => array($from, $to));
    $schedules = $this->Schedule->find('all', compact('conditions'));
    
    return $schedules;
  }

  function _send($schedule) {
    foreach ($schedule['User'] as $user) {
      $this->Email->charset = 'iso-2022-jp'; 
      $this->Email->from = 'admin@example.com'; 
      $this->Email->to = $user['email'];
      $this->Email->subject = mb_convert_encoding('[calendarnote] 予定を通知します', 'JIS', 'UTF-8');

      $this->Email->template = 'alert';
      $this->Email->Controller->set('from', $schedule['Schedule']['from']);
      $this->Email->Controller->set('to', $schedule['Schedule']['to']);
      $this->Email->Controller->set('schedule_title', $schedule['Schedule']['title']);
		  $message = $this->Email->__wrap(null);
      $message = $this->Email->__renderTemplate($message);
      $this->Email->template = null;

      $body = mb_convert_encoding(implode("\n", $message), 'JIS', 'UTF-8');
      $this->Email->send($body);
      $this->Email->reset();
    }
  }
}
