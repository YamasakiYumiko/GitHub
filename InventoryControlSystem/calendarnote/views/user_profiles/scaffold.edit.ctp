<?php 
e($appForm->create('User', array('url'=>array('controller'=>'user_profiles','action'=>'edit'))));
e($appForm->hidden('id'));
e($appForm->input('username', array('label'=>__('Login Name',true))));
e($appForm->input('password', array('value'=>'')));
e($appForm->input('confirm_password', array('label'=>__('Password (for a check)',true), 'type'=>'password', 'value'=>'')));
e($appForm->input('fullname'));
e($appForm->input('email'));
e($appForm->input('tel'));
e($appForm->input('memo'));
?>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Preview',true), array('div'=>false)));
?>
</td></tr>
<?php 
e($appForm->end());
?>
