<?php 
/* SVN FILE: $Id$ */
/* User Test cases generated on: 2009-01-14 16:01:43 : 1231918603*/
App::import('Model', 'User');
App::import('Model', 'Group');

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);

	function startTest() {
		Configure::write('Acl.database', 'test_suite');
		$this->User =& ClassRegistry::init('User');
		$this->User->Group =& ClassRegistry::init('Group');
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}

	function testUserFind() {
		$this->User->recursive = -1;
		$results = $this->User->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('User' => array(
			'id'  => 1,
			'username'  => 'Lorem ipsum dolor sit amet',
			'password'  => 'Lorem ipsum dolor sit amet',
			'fullname'  => 'Lorem ipsum dolor sit amet',
			'email'  => 'Lorem ipsum dolor sit amet',
			'tel'  => 'Lorem ipsum dolor ',
			'memo'  => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida,phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam,vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit,feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'created'  => '2009-01-14 16:36:43',
			'updated'  => '2009-01-14 16:36:43'
			));
		$this->assertEqual($results, $expected);
	}
	function testValidateNoError() {
		$data = array ('User'=>array(
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertTrue($this->User->validates());
	}

	function testValidateRequireError() {
		$data = array ('User'=>array(
				'username'  => '',
				'password'  => '',
				'confirm_password'  => '',
				'fullname'  => '',
				'email'  => '',
				'tel'  => '',
				'memo' => '',
				),
				'Group'=>array('Group'=>array())
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(5, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("username", $this->User->validationErrors));
		$this->assertTrue(array_key_exists("password", $this->User->validationErrors));
		$this->assertTrue(array_key_exists("fullname", $this->User->validationErrors));
		$this->assertTrue(array_key_exists("email", $this->User->validationErrors));
		$this->assertTrue(array_key_exists("Group", $this->User->validationErrors));
	}
	function testUsernameIsUnique() {
		$data = array ('User'=>array(
				'username'  => 'Lorem ipsum dolor sit amet',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(1, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("username", $this->User->validationErrors));
	}
	function testUsernameIsHarfWidth() {
		$data = array ('User'=>array(
				'username'  => '全角',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(1, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("username", $this->User->validationErrors));
	}
	function testPasswordLengthError() {
		$data = array ('User'=>array(
				'username'  => 'suzuki',
				'password'  => '111',
				'confirm_password'  => '111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(1, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("password", $this->User->validationErrors));
	}
	function testNotEqualPasswordError() {
		$data = array ('User'=>array(
				'username'  => 'suzuki',
				'password'  => '1111111',
				'confirm_password'  => '22222222',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(1, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("password", $this->User->validationErrors));
	}
	function testEmailError() {
		$data = array ('User'=>array(
				'username'  => 'suzuki',
				'password'  => '1111111',
				'confirm_password'  => '1111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);

		$this->assertTrue($this->User->create($data));
		$this->assertFalse($this->User->validates());
		$this->assertEqual(1, count($this->User->validationErrors));
		$this->assertTrue(array_key_exists("email", $this->User->validationErrors));
	}
	function testSetWhiteListByIndexAction() {
		$this->assertTrue(empty($this->User->whitelist));
		
		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->setWhiteList('index', $data);
		
		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByViewAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->setWhiteList('view', $data);

		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByAddGetAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array (	);
		$this->User->setWhiteList('add', $data);

		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByAddPostAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->setWhiteList('add', $data);

		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByEditGetAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array (	);
		$this->User->setWhiteList('edit', $data);

		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByEditPostChangePasswordAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->setWhiteList('edit', $data);

		$this->assertTrue(empty($this->User->whitelist));
	}
	function testSetWhiteListByEditPostNotChangePasswordAction() {
		$this->assertTrue(empty($this->User->whitelist));

		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '',
				'confirm_password'  => '',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->setWhiteList('edit', $data);

		$this->assertEqual(array('id','username','fullname','email','tel','memo'), $this->User->whitelist);
	}
	function testHashPasswords() {
		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->assertEqual($data, $this->User->hashPasswords($data));
	}
	function testBeforeSave() {
		$data = array('User' => array (
				'username'  => 'suzuki',
				'password'  => '111111',
				'confirm_password'  => '111111',
				'fullname'  => 'suzuki tarou',
				'email'  => 'suzuki@test.jp',
				'tel'  => '03-1234-5678',
				'memo' => 'memo ran',
				),
				'Group'=>array('Group'=>array(1))
				);
		$this->User->set($data);
		$this->assertEqual('111111', $this->User->data['User']['password']);
		$this->assertTrue($this->User->beforeSave(array()));
		$this->assertNotEqual('111111', $this->User->data['User']['password']);
	}


	function endTest($method) {
		unset($this->User);
	}
}
?>