<?php 
class CalendarnoteSchema extends CakeSchema {
	var $name = 'Calendarnote';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $schedules = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
			'from' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'to' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
			'title' => array('type' => 'string', 'null' => false, 'length' => 100),
			'contents' => array('type' => 'text', 'null' => false, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

	var $users = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'username' => array('type' => 'string', 'null' => false, 'length' => 50),
			'password' => array('type' => 'string', 'null' => false, 'length' => 50),
			'fullname' => array('type' => 'string', 'null' => false, 'length' => 50),
			'email' => array('type' => 'string', 'null' => false, 'length' => 256),
			'tel' => array('type' => 'string', 'null' => false, 'length' => 20),
			'memo' => array('type' => 'text', 'null' => false, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

	var $groups = array(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => false, 'length' => 50),
			'memo' => array('type' => 'text', 'null' => false, 'default' => NULL),
//			'my_id' => array('type'=>'integer', 'null' => true, 'length' => 10, 'default' => NULL),
			'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);

	var $users_groups = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'group_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
			'user_id' => array('type'=>'integer', 'null' => false, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'USER_GROUP_KEY' => array('column' => array('group_id', 'user_id'), 'unique' => 1))
		);

	var $schedules_users = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
			'schedule_id' => array('type'=>'integer', 'null' => false, 'length' => 20, 'key' => 'index'),
			'user_id' => array('type'=>'integer', 'null' => false, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'SCHEDULE_USER_KEY' => array('column' => array('schedule_id', 'user_id'), 'unique' => 1))
		);

}
?>