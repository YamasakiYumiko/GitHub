<?php 
e($appForm->create('Schedule', array('onsubmit'=>"multipleSelectOnSubmit()")));
if ($this->action != 'add'){
	e($appForm->hidden('id'));
}
$date = null;
if(!empty($this->params['named']['date'])) {
	$date = $this->params['named']['date'];
}
e($appForm->input('from', array('selected'=>$date)));
e($appForm->input('to', array('selected'=>$date)));
e($appForm->input('title'));
e($appForm->input('contents'));
$options = array();
if($this->action == 'add') {
	if(empty($this->data)) {
		$options[$loginUser['User']['id']] = $loginUser['User']['fullname'];
		if(!empty($this->params['named']['user'])) {
			$users = $this->getVar('users');
			$options[$this->params['named']['user']] = $users[$this->params['named']['user']];
		}
	}
}
$appForm->fieldset['validates'][] = 'User';
e($appForm->multipleSelectBoxes('User', array('label'=>__('Participant',true), 'multiple'=>'multiple', 'options'=>$options)));
?>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Preview',true), array('div'=>false)));
if ($this->action != 'add'){
	e($appForm->delete_button('/schedules/delete/'.$this->data['Schedule']['id']));
}
?>
</td></tr>
<?php 
e($appForm->end());
?>
