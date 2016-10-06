<?php e($appForm->create('User')); ?>
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
		<tr><th><?php __('Group'); ?></th>
		<td><ul>
			<?php foreach($this->data['Group']['Group'] as $groupid){ e('<li>'.h($groups[$groupid]).'</li>'); } ?>
			</ul>
		</td></tr>
<tr><td colspan="2" class="buttons">
<?php 
e($appForm->submit(__('Save',true), array('div'=>false)));
?>
</td></tr>
<?php 
e($appForm->end());
?>
