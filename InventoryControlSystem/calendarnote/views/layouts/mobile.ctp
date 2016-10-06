<html>
<head>
	<?php echo $html->charset("utf-8"); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>

<body>
	<div id="container">
		<div id="header">
      <h3><?php __('CalendarNote'); ?></h3>
			<p><?php 
				if($loginUser != null) {
					e(h(sprintf(__('logon:%s',true), $loginUser['User']['fullname'])));
					e('('.$html->link(__('Logout',true), array('controller'=>'users', 'action'=>'logout')).')');
				}
			?>
			</p>
		</div>
		<div id="content">

			<?php
				$session->flash();
				$session->flash('auth');
			?>

			<?php echo $content_for_layout; ?>

		</div>
		<div id="footer">
			<?php echo $html->link(
					$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
					'http://www.cakephp.org/',
					array('target'=>'_blank'), null, false
				);
			?>
		</div>
	</div>
	<?php echo $cakeDebug; ?>
</body>
</html>
