<?php e($appForm->create('User', array('url'=>array('controller'=>'user_profiles','action'=>'edit')))); ?>
		<tr><th><?php __('Login Name'); ?></th>
		<td>
			<?php e(h($this->data['User']['username'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Fullname'); ?></th>
		<td>
			<?php e(h($this->data['User']['fullname'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Email'); ?></th>
		<td>
			<?php e(h($this->data['User']['email'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Tel'); ?></th>
		<td>
			<?php e(h($this->data['User']['tel'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Memo'); ?></th>
		<td>
			<?php e(nl2br(h($this->data['User']['memo']))); ?>
			&nbsp;
		</td></tr>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Save',true), array('div'=>false)));
?>
</td></tr>
<?php 
e($appForm->end());
?>
