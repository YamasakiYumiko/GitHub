<?php e($appForm->create('Schedule')); ?>
		<tr><th><?php __('From'); ?></th>
		<td>
			<?php e(h($appForm->printDatetime($this->data['Schedule']['from']))); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('To'); ?></th>
		<td>
			<?php e(h($appForm->printDatetime($this->data['Schedule']['to']))); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Title'); ?></th>
		<td>
			<?php e(h($this->data['Schedule']['title'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Contents'); ?></th>
		<td>
			<?php e(nl2br(h($this->data['Schedule']['contents']))); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Participant'); ?></th>
		<td><ul>
			<?php foreach($this->data['User']['User'] as $userid){ e('<li>'.h($users[$userid]).'</li>'); } ?>
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
