<?php
	e($appForm->create('User', array('action' => 'login')));
	e($appForm->input('username', array('label'=>__('Login Name',true))));
	e($appForm->input('password'));
?>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Login',true)));
?>
</td></tr>
<?php 
e($appForm->end());
?>
