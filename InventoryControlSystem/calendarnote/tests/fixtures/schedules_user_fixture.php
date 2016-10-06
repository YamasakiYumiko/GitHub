<?php 
/* SVN FILE: $Id$ */
/* User Fixture generated on: 2009-01-14 16:01:43 : 1231918603*/

class SchedulesUserFixture extends CakeTestFixture {
	var $name = 'SchedulesUser';
	
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'schedule_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
			'user_id' => array('type'=>'integer', 'null' => false, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'SCHEDULE_USER_KEY' => array('column' => array('schedule_id', 'user_id'), 'unique' => 1))
		);
	var $records = array(array(
			'id'  => 1,
			'schedule_id' => 2,
			'user_id'  => 2
			),array(
			'id'  => 2,
			'schedule_id' => 3,
			'user_id'  => 2
			),array(
			'id'  => 3,
			'schedule_id' => 4,
			'user_id'  => 2
			),
			);
}
?>