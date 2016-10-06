<?php 
App::import('Helper', 'Html');
App::import('Helper', 'Form');
App::import('Helper', 'ScheduleTable');
App::import('Model', 'Schedule');

class ScheduleTableTestHelper extends ScheduleTableHelper {
	var $_addSchedule = false;
	function addSchedule($time, $user) {
		if($this->_addSchedule){
			return parent::addSchedule($time, $user);
		}
		return '';
	}
}

class ScheduleTableHelperTest extends CakeTestCase {
	var $fixtures = array('app.group', 'app.user', 'app.users_group', 	'app.schedule', 'app.schedules_user',
		'app.aco', 'app.aro', 'app.aros_aco'
	);
	var $Helper = null;
	var $Model = null;

	function start() {
		parent::start();
		Configure::write('Acl.database', 'test_suite');

		$this->Helper =& new ScheduleTableTestHelper();
		$this->Helper->Html =& new HtmlHelper();
		$this->Helper->Form =& new FormHelper();
		$this->Helper->Form->Html = $this->Helper->Html;
		$this->View =& new View($this->Controller);
		ClassRegistry::addObject('view', $view);
		$this->Model =& new Schedule(array('ds'=>'test_suite'));
	}

	function testJaWeek() {
		$locale = Configure::write('Config.language', 'ja');
		$times = array('from_time'=>mktime(0,0,0,1,1,2009), 'to_time'=>mktime(23,59,59,1,7,2009));
		$schedules = array(array(
			'schedules'=>$this->Model->findByTimes($times, 2), 
			'user'=>$this->Model->User->read(null, 2)
		));
		$result = $this->Helper->week($schedules, $times);
		$expected = array(
			'table' => array('class' => 'week'),
			'<thead',
			'<tr',
			'<td','&nbsp;','/td',
			array('td' => array('class'=>'Thu holiday')),	array('a'=>array('href'=>'/index/day/2009/01/01')),'*/a','/td',
			array('td' => array('class'=>'Fri')),	array('a'=>array('href'=>'/index/day/2009/01/02')),'*/a','/td',
			array('td' => array('class'=>'Sat')),	array('a'=>array('href'=>'/index/day/2009/01/03')),'*/a','/td',
			array('td' => array('class'=>'Sun')),	array('a'=>array('href'=>'/index/day/2009/01/04')),'*/a','/td',
			array('td' => array('class'=>'Mon')),	array('a'=>array('href'=>'/index/day/2009/01/05')),'*/a','/td',
			array('td' => array('class'=>'Tue')),	array('a'=>array('href'=>'/index/day/2009/01/06')),'*/a','/td',
			array('td' => array('class'=>'Wed')),	array('a'=>array('href'=>'/index/day/2009/01/07')),'*/a','/td',
			'/tr',
			'/thead',
			'<tbody',
			'<tr',
			array('td' => array('class'=>'user_name')),'Hidetoshi Nakata','/td',
			array('td' => array('class'=>'Thu holiday')),'*/td',
			array('td' => array('class'=>'Fri')),'&nbsp;','/td',
			array('td' => array('class'=>'Sat')),'&nbsp;','/td',
			array('td' => array('class'=>'Sun')),'&nbsp;','/td',
			array('td' => array('class'=>'Mon')),array('a'=>array('href'=>'/edit/2')),'10:00-12:00 Nengashiki','/a','*/td',
			array('td' => array('class'=>'Tue')),'&nbsp;','/td',
			array('td' => array('class'=>'Wed')),'&nbsp;','/td',
			'/tr',
			'/tbody',
			'/table'
		);
		$this->assertTags($result, $expected,true);
	}
	function testJaDay() {
		$locale = Configure::write('Config.language', 'ja');
		$times = array('from_time'=>mktime(0,0,0,1,8,2009), 'to_time'=>mktime(23,59,59,1,8,2009));
		$schedules = array(array(
			'schedules'=>$this->Model->findByTimes($times, 2), 
			'user'=>$this->Model->User->read(null, 2)
		));
		$result = $this->Helper->day($schedules, $times);
		$expected = array(
			'table' => array('class' => 'day'),
			'<thead',
			array('tr' => array()),
			'<th','&nbsp;','/th',
			array('th' => array('colspan'=>'6')), '0','/th',
			array('th' => array('colspan'=>'6')), '1','/th',
			array('th' => array('colspan'=>'6')), '2','/th',
			array('th' => array('colspan'=>'6')), '3','/th',
			array('th' => array('colspan'=>'6')), '4','/th',
			array('th' => array('colspan'=>'6')), '5','/th',
			array('th' => array('colspan'=>'6')), '6','/th',
			array('th' => array('colspan'=>'6')), '7','/th',
			array('th' => array('colspan'=>'6')), '8','/th',
			array('th' => array('colspan'=>'6')), '9','/th',
			array('th' => array('colspan'=>'6')), '10','/th',
			array('th' => array('colspan'=>'6')), '11','/th',
			array('th' => array('colspan'=>'6')), '12','/th',
			array('th' => array('colspan'=>'6')), '13','/th',
			array('th' => array('colspan'=>'6')), '14','/th',
			array('th' => array('colspan'=>'6')), '15','/th',
			array('th' => array('colspan'=>'6')), '16','/th',
			array('th' => array('colspan'=>'6')), '17','/th',
			array('th' => array('colspan'=>'6')), '18','/th',
			array('th' => array('colspan'=>'6')), '19','/th',
			array('th' => array('colspan'=>'6')), '20','/th',
			array('th' => array('colspan'=>'6')), '21','/th',
			array('th' => array('colspan'=>'6')), '22','/th',
			array('th' => array('colspan'=>'6')), '23','/th',
			'/tr',
			'<tr',
			'<td', '&nbsp;','/td',
			'*/tr',
			'/thead',
			'<tbody',
			'<tr',
			array('td' => array('class'=>'user_name empty')),'Hidetoshi Nakata','/td',
			array('td' => array('colspan'=>60,'class'=>'empty')),'&nbsp;','/td',
			array('td' => array('colspan'=>12)),'10:00-12:00 Pre Sales Meeting','/td',
			array('td' => array('colspan'=>12,'class'=>'empty')),'&nbsp;','/td',
			array('td' => array('colspan'=>12)),'14:00-16:00 Sales','/td',
			array('td' => array('colspan'=>48,'class'=>'empty')),'&nbsp;','/td',
			'/tr',
			'/tbody',
			'/table'
		);
		$this->assertTags($result, $expected,true);
	}
	function testJaMonth() {
		$locale = Configure::write('Config.language', 'ja');
		$times = array('from_time'=>mktime(0,0,0,12,28,2008), 'to_time'=>mktime(23,59,59,1,31,2009));
		$schedules = array(array(
			'schedules'=>$this->Model->findByTimes($times, 2), 
			'user'=>$this->Model->User->read(null, 2)
		));
		$result = $this->Helper->month($schedules, $times);
		$expected = array(
			array('table' => array('class' => 'month')),
			'<tbody',
			'<tr',
			array('td' => array('class'=>'Sun')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2008/12/28')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Mon')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2008/12/29')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Tue')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2008/12/30')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Wed')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2008/12/31')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Thu holiday')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/01')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Fri')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/02')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Sat')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/03')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			'/tr','<tr',
			array('td' => array('class'=>'Sun')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/04')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Mon')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/05')),'*/a','/th','/tr',
				'<tr','<td',
					array('a'=>array('href'=>'/edit/2')),'10:00-12:00 Nengashiki','/a','<br /',
				'/td','/tr','/table','/td',
			array('td' => array('class'=>'Tue')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/06')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Wed')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/07')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Thu')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/08')),'*/a','/th','/tr',
				'<tr','<td',
					array('a'=>array('href'=>'/edit/4')),'10:00-12:00 Pre Sales Meeting','/a','<br /',
					array('a'=>array('href'=>'/edit/3')),'14:00-16:00 Sales','/a','<br /',
				'/td','/tr','/table','/td',
			array('td' => array('class'=>'Fri')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/09')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Sat')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/10')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			'/tr','<tr',
			array('td' => array('class'=>'Sun')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/11')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Mon holiday')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/12')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Tue')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/13')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Wed')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/14')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Thu')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/15')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Fri')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/16')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Sat')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/17')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			'/tr','<tr',
			array('td' => array('class'=>'Sun')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/18')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Mon')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/19')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Tue')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/20')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Wed')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/21')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Thu')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/22')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Fri')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/23')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Sat')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/24')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			'/tr','<tr',
			array('td' => array('class'=>'Sun')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/25')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Mon')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/26')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Tue')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/27')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Wed')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/28')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Thu')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/29')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Fri')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/30')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			array('td' => array('class'=>'Sat')),
				array('table'=>array('class'=>'day_in_month')),
				'<tr','<th',array('a'=>array('href'=>'/index/day/2009/01/31')),'*/a','/th','/tr',
				'<tr','<td','*/td','/tr','/table','/td',
			'/tr',
			'/tbody',
			'/table'
		);
		$this->assertTags($result, $expected,true);
	}	
	function testMonthNavi() {
		$locale = Configure::write('Config.language', 'en');
		$result = $this->Helper->navi('schedules', 'month', '2009/01/07');
		$expected = array(
			'<ul',
			'<li',array('a'=>array('href'=>'/schedules/index/month/2008/12')),'Last month','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/month/'.date('Y/m'))),'This month','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/month/2009/02')),'Next month','/a','/li',
			'/ul'
		);
		$this->assertTags($result, $expected,true);
	}
	function testWeekNavi() {
		$locale = Configure::write('Config.language', 'en');
		$result = $this->Helper->navi('schedules', 'week', '2009/01/07');
		$expected = array(
			'<ul',
			'<li',array('a'=>array('href'=>'/schedules/index/week/2008/12/31')),'Last week','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/week/2009/01/06')),'Previous day','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/week/'.date('Y/m/d'))),'This week','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/week/2009/01/08')),'Next day','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/week/2009/01/14')),'Next week','/a','/li',
			'/ul'
		);
		$this->assertTags($result, $expected,true);
	}
	function testDayNavi() {
		$locale = Configure::write('Config.language', 'en');
		$result = $this->Helper->navi('schedules', 'day', '2009/01/07');
		$expected = array(
			'<ul',
			'<li',array('a'=>array('href'=>'/schedules/index/day/2009/01/06')),'Previous day','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/day/'.date('Y/m/d'))),'Today','/a','/li',
			'<li',array('a'=>array('href'=>'/schedules/index/day/2009/01/08')),'Next day','/a','/li',
			'/ul'
		);
		$this->assertTags($result, $expected,true);
	}
	function testAddScheduleLink() {
		$time = mktime(0,0,0,1,1,2009);
		$user = array('User'=>array('id'=>2));
		$this->Helper->_addSchedule = true;
		$result = $this->Helper->addSchedule($time, $user);
		$expected = array(
			'a'=>array('href'=>"/add/date:2009-01-01 00:00/user:2"),
			'img'=>array('src'=>"img/edit.gif", 'alt'=>""),
			'/a'
		);
		$this->assertTags($result, $expected,true);
	}
	function testGroupSelect() {
		$groups = $this->Model->User->Group->find('list');
		$times = array('from_time'=>mktime(0,0,0,1,1,2009), 'to_time'=>mktime(23,59,59,1,7,2009));
		$result = $this->Helper->group_select($groups, 'week', $times, 2);
		
		$expected = array(
			array('select'=>array('name'=>"data[Groups]", 'onChange'=>"location.href=value;", 'id'=>"Groups")),
			array('option'=>array('value'=>"/schedules/index/week/2009/01/01/group:0")),'Personal Schedule','/option',
			array('option'=>array('value'=>"/schedules/index/week/2009/01/01/group:1")),'Lorem ipsum dolor sit amet','/option',
			array('option'=>array('value'=>"/schedules/index/week/2009/01/01/group:2",'selected'=>"selected")),'development','/option',
			array('option'=>array('value'=>"/schedules/index/week/2009/01/01/group:3")),'sales','/option',
			'/select'
		);
		$this->assertTags($result, $expected,true);
	}
}
?>
