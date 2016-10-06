<?php
	$actions = $this->requestAction('user_profiles/allowActions/'.$loginUser['User']['id']);
	$menus = array(
		'Private Settings'=>array('controller'=>'user_profiles', 'action'=>'edit'),
		'Schedule Management'=>array('controller'=>'schedules', 'action'=>'index'),
		'User Management'=>array('controller'=>'users', 'action'=>'index'),
		'Group Management'=>array('controller'=>'groups', 'action'=>'index'),
	);
?>
<ul class="menu">
<?php
	foreach($menus as $name=>$url):
		if(!in_array($url['controller'].'/'.$url['action'], $actions) && $url['controller'] != 'user_profiles') {
			continue;
		}
?>
<li><?php e($html->link(__($name, true), $url)); ?></li>
<?php endforeach; ?>
</ul>
