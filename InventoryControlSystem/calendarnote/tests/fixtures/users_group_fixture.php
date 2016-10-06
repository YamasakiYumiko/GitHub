<?php 
/* SVN FILE: $Id$ */
/* User Fixture generated on: 2009-01-14 16:01:43 : 1231918603*/

class UsersGroupFixture extends CakeTestFixture {
	var $name = 'UsersGroup';
	
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'group_id' => array('type'=>'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
			'user_id' => array('type'=>'integer', 'null' => false, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'USER_GROUP_KEY' => array('column' => array('group_id', 'user_id'), 'unique' => 1))
		);
	var $records = array(array(
			'id'  => 1,
			'group_id' => 2,
			'user_id'  => 2
			),array(
			'id'  => 2,
			'group_id' => 2,
			'user_id'  => 3
			));
}
?>