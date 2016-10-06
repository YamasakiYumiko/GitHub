<?php 
class AcoFixture extends CakeTestFixture {
	var $name = 'Aco';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
			'parent_id' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'model' => array('type'=>'string', 'null' => true),
			'foreign_key' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'alias' => array('type'=>'string', 'null' => true),
			'lft' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'rght' => array('type'=>'integer', 'null' => true, 'default' => NULL, 'length' => 10),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
		);
	var $records = array(
		array('id' => 1, 'parent_id' => NULL,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'groups',	'lft' => 1, 'rght' => 12),
		array('id' => 2, 'parent_id' => 1,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'index',	'lft' => 2, 'rght' => 3),
		array('id' => 3, 'parent_id' => 1,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'view',	'lft' => 4, 'rght' => 5),
		array('id' => 4, 'parent_id' => 1,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'add',		'lft' => 6, 'rght' => 7),
		array('id' => 5, 'parent_id' => 1,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'edit',	'lft' => 8, 'rght' => 9),
		array('id' => 6, 'parent_id' => 1,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'delete',	'lft' => 10,'rght' => 11),
		array('id' => 7, 'parent_id' => NULL,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'schedules','lft' => 13,'rght' => 24),
		array('id' => 8, 'parent_id' => 7,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'index',	'lft' => 14,'rght' => 15),
		array('id' => 9, 'parent_id' => 7,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'view',	'lft' => 16,'rght' => 17),
		array('id' => 10,'parent_id' => 7,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'add',		'lft' => 18,'rght' => 19),
		array('id' => 11,'parent_id' => 7,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'edit',	'lft' => 20,'rght' => 21),
		array('id' => 12,'parent_id' => 7,		'model' => NULL, 'foreign_key' => NULL, 'alias' => 'delete',	'lft' => 22,'rght' => 23),
		array('id' => 13,'parent_id' => NULL,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'users',	'lft' => 25,'rght' => 36),
		array('id' => 14,'parent_id' => 13,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'index',	'lft' => 26,'rght' => 27),
		array('id' => 15,'parent_id' => 13,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'view',	'lft' => 28,'rght' => 29),
		array('id' => 16,'parent_id' => 13,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'add',		'lft' => 30,'rght' => 31),
		array('id' => 17,'parent_id' => 13,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'edit',	'lft' => 32,'rght' => 33),
		array('id' => 18,'parent_id' => 13,	'model' => NULL, 'foreign_key' => NULL, 'alias' => 'delete',	'lft' => 34,'rght' => 35)
	);
}
?>