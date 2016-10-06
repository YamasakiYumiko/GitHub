<?php 
e($appForm->create('Group'));
if ($this->action != 'add'){
	e($appForm->hidden('id'));
}
e($appForm->input('name', array('label'=>__('Group Name',true))));
e($appForm->input('memo'));
e($appForm->permissionSelect($acos, $aros));
?>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Save',true), array('div'=>false)));
if ($this->action != 'add'){
	e($appForm->delete_button('/groups/delete/'.$this->data['Group']['id']));
}
?>
</td></tr>
<?php 
e($appForm->end());
?>
