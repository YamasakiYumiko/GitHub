<?php
class DATABASE_CONFIG {

	var $default = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'calendarnote',
		'password' => 'calendarnote',
		'database' => 'calendarnote',
		'encoding' => 'utf8'
	);
	var $test = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'calendarnote',
		'password' => 'calendarnote',
		'database' => 'calendarnote',
		'prefix' => 'test_suite_',
		'encoding' => 'utf8'
	);
}
?>