<!DOCTYPE html>
<html lang="ja">
<head>
<title>在庫管理システム（商品部用）</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="../../default.css" />
</head>
<body>
	<div class ="center">
		<h1>メインメニュー</h1>
		<h2>データ照会</h2>
		<ul>
			<li><a href="./order.php">発注照会</a></li>
			<li><a href="./purchase.php">仕入照会</a></li>
			<li><a href="./transport.php">移動照会</a></li>
			<li><a href="./current_stock.php">在庫照会</a></li>
		</ul>

		<h2>マスタ一覧</h2>
		<ul>
			<li><a href="./mst_office.php">事業所マスタ一覧</a></li>
			<li><a href="./mst_group.php">部門マスタ一覧</a></li>
			<li><a href="./mst_warehouse.php">倉庫マスタ一覧</a></li>
			<li><a href="./mst_staff.php">担当者マスタ一覧</a></li>
			<li><a href="./mst_supplier.php">仕入先マスタ一覧</a></li>
			<li><a href="./mst_payee.php">支払先マスタ一覧</a></li>
			<li><a href="./mst_payment_close_date.php">支払締日マスタ一覧</a></li>
			<li><a href="./mst_custmer.php">得意先マスタ一覧</a></li>
			<li><a href="./mst_hauler.php">配送業者マスタ一覧</a></li>
			<li><a href="./mst_item.php">商品マスタ一覧</a></li>
			<li><a href="./mst_item_price.php">商品単価マスタ一覧</a></li>
			<li><a href="./mst_custmer_item_price.php">社内販売用単価マスタ一覧</a></li>
		</ul>
	</div>
</body>
</html>




<?php echo $this->Html->link('ログアウト', array('action' => 'logout')); ?>