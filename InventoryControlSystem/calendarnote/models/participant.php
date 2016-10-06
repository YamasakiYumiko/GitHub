<?php
class Participant extends AppModel {
	var $name = 'Participant';
	var $useTable = 'schedules_users';
	var $belongsTo = array('Schedule', 'User');
}

?>