<?php 
class AroFixture extends CakeTestFixture {
	var $name = 'Aro';
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'model' => array('type' => 'string', 'default' => ''),
		'foreign_key' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'alias' => array('type' => 'string', 'default' => ''),
		'lft' => array('type' => 'integer', 'length' => 10, 'null' => true),
		'rght' => array('type' => 'integer', 'length' => 10, 'null' => true)
	);
	var $records = array(
		array('parent_id' => null, 'model' => 'Group', 'foreign_key' => 1, 'alias' => null, 'lft' => 1, 'rght' => 2),
		array('parent_id' => null, 'model' => 'Group', 'foreign_key' => 2, 'alias' => null, 'lft' => 2, 'rght' => 3),
	);
}
?>