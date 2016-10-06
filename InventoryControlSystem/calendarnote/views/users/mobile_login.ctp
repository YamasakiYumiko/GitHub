<?php echo $form->create('User', array('action' => 'login')); ?>
<?php echo $form->label(__('Login Name',true)); ?><br />
<?php echo $form->input('username', array('label'=> false, 'div' => false)); ?><br />
<?php echo $form->label('password'); ?><br />
<?php echo $form->input('password', array('label' => false, 'div' => false)); ?><br />
<?php echo $form->submit(__('Login',true), array('div' => false)); ?>
<?php echo $form->end(); ?>

