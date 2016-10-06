<?php 
/* SVN FILE: $Id$ */
/* Schedule Fixture generated on: 2008-12-13 14:12:03 : 1229146983*/

class ScheduleFixture extends CakeTestFixture {
	var $name = 'Schedule';
	var $table = 'schedules';
	var $fields = array(
			'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'length' => 20, 'key' => 'primary'),
			'from' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
			'to' => array('type'=>'datetime', 'null' => false, 'default' => NULL),
			'title' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 100),
			'contents' => array('type'=>'text', 'null' => false, 'default' => NULL),
			'created' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'updated' => array('type'=>'datetime', 'null' => true, 'default' => NULL),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
			);
	var $records = array(array(
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
			),array(
			'id'  => 2,
			'from'  => '2009-01-05 10:00:00',
			'to'  => '2009-01-05 12:00:00',
			'title'  => 'Nengashiki',
			'contents'  => 'In Japan, there are the New Year holidays and a New-Year\'s-greetings ceremony is performed to the first day of work.',
			'created'  => '2008-12-28 14:43:03',
			'updated'  => '2008-12-28 14:43:03'
			),array(
			'id'  => 3,
			'from'  => '2009-01-08 14:00:00',
			'to'  => '2009-01-08 16:00:00',
			'title'  => 'Sales',
			'contents'  => 'Visitor visit.',
			'created'  => '2009-01-05 14:43:03',
			'updated'  => '2009-01-05 14:43:03'
			),array(
			'id'  => 4,
			'from'  => '2009-01-08 10:00:00',
			'to'  => '2009-01-08 12:00:00',
			'title'  => 'Pre Sales Meeting',
			'contents'  => 'Previous Visitor visit.',
			'created'  => '2008-12-29 14:43:03',
			'updated'  => '2008-12-29 14:43:03'
			));
}
?>