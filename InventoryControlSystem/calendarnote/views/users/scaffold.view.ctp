<div class="users view">
<h2><?php  __('User');?></h2>
	<table>
		<tr><th><?php __('Login Name'); ?></th>
		<td>
			<?php e(h($user['User']['username'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Fullname'); ?></th>
		<td>
			<?php e(h($user['User']['fullname'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Email'); ?></th>
		<td>
			<?php e(h($user['User']['email'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Tel'); ?></th>
		<td>
			<?php e(h($user['User']['tel'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Memo'); ?></th>
		<td>
			<?php e(nl2br(h($user['User']['memo']))); ?>
			&nbsp;
		</td></tr>
	</table>
</div>
