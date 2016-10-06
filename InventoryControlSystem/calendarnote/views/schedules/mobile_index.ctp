<h1>予定表</h1>
<hr />
<div>
  <ul>
  <?php foreach ($schedules as $schedule): ?>
    <?php foreach ($schedule['schedules'] as $v): ?>
      <li>
        <?php echo h($v['Schedule']['from']); ?>～<br />
        <?php echo h($v['Schedule']['to']); ?><br />
        [<?php echo h($v['Schedule']['title']); ?>]<br />
        <br />
      </li>
    <?php endforeach; ?>
  <?php endforeach; ?>
  </ul>
</div>
<hr />
<?php echo $html->link('ログアウト', array('controller' => 'users', 'action' => 'logout')); ?>
