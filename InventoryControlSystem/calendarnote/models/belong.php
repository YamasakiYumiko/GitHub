<?php
class Belong extends AppModel {
	var $name = 'Belong';
	var $useTable = 'users_groups';
	var $belongsTo = array('User', 'Group');
}
?>