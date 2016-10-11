<!DOCTYPE html>
<html lang="ja">
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->meta('icon');
		echo $this->Html->css('style');
		echo $this->Html->css('webzks');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<title><?php echo $this->fetch('title'); ?></title>
</head>
<body>
	<div id="container">
		<div id="content">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</body>
</html>


