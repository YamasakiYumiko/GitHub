<?php 
App::import('Model', 'Schedule');

class ScheduleTestCase extends CakeTestCase {
	var $Schedule = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest() {
		Configure::write('Acl.database', 'test_suite');
		$this->Schedule =& ClassRegistry::init('Schedule');
	}

	function testScheduleInstance() {
		$this->assertTrue(is_a($this->Schedule, 'Schedule'));
	}

	function testScheduleFind() {
		$this->Schedule->recursive = -1;
		$results = $this->Schedule->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('Schedule' => array(
			'id'  => 1,
			'from'  => '2008-12-13 14:43:03',
			'to'  => '2008-12-13 14:43:03',
			'title'  => 'Lorem ipsum dolor sit amet',
			'contents'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,
									phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,
									vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,
									feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.
									Orci aliquet, in lorem et velit maecenas luctus, wisi nulla at, mauris nam ut a, lorem et et elit eu.
									Sed dui facilisi, adipiscing mollis lacus congue integer, faucibus consectetuer eros amet sit sit,
									magna dolor posuere. Placeat et, ac occaecat rutrum ante ut fusce. Sit velit sit porttitor non enim purus,
									id semper consectetuer justo enim, nulla etiam quis justo condimentum vel, malesuada ligula arcu. Nisl neque,
									ligula cras suscipit nunc eget, et tellus in varius urna odio est. Fuga urna dis metus euismod laoreet orci,
									litora luctus suspendisse sed id luctus ut. Pede volutpat quam vitae, ut ornare wisi. Velit dis tincidunt,
									pede vel eleifend nec curabitur dui pellentesque, volutpat taciti aliquet vivamus viverra, eget tellus ut
									feugiat lacinia mauris sed, lacinia et felis.',
			'created'  => '2008-12-13 14:43:03',
			'updated'  => '2008-12-13 14:43:03'
			));
		$this->assertEqual($results, $expected);
	}
	function testScheduleFindByTimesWeek() {
		$this->Schedule->recursive = -1;
		$results = $this->Schedule->findByTimes(
			array('from_time'=>mktime(0,0,0,1,1,2009), 'to_time'=>mktime(23,59,59,1,7,2009)), 2
		);
		$this->assertTrue(!empty($results));

		$expected = array(array('Schedule' => array(
			'id'  => 2,
			'from'  => '2009-01-05 10:00:00',
			'to'  => '2009-01-05 12:00:00',
			'title'  => 'Nengashiki',
			'contents'  => 'In Japan, there are the New Year holidays and a New-Year\'s-greetings ceremony is performed to the first day of work.',
			'created'  => '2008-12-28 14:43:03',
			'updated'  => '2008-12-28 14:43:03'
			)));
		// Assert between find result
		$this->assertEqual($results, $expected);
	}
	function testScheduleFindByTimesDay() {
		$this->Schedule->recursive = -1;
		$results = $this->Schedule->findByTimes(
			array('from_time'=>mktime(0,0,0,1,8,2009), 'to_time'=>mktime(23,59,59,1,8,2009)), 2
		);
		$this->assertTrue(!empty($results));

		$expected = array(array('Schedule' => array(
			'id'  => 4,
			'from'  => '2009-01-08 10:00:00',
			'to'  => '2009-01-08 12:00:00',
			'title'  => 'Pre Sales Meeting',
			'contents'  => 'Previous Visitor visit.',
			'created'  => '2008-12-29 14:43:03',
			'updated'  => '2008-12-29 14:43:03'
			)),
			array('Schedule' => array(
			'id'  => 3,
			'from'  => '2009-01-08 14:00:00',
			'to'  => '2009-01-08 16:00:00',
			'title'  => 'Sales',
			'contents'  => 'Visitor visit.',
			'created'  => '2009-01-05 14:43:03',
			'updated'  => '2009-01-05 14:43:03'
			)));
		// Assert between find & sort result
		$this->assertEqual($results, $expected);
	}
	function testValidateNoError() {
		$data = array ('Schedule'=>array(
				'from'  => '2009-01-10 14:00:00',
				'to'  => '2009-01-10 16:00:00',
				'title'  => 'Add Unique Schedule',
				'contents'  => 'Add Unique Schedule Contents.'
			),
				'User'=>array('User'=>array(2))
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertTrue($this->Schedule->validates());
	}
	function testValidateRequireError() {
		$data = array ('Schedule'=>array(
				'from'  => '',
				'to'  => '',
				'title'  => '',
				'contents'  => ''
			),
				'User'=>array('User'=>array())
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertFalse($this->Schedule->validates());
		$this->assertEqual(4, count($this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("from", $this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("to", $this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("title", $this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("User", $this->Schedule->validationErrors));
	}
	function testValidateFromFormatError() {
		$data = array ('Schedule'=>array(
				'from'  => '2009-ab-10 14:00:00',
				'to'  => '2009-01-10 16:00:00',
				'title'  => 'Add Unique Schedule',
				'contents'  => 'Add Unique Schedule Contents.'
			),
				'User'=>array('User'=>array(2))
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertFalse($this->Schedule->validates());
		$this->assertEqual(1, count($this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("from", $this->Schedule->validationErrors));
	}
	function testValidateToFormatError() {
		$data = array ('Schedule'=>array(
				'from'  => '2009-01-10 14:00:00',
				'to'  => '2009-01-ab 16:00:00',
				'title'  => 'Add Unique Schedule',
				'contents'  => 'Add Unique Schedule Contents.'
			),
				'User'=>array('User'=>array(2))
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertFalse($this->Schedule->validates());
		// Include sould be from < to validate error
		$this->assertEqual(2, count($this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("to", $this->Schedule->validationErrors));
	}
	function testValidateFromToRangeError() {
		$data = array ('Schedule'=>array(
				'from'  => '2009-01-10 16:00:00',
				'to'  => '2009-01-10 14:00:00',
				'title'  => 'Add Unique Schedule',
				'contents'  => 'Add Unique Schedule Contents.'
			),
				'User'=>array('User'=>array(2))
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertFalse($this->Schedule->validates());
		$this->assertEqual(1, count($this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("from", $this->Schedule->validationErrors));
	}
	function testValidateFromToDuplicateError() {
		$data = array ('Schedule'=>array(
				'from'  => '2009-01-08 13:00:00',
				'to'  => '2009-01-08 15:00:00',
				'title'  => 'Add Not Unique Schedule',
				'contents'  => 'Add Not Unique Schedule Contents.'
			),
				'User'=>array('User'=>array(2))
		);
		$this->assertTrue($this->Schedule->create($data));
		$this->assertFalse($this->Schedule->validates());
		$this->assertEqual(1, count($this->Schedule->validationErrors));
		$this->assertTrue(array_key_exists("from", $this->Schedule->validationErrors));
	}
}
?>