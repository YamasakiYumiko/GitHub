<div id="schedule">
  <div class="title"><?php __('Schedule');?></div>
  <div class="navi">
    <table><tr>
      <td class="group"><?php e($scheduleTable->group_select($groups,$scope,$times,$groupId)); ?></td>
      <td class="date"><?php e(date(__('F j, Y',true), $times['from_time'])); ?></td>
      <td class="menu"><?php e($scheduleTable->navi('schedules', $scope, $current)); ?></td>
    </tr></table>
  </div>
  <div class="main">
<?php 
    echo $html->link(__('Add new schedule', true), array('controller'=>'schedules', 'action'=>'add'));
    echo $scheduleTable->$scope($schedules, $times); 
?>
  <?php echo $appForm->create(null, array('url' => array('action' => 'csv'))); ?>
  <?php echo $appForm->submit(__('csv download', true)); ?>
  <?php echo $appForm->end(); ?>
  </div>
  <div class="scope">
    <ul>
      <li><?php echo $html->link(__('Weeky', true), array('controller'=>'schedules', 'action'=>'index', 'id'=>'week', date('Y/m/d',$times['from_time'])));?></li>
      <li><?php echo $html->link(__('Monthly', true), array('controller'=>'schedules', 'action'=>'index', 'id'=>'month', date('Y/m/d',$times['from_time'])));?></li>
      <li><?php echo $html->link(__('Daily', true), array('controller'=>'schedules', 'action'=>'index', 'id'=>'day', date('Y/m/d',$times['from_time'])));?></li>
    </ul>
  </div>
</div>
