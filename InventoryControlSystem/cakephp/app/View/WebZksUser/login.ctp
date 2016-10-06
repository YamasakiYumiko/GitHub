<?php echo $this->Form->create(false); ?>
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('WebZksUser'); ?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>ログイン画面</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../../default.css" />
</head>
<body>

<div class="content-wrap">
	<div class="login-form">

		<div id="logo-title">
			<?php echo $this->Html->image('zaiko.png'); ?>
			<h1>WEB在庫管理システム</h1>
		</div>
	    <fieldset>
			<?php echo $this->Flash->render(); ?>
	        <?php echo $this->Form->input('user_cd');		//ここ変えたらエラーになる
	        	  echo $this->Form->input('user_pass');
	        	  echo $this->Form->end(__('Login'));
	    	?>
	    </fieldset>
	</div>
</div>

</body>
</html>




