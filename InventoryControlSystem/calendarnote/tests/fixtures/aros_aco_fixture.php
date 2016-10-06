<?php 
class ArosAcoFixture extends CakeTestFixture {
	var $name = 'ArosAco';
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
		'aco_id' => array('type' => 'integer', 'length' => 10, 'null' => false),
		'_create' => array('type' => 'string', 'length' => 2, 'default' => 0),
		'_read' => array('type' => 'string', 'length' => 2, 'default' => 0),
		'_update' => array('type' => 'string', 'length' => 2, 'default' => 0),
		'_delete' => array('type' => 'string', 'length' => 2, 'default' => 0)
	);
	var $records = array(
		array('id' => 1, 'aro_id' => '1', 'aco_id' => '1', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
		array('id' => 2, 'aro_id' => '1', 'aco_id' => '7', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
		array('id' => 3, 'aro_id' => '1', 'aco_id' => '13', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
		array('id' => 4, 'aro_id' => '2', 'aco_id' => '1', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
		array('id' => 5, 'aro_id' => '2', 'aco_id' => '7', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
		array('id' => 6, 'aro_id' => '2', 'aco_id' => '13', '_create' => '1',  '_read' => '1', '_update' => '1', '_delete' => '1'),
	);
}
?>