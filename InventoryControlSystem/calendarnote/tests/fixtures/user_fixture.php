<?php 
/* SVN FILE: $Id$ */
/* User Fixture generated on: 2009-01-14 16:01:43 : 1231918603*/

class UserFixture extends CakeTestFixture {
	var $name = 'User';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'username' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 50),
			'password' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 50),
			'fullname' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 50),
			'email' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 256),
			'tel' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 20),
			'memo' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
			'id'  => 1,
			'username'  => 'Lorem ipsum dolor sit amet',
			'password'  => 'Lorem ipsum dolor sit amet',
			'fullname'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'Lorem ipsum dolor sit amet',
			'tel'  => 'Lorem ipsum dolor ',
			'memo'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-14 16:36:43',
			'updated'  => '2009-01-14 16:36:43'
			),array(
			'id'  => 2,
			'username'  => 'hide',
			'password'  => 'password',
			'fullname'  => 'Hidetoshi Nakata',
			'email'  => 'hide@nakata.com',
			'tel'  => '010-2222-3333',
			'memo'  => '',
			'created'  => '2009-01-14 16:36:43',
			'updated'  => '2009-01-14 16:36:43'
			),array(
			'id'  => 3,
			'username'  => 'shunsuke',
			'password'  => 'password',
			'fullname'  => 'Shunsuke Nakamura',
			'email'  => 'shun@nakamura.com',
			'tel'  => '020-3333-4444',
			'memo'  => '',
			'created'  => '2009-01-14 16:36:43',
			'updated'  => '2009-01-14 16:36:43'
			));
}
?>