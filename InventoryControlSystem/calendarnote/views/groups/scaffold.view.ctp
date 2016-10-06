<div class="groups view">
<h2><?php  __('Group');?></h2>
	<table>
		<tr><th><?php __('Group Name'); ?></th>
		<td>
			<?php e(h($group['Group']['name'])); ?>
			&nbsp;
		</td></tr>
		<tr><th><?php __('Memo'); ?></th>
		<td>
			<?php e(nl2br(h($group['Group']['memo']))); ?>
			&nbsp;
		</td></tr>
	</table>
</div>
