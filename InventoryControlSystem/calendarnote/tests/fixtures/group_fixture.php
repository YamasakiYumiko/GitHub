<?php 
/* SVN FILE: $Id$ */
/* Group Fixture generated on: 2009-01-14 14:01:55 : 1231911295*/

class GroupFixture extends CakeTestFixture {
	var $name = 'Group';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 50),
			'memo' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
			'id'  => 1,
			'name'  => 'Lorem ipsum dolor sit amet',
			'memo'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-14 14:34:55',
			'updated'  => '2009-01-14 14:34:55'
			),array(
			'id'  => 2,
			'name'  => 'development',
			'memo'  => 'System development team.',
			'created'  => '2009-01-14 14:34:55',
			'updated'  => '2009-01-14 14:34:55'
			),array(
			'id'  => 3,
			'name'  => 'sales',
			'memo'  => 'Sales team.',
			'created'  => '2009-01-14 14:34:55',
			'updated'  => '2009-01-14 14:34:55'
			),
			);
}
?>