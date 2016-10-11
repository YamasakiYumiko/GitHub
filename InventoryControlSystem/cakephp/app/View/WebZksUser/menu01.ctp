<!DOCTYPE html>
<html lang="ja">
<head>
<title>在庫管理システム（店舗用）</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../../default.css" />
</head>
<body>
	<div class ="center">
		<h1>メインメニュー</h1>
		<h2>データ照会</h2>
		<ul>
			<li><?php echo $this->Html->link( '発注照会',array('controller' => 'Order','action' => 'orderList','target' => '_blank'));?></li>
			<li><a href="./purchase_s.php">仕入照会</a></li>
			<li><a href="./transport_s.php">移動照会</a></li>
			<li><a href="./current_stock_s.php">在庫照会</a></li>
		</ul>

		<h2>マスタ一覧</h2>
		<ul>
			<li><a href="./mst_supplier_s.php">仕入先マスタ一覧</a></li>
			<li><a href="./mst_item_s.php">商品マスタ一覧</a></li>
		</ul>

		<?php echo $this->Html->link('ログアウト', array('action' => 'logout')); ?>
	</div>
</body>
</html>

