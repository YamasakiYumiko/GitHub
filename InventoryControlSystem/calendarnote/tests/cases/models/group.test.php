<?php 
/* SVN FILE: $Id$ */
/* Group Test cases generated on: 2009-01-14 14:01:55 : 1231911295*/
App::import('Model', 'Group');

class GroupTestCase extends CakeTestCase {
	var $Group = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest() {
		Configure::write('Acl.database', 'test_suite');
		$this->Group =& ClassRegistry::init('Group');
	}

	function testGroupInstance() {
		$this->assertTrue(is_a($this->Group, 'Group'));
	}

	function testGroupFind() {
		$this->Group->recursive = -1;
		$results = $this->Group->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Group' => array(
			'id'  => 1,
			'name'  => 'Lorem ipsum dolor sit amet',
			'memo'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-14 14:34:55',
			'updated'  => '2009-01-14 14:34:55'
			));
		$this->assertEqual($results, $expected);
	}

	function testValidateNoError() {
		$data = array (
				'name' => 'admin',
				'memo' => 'administrator group',
				);

		$this->assertTrue($this->Group->create($data));
		$this->assertTrue($this->Group->validates());
	}

	function testValidateRequireError() {
		$data = array (
				'name' => '',
				'memo' => '',
				);

		$this->assertTrue($this->Group->create($data));
		$this->assertFalse($this->Group->validates());
		$this->assertEqual(2, count($this->Group->validationErrors));
		$this->assertTrue(array_key_exists("name", $this->Group->validationErrors));
		$this->assertTrue(array_key_exists("memo", $this->Group->validationErrors));
	}
	function testNameIsUnique() {
		$data = array (
				'name' => 'Lorem ipsum dolor sit amet',
				'memo' => 'Name shoud be unique',
				);

		$this->assertTrue($this->Group->create($data));
		$this->assertFalse($this->Group->validates());
		$this->assertEqual(1, count($this->Group->validationErrors));
		$this->assertTrue(array_key_exists("name", $this->Group->validationErrors));
	}
}
?>