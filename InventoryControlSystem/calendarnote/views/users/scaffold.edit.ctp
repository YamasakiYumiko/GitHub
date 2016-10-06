<?php 
e($appForm->create('User'));
if ($this->action != 'add'){
	e($appForm->hidden('id'));
}
e($appForm->input('username', array('label'=>__('Login Name',true))));
e($appForm->input('password', array('value'=>'')));
e($appForm->input('confirm_password', array('label'=>__('Password (for a check)',true), 'type'=>'password', 'value'=>'')));
e($appForm->input('fullname'));
e($appForm->input('email'));
e($appForm->input('tel'));
e($appForm->input('memo'));
$appForm->fieldset['validates'][] = 'Group';
e($appForm->input('Group', array('multiple'=>'checkbox')));
?>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Preview',true), array('div'=>false)));
if ($this->action != 'add'){
	e($appForm->delete_button('/users/delete/'.$this->data['User']['id']));
}
?>
</td></tr>
<?php 
e($appForm->end());
?>
