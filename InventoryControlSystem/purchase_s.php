<?php
	// 並び替え順序文字
	$order_by = ( string ) stripslashes ( @$_POST ['order_by'] ); // シングルクォートを勝手に書き換えないようにstripslashesを使用
	if ($order_by == null) {
		$order_by = "p.purchase_slip_no";
	}

	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);

	$warehouse_cd1 = (string) @$_POST['warehouse_cd1'];

	$wrk_str = (string) @$_POST['purchase_ymd1'];
	$purchase_ymd1 = $wrk_str;

	$wrk_str = (string) @$_POST['purchase_ymd2'];
	$purchase_ymd2 = $wrk_str;

	$wrk_str = (string) @$_POST['stock_ymd1'];
	$stock_ymd1 = $wrk_str;

	$wrk_str = (string) @$_POST['stock_ymd2'];
	$stock_ymd2 = $wrk_str;

	$wrk_str = (string) @$_POST['slip_no1'];
	$slip_no1 = $wrk_str;

	$wrk_str = (string) @$_POST['slip_no2'];
	$slip_no2 = $wrk_str;

	$wrk_str = (string) @$_POST['item_cd1'];
	$item_cd1 = $wrk_str;

	$wrk_str = (string) @$_POST['item_cd2'];
	$item_cd2 = $wrk_str;

	$item_name1 = (string) @$_POST['item_name1'];

	$jan_cd1 = (string) @$_POST['jan_cd1'];

	$supplier_cd1 = (string) @$_POST['supplier_cd1'];
	$supplier_name1 = (string) @$_POST['supplier_name1'];

	$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$arr1 = array(
			"row_no"
			,"slip_no"
	//				,"purchase_kubun_cd"
	//				,"office_cd"
	//				,"warehouse_cd"
	//				,"warehouse_name"
			,"stock_ymd"
	//				,"acceptance_ymd"
			,"purchase_ymd"
	//				,"staff_cd"
	//				,"staff_name"
			,"supplier_cd"
			,"supplier_name"
	//				,"payee_cd"
	//				,"payee_name"
	//				,"payment_kubun_cd"
	//				,"payment_date"
	//				,"payment_cd"
	//				,"payment_name"
	//				,"supplier_order_no"
	//				,"demand_no"
	//				,"hauler_cd"
	//				,"hauler_name"
	//				,"slip_summary"
	//				,"created_cd"
	//				,"created_timestamp"
	//				,"modified_cd"
	//				,"modified_timestamp"
	//				,"d_slip_no"
			,"d_detail_line_no"
	//				,"d_detail_purchase_kubun_cd"
	//				,"d_debt_kubun_cd"
	//				,"d_detail_warehouse_cd"
			,"d_item_cd"
			,"d_item_name"
			,"d_jan_cd"
			,"d_model_name"
	//				,"d_tax_rate_kubun_cd"
	//				,"d_unit_quantity"
	//				,"d_single_quantity"
			,"d_quantity"
	//				,"d_supplier_price"
	//				,"d_supplier_amount"
	//				,"d_supplier_consumption_tax"
	//				,"d_supplier_order_no"
	//				,"d_stock_ymd"
	//				,"d_detail_summary"
	//				,"d_created_cd"
	//				,"d_created_timestamp"
	//				,"d_modified_cd"
	//				,"d_modified_timestamp"
			,"warehouse_name"
			,"order_slip_no"

	);

	$arr2 = array(
			""
			,"伝票番号"
	//				,"仕入区分"
	//				,"事業所CD"
	//				,"倉庫CD"
	//				,"倉庫名"
			,"入荷日"
	//				,"検収日"
			,"仕入日"
	//				,"担当者CD"
	//				,"担当者名"
			,"仕入先CD"
			,"仕入先名"
	//				,"支払先CD"
	//				,"支払先名"
	//				,"支払帳端区分"
	//				,"支払予定日"
	//				,"支払方法CD"
	//				,"支払方法"
	//				,"仕入先注文番号"
	//				,"案件番号"
	//				,"配送業者CD"
	//				,"配送業者名"
			,"伝票摘要"
	//				,"作成担当者CD"
	//				,"作成日時"
	//				,"更新担当者CD"
	//				,"更新日時"
	//				,"伝票番号"
			,"行"	//,"明細行番号"
	//				,"明細発注区分"
	//				,"債務科目区分"
	//				,"明細倉庫CD"
			,"商品CD"
			,"商品名"
			,"商品名補足"	//,"規格"
	//				,"税率区分"
	//				,"明細荷数"
	//				,"明細バラ数"
			,"仕入数"
	//				,"仕入単価"
	//				,"仕入金額"
	//				,"仕入消費税額"
	//				,"明細仕入先注文番号"
	//				,"明細入荷日"
	//				,"明細摘要"
	//				,"作成担当者CD"
	//				,"作成日時"
	//				,"更新担当者CD"
	//				,"更新日時"
			,"倉庫名"
			,"発注伝票番号"

	);


	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM tbl_zks_obic_purchase_detail
QUERY_EOD;

	// クエリ作成
	$query3 = <<<QUERY_EOD
	SELECT
		dept_cd AS warehouse_cd,
		shop_short_name AS warehouse_name
	FROM mst_shop_for_tool
	WHERE
		dept_cd NOT IN (
			'0101',
			'0202',
			'0203',
			'0206',
			'0208',
			'0214',
			'0301',
			'0302',
			'0303',
			'0304',
			'0305',
			'0401'
		)
	ORDER BY warehouse_cd
QUERY_EOD;

	// クエリ作成
	$query4 = <<<QUERY_EOD
	SELECT
		dept_cd,
		shop_short_name,
		sv_ip
	FROM mst_shop_for_tool
	WHERE
		sv_ip = '$sv_ip';
QUERY_EOD;

	// クエリ作成
	$wrk_query = <<<QUERY_EOD
	SELECT
		ROW_NUMBER() OVER(ORDER BY $order_by) AS row_no,
		p.purchase_slip_no AS slip_no,
		p.purchase_kbn AS purchase_kubun_cd,
		p.office_cd AS office_cd,
		p.warehouse_cd AS warehouse_cd,
		ms.shop_short_name AS warehouse_name,
		p.order_slip_no AS order_slip_no,
		p.arrival_ymd AS stock_ymd,
		p.acceptance_ymd AS acceptance_ymd,
		p.purchase_ymd AS purchase_ymd,
		p.suppiler_staff_cd AS staff_cd,
		mst.staff_name AS staff_name,
		p.supplier_cd AS supplier_cd,
		msp.supplier_name AS supplier_name,
		p.payee_cd AS payee_cd,
		mpy.payee_name AS payee_name,
		p.payment_kbn AS payment_kubun_cd,
		p.payment_date AS payment_date,
		p.payment_cd AS payment_cd,
		mpm.payment_name AS payment_name,
		p.supplier_order_no AS supplier_order_no,
		p.demand_no AS demand_no,
		p.hauler_cd AS hauler_cd,
		mh.hauler_name AS hauler_name,
		p.slip_remarks AS slip_summary,
		p.created_cd AS created_cd,
		p.created_timestamp AS created_timestamp,
		p.modified_cd AS modified_cd,
		p.modified_timestamp AS modified_timestamp,
		pd.purchase_slip_no AS d_slip_no,
		pd.purchase_detail_no AS d_detail_line_no,
		pd.purchase_kbn AS d_detail_purchase_kubun_cd,
		pd.debt_kbn AS d_debt_kubun_cd,
		pd.warehouse_cd AS d_detail_warehouse_cd,
		pd.item_cd AS d_item_cd,
		mi.item_name AS d_item_name,
		mi.jan_cd AS d_jan_cd,
		pd.model_name AS d_model_name,
		pd.tax_rate_kbn AS d_tax_rate_kubun_cd,
		CAST(pd.unit_qty AS INTEGER) AS d_unit_quantity,
		CAST(pd.single_qty AS INTEGER) AS d_single_quantity,
		TO_CHAR(CAST(pd.purchase_qty AS INTEGER), 'FM9,999,999,990') AS d_quantity,
		REPLACE(TO_CHAR(pd.purchase_cost, 'FM9,999,999,990.00'), '.00', '&nbsp;&nbsp;&nbsp;') AS d_supplier_price,
		TO_CHAR(CAST(pd.purchase_amount AS INTEGER), 'FM9,999,999,990') AS d_supplier_amount,
		CAST(pd.purchase_consumption_tax AS INTEGER) AS d_supplier_consumption_tax,
		pd.supplier_order_no AS d_supplier_order_no,
		pd.purchase_ymd AS d_stock_ymd,
		pd.remarks AS d_detail_summary,
		pd.created_cd AS d_created_cd,
		pd.created_timestamp AS d_created_timestamp,
		pd.modified_cd AS d_modified_cd,
		pd.modified_timestamp AS d_modified_timestamp
QUERY_EOD;

	$query = <<<QUERY_EOD
	FROM tbl_zks_obic_purchase as p
	INNER JOIN tbl_zks_obic_purchase_detail AS pd ON
		pd.purchase_slip_no = p.purchase_slip_no
	LEFT JOIN mst_shop_for_tool AS ms ON
		ms.dept_cd = p.warehouse_cd
	LEFT JOIN (
		SELECT
			m1.staff_cd,
			m1.staff_name
		FROM (
			SELECT
				staff_cd,
				MAX(revision_date) AS revision_date
			FROM mst_obic_staff
			GROUP BY staff_cd
		) AS m0
		INNER JOIN mst_obic_staff AS m1 ON
			m1.staff_cd = m0.staff_cd AND
			m1.revision_date = m0.revision_date
	) AS mst ON
		mst.staff_cd = p.suppiler_staff_cd
	LEFT JOIN mst_obic_supplier AS msp ON
		msp.supplier_cd = p.supplier_cd
	LEFT JOIN mst_obic_payee AS mpy ON
		mpy.payee_cd = p.payee_cd
	LEFT JOIN mst_obic_payment AS mpm ON
		mpm.payment_cd = p.payment_cd
	LEFT JOIN mst_obic_hauler AS mh ON
		mh.hauler_cd = p.hauler_cd
	LEFT JOIN mst_obic_item AS mi ON
		mi.item_cd = pd.item_cd
	WHERE
		mi.item_cd LIKE 'D%'
		AND p.purchase_ymd >= '$min_day'
QUERY_EOD;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<title>在庫管理システム（店舗用）</title>
<meta charset="UTF-8">
<script type="text/javascript" src="./default.js" charset=UTF-8></script>
<link rel="stylesheet" type="text/css" href="./default.css" />
	<?php include(dirname(__FILE__) . "/common.php"); ?>
</head>
<body>

	<h2>仕入照会（店舗用）</h2>

	<div id="wrapper">
		<div id="headWrap">
			<?php
			foreach ( $conn_array as $key => $value ) {
				$res = @pg_query ( $value, $query2 );
				if ($res) {

					while ( $row = pg_fetch_assoc ( $res ) ) {
						$output_timestamp = ( string ) $row ['output_timestamp'];

						echo "<Div Align = \"right\"><b>更新日時：" . $output_timestamp . "</b></Div>";
					}
				}
			}
			?>
			<input type="button" value="メニューへ戻る"
				onclick="location.href='./main.html'">
		</div>

		<div id="container">
			<div class="searchbox">

				<form method="POST" action="" name="form1">
					<table style="border-style: none">
						<tr>
							<th>倉庫</th>
							<?php
							if ($sv_ip == "192.168.1.50") {
								if ($warehouse_cd1 == "") {
									$warehouse_cd1 = "0000";
								}
								echo "<td><select name=\"warehouse_cd1\" onkeypress=\"return EnterFocus(this, 'supplier_cd1')\">";
								echo "<option value=\"0000\">0000 すべて</option>\n";

								foreach ( $conn_array as $key => $value ) {
									$res = @pg_query ( $value, $query3 );
									if ($res) {

										while ( $row = pg_fetch_assoc ( $res ) ) {
											$warehouse_cd = ( string ) $row ['warehouse_cd'];
											$warehouse_name = ( string ) $row ['warehouse_name'];

											$echo_str = "<option value=\"" . $warehouse_cd . "\"";
											if ($warehouse_cd1 == $warehouse_cd) {
												$echo_str .= " selected";
											}

											$echo_str .= ">" . $warehouse_cd . " " . $warehouse_name . "</option>\n";
											echo "$echo_str";
										}
										echo "</select></td>";
									}
								}
							} else {

								foreach ( $conn_array as $key => $value ) {
									$res = @pg_query ( $value, $query4 );
									if ($res) {

										while ( $row = pg_fetch_assoc ( $res ) ) {
											$shop_cd = ( string ) $row ['dept_cd'];
											$shop_name = ( string ) $row ['shop_short_name'];
										}
									}
								}

								if ($warehouse_cd1 == "") {
									$warehouse_cd1 = $shop_cd;
								}

								if (($shop_cd == "0209") or // 福岡港店
									($shop_cd == "0219") or // 福岡博多店
									($shop_cd == "0903")) // 楽一福岡イオン店
								{
									$arr_rnj1 = array (
											// "0000",
											$shop_cd,
											"0201",
											"0218",
											"0220",
											"0216"
									);

									$arr_rnj2 = array (
											// "すべて",
											$shop_name,
											"別府店",
											"大村店",
											"熊本店",
											"臨時店"
									);

									$wrk_str1 = $warehouse_cd1;
									echo "<td><select name=\"warehouse_cd1\" onkeypress=\"return EnterFocus(this, 'supplier_cd1')\">";

									for($count = 0; $count < count ( $arr_rnj1 ); $count ++) {
										$wrk_str = "<option value=\"" . $arr_rnj1 [$count] . "\"";
										if ($arr_rnj1 [$count] == $wrk_str1) {
											$wrk_str .= " selected";
										}
										$wrk_str .= ">" . $arr_rnj1 [$count] . " " . $arr_rnj2 [$count] . "</option>\\n";

										echo $wrk_str;
									}

									echo "</select></td>";
								} else {
									$warehouse_cd1 = $shop_cd;
									$warehouse_name = $shop_name;
									echo "<td>" . $warehouse_cd1 . " " . $warehouse_name . "</td>";
								}
							}
							?>

							<th>仕入先CD</th>
							<td><input type="text" name="supplier_cd1"
								value="<?php echo $supplier_cd1; ?>" maxlength="10"
								onkeypress="return EnterFocusCD(this, 'slip_no1', 'supplier_cd1', '', 10, true)"
								onblur="return FocusCD(this, 'slip_no1', 'supplier_cd1', '', 10, true)" /></td>
						</tr>
						<tr>
							<th>伝票NO</th>
							<td>
<!--
								<input type="text" name="slip_no1"
									value="<?php //echo $slip_no1; ?>" maxlength="12"
									onkeypress="return EnterFocusCD(this, 'slip_no2', 'slip_no1', 'F', 12, true)"
									onblur="return FocusCD(this, 'slip_no2', 'slip_no1', 'F', 12, true)" />
									~ <input type="text" name="slip_no2"
									value="<?php //echo $slip_no2; ?>" maxlength="12"
									onkeypress="return EnterFocusCD(this, 'purchase_ymd1', 'slip_no2', 'F', 12, true)"
									onblur="return FocusCD(this, 'purchase_ymd1', 'slip_no2', 'F', 12, true)" />
-->

								<input type="text" name="slip_no1" value="<?php echo $slip_no1; ?>" maxlength="12" onkeypress="return EnterFocus(this, 'slip_no2')" />
								~ <input type="text" name="slip_no2" value="<?php echo $slip_no2; ?>" maxlength="12" onkeypress="return EnterFocus(this, 'supplier_name1')" />
							</td>
							<th>仕入先名</th>
							<td>
								<input type="text" name="supplier_name1" value="<?php echo $supplier_name1; ?>" onkeypress="return EnterFocus(this, 'purchase_ymd1')" />
							</td>
						</tr>
						<tr>
							<th>仕入日</th>
							<td><input type="text" name="purchase_ymd1"
								value="<?php echo $purchase_ymd1; ?>" maxlength="10"
								onkeypress="return EnterFocusDate(this, 'purchase_ymd2', 'purchase_ymd1', '')"
								onblur="return FocusDate(this, 'purchase_ymd2', 'purchase_ymd1', '')" />
								~ <input type="text" name="purchase_ymd2"
								value="<?php echo $purchase_ymd2; ?>" maxlength="10"
								onkeypress="return EnterFocusDate(this, 'item_cd1', 'purchase_ymd2', '')"
								onblur="return FocusDate(this, 'item_cd1', 'purchase_ymd2', '')" /></td>
							<th>商品CD</th>
							<td><input type="text" name="item_cd1"
								value="<?php echo $item_cd1; ?>" maxlength="7"
								onkeypress="return EnterFocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)"
								onblur="return FocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)" />
								~ <input type="text" name="item_cd2"
								value="<?php echo $item_cd2; ?>" maxlength="7"
								onkeypress="return EnterFocusCD(this, 'stock_ymd1', 'item_cd2', 'D', 7, false)"
								onblur="return FocusCD(this, 'stock_ymd1', 'item_cd2', 'D', 7, false)" /></td>
						</tr>
						<tr>
							<th>入荷日</th>
							<td><input type="text" name="stock_ymd1"
								value="<?php echo $stock_ymd1; ?>" maxlength="10"
								onkeypress="return EnterFocusDate(this, 'stock_ymd2', 'stock_ymd1', '')"
								onblur="return FocusDate(this, 'stock_ymd2', 'stock_ymd1', '')" />
								~ <input type="text" name="stock_ymd2"
								value="<?php echo $stock_ymd2; ?>" maxlength="10"
								onkeypress="return EnterFocusDate(this, 'item_name1', 'stock_ymd2', '')"
								onblur="return FocusDate(this, 'item_name1', 'stock_ymd2', '')" /></td>
							<th>商品名</th>
							<td><input type="text" name="item_name1"
								value="<?php echo $item_name1; ?>"
								onkeypress="return EnterFocus(this, 'jan_cd1')" /></td>
						</tr>
						<tr>
							<th>JANCD</th>
							<td><input type="text" name="jan_cd1"
								value="<?php echo $jan_cd1; ?>" maxlength="13"
								onkeypress="return EnterFocusCD(this, 'interval', 'jan_cd1', '', 13, false)"
								onblur="return FocusCD(this, 'interval', 'jan_cd1', '', 13, false)" /></td>
							<th>表示件数</th>
							<td><select name="interval"
								onkeypress="return EnterFocus(this, 'button')">
								<?php
								foreach ( $interval_array as $val ) {
									$echo_str = "<option value=$val";

									if ($val == $interval) {
										$echo_str .= " selected";
									}

									$echo_str .= ">$val</option>\n";

									echo "$echo_str";
								}
								?>
								</select></td>
						</tr>
					</table>

					<input type="hidden" name="page" value="<?php echo $page; ?>" /> <input
						type="hidden" name="order_by" value="<?php echo $order_by; ?>" />
					<input type="submit" value="更新" name="button" /> <input
						type="submit" value="前へ" name="button_pre" /> <input type="submit"
						value="次へ" name="button_next" />

					<?php

						// 倉庫CD
					if ($sv_ip == "192.168.1.50") {
						if ($warehouse_cd1 != '0000' && $warehouse_cd1 != '') {
							$query = $query . <<<QUERY_EOD
					AND p.warehouse_cd = '$warehouse_cd1'
QUERY_EOD;
						}
					} else {
						// if ($warehouse_cd1 == "0000") {
						// $query .= " AND p.warehouse_cd IN (";
						//
						// foreach($arr_rnj1 as $val) {
						// if ($val != "0000") {
						// $query .= "'".$val."',";
						// }
						// }
						//
						// $query .= "'')";
						// } else {
						$query = $query . <<<QUERY_EOD
					AND p.warehouse_cd = '$warehouse_cd1'
QUERY_EOD;
						// }
					}

					// 仕入日
					if ($purchase_ymd1 != '' && $purchase_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_ymd BETWEEN '$purchase_ymd1' AND '$purchase_ymd2'
QUERY_EOD;
					} else if ($purchase_ymd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_ymd >= '$purchase_ymd1'
QUERY_EOD;
					} else if ($purchase_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_ymd <= '$purchase_ymd2'
QUERY_EOD;
					}

						// 入荷日
					if ($stock_ymd1 != '' && $stock_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.arrival_ymd BETWEEN '$stock_ymd1' AND '$stock_ymd2'
QUERY_EOD;
					} else if ($stock_ymd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.arrival_ymd >= '$stock_ymd1'
QUERY_EOD;
					} else if ($stock_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.arrival_ymd <= '$stock_ymd2'
QUERY_EOD;
					}

						// 伝票番号
					if ($slip_no1 != '' && $slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_slip_no BETWEEN '$slip_no1' AND '$slip_no2'
QUERY_EOD;
					} else if ($slip_no1 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_slip_no >= '$slip_no1'
QUERY_EOD;
					} else if ($slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.purchase_slip_no <= '$slip_no2'
QUERY_EOD;
					}

					// 仕入先CD
					if ($supplier_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND p.supplier_cd = '$supplier_cd1'
QUERY_EOD;
					}

					// 仕入先名
					if ($supplier_name1 != '') {
						$query = $query . <<<QUERY_EOD
					AND msp.supplier_name LIKE '%$supplier_name1%'
QUERY_EOD;
					}

						// 商品CD
					if ($item_cd1 != '' && $item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND (pd.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(pd.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND (pd.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(pd.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND (pd.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(pd.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					}

					// JANCD
					if ($jan_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND mi.jan_cd = '$jan_cd1'
QUERY_EOD;
					}
					// 商品名
					if ($item_name1 != '') {
						$query = $query . <<<QUERY_EOD
					AND mi.item_name LIKE '%$item_name1%'
QUERY_EOD;
					}

					$query1 = "SELECT COUNT(*) AS x_count " . $query;

					$query = $wrk_query . $query;

					$query = $query . <<<QUERY_EOD
					ORDER BY $order_by
QUERY_EOD;

					$query0 = $query;

					$query = $query . <<<QUERY_EOD
					LIMIT $interval OFFSET ($page - 1) * $interval
QUERY_EOD;
						// QUERY_EODの前には空白などを含めない
					foreach ( $conn_array as $key => $value ) {
						$res = @pg_query ( $value, $query1 );
						if (! $res) {
							// echo "エラー!! 入力項目が正しくありません。";
							// echo "Cannot execute SQL($query1)";
							echo "<input type='hidden' name='page_all' value=\"1\" />";
						} else {
							while ( $row = pg_fetch_assoc ( $res ) ) {
								$x_count = ( string ) $row ['x_count'];
								$page_all = ( string ) (floor ( ( int ) $x_count / ( int ) $interval ) + 1);
							}

							echo "<input type='hidden' name='page_all' value=\"" . $page_all . "\" />";
						}
					}
					?>
				</form>
			</div>

			<form method="POST" action="download.php" target="_blank">
				<input type="hidden" name="page_name" value="<?php echo "$page_name"; ?>" />
				<input type="hidden" name="query" value="<?php echo "$query0"; ?>" />

				<?php
				foreach ( $arr1 as $val ) {
					echo "<input type='hidden' name='arr1[]' value='" . $val . "' />";
				}
				foreach ( $arr2 as $val ) {
					echo "<input type='hidden' name='arr2[]' value='" . $val . "' />";
				}
				?>

				<input type="submit" value="ダウンロード" name="button_dl" />
			</form>

			<ul>
				<li><b>全件数：</b><b><?php echo "$x_count"; ?>件</b></li>
				<li><b>ページ数：</b><b><?php echo "$page"."／"."$page_all"; ?></b></li>
			</ul>

			<div class="resultbox">
				<form method="POST" action="">
					<input type="hidden" name="page" value="<?php echo "$page"; ?>" />
					<input type="hidden" name="interval"
						value="<?php echo "$interval"; ?>" /> <input type="hidden"
						name="warehouse_cd1" value="<?php echo "$warehouse_cd1"; ?>" /> <input
						type="hidden" name="purchase_ymd1"
						value="<?php echo "$purchase_ymd1"; ?>" /> <input type="hidden"
						name="purchase_ymd2" value="<?php echo "$purchase_ymd2"; ?>" /> <input
						type="hidden" name="stock_ymd1"
						value="<?php echo "$stock_ymd1"; ?>" /> <input type="hidden"
						name="stock_ymd2" value="<?php echo "$stock_ymd2"; ?>" /> <input
						type="hidden" name="slip_no1" value="<?php echo "$slip_no1"; ?>" />
					<input type="hidden" name="slip_no2"
						value="<?php echo "$slip_no2"; ?>" /> <input type="hidden"
						name="item_cd1" value="<?php echo "$item_cd1"; ?>" /> <input
						type="hidden" name="item_cd2" value="<?php echo "$item_cd2"; ?>" />
					<input type="hidden" name="item_name1"
						value="<?php echo "$item_name1"; ?>" /> <input type="hidden"
						name="supplier_cd1" value="<?php echo "$supplier_cd1"; ?>" /> <input
						type="hidden" name="supplier_name1"
						value="<?php echo "$supplier_name1"; ?>" />

					<?php
					foreach ( $conn_array as $key => $value ) {
						$res = @pg_query ( $value, $query );

						if (! $res) {
							echo "エラー!! 入力項目が正しくありません。";
							// echo "Cannot execute SQL($query)";
						} else {
							// ヘッダ書き込み
							echo "<table>";
							echo "<tr>";
							echo "<th></th>";
							echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"p.purchase_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.purchase_slip_no DESC\">↑</button></th>";
							// echo "<th>仕入区分<br><button type=\"submit\" name=\"order_by\" value=\"p.purchase_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.purchase_kbn DESC\">↑</button></th>";
							// echo "<th>事業所CD<br><button type=\"submit\" name=\"order_by\" value=\"p.office_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.office_cd DESC\">↑</button></th>";
							// echo "<th>倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"p.warehouse_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.warehouse_cd DESC\">↑</button></th>";
							// echo "<th>倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name DESC\">↑</button></th>";
							echo "<th>入荷日<br><button type=\"submit\" name=\"order_by\" value=\"p.arrival_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.arrival_ymd DESC\">↑</button></th>";
							// echo "<th>検収日<br><button type=\"submit\" name=\"order_by\" value=\"p.acceptance_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.acceptance_ymd DESC\">↑</button></th>";
							echo "<th>仕入日<br><button type=\"submit\" name=\"order_by\" value=\"p.purchase_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.purchase_ymd DESC\">↑</button></th>";
							// echo "<th>担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"p.suppiler_staff_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.suppiler_staff_cd DESC\">↑</button></th>";
							// echo "<th>担当者名<br><button type=\"submit\" name=\"order_by\" value=\"mst.staff_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mst.staff_name DESC\">↑</button></th>";
							echo "<th>仕入先CD<br><button type=\"submit\" name=\"order_by\" value=\"p.supplier_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.supplier_cd DESC\">↑</button></th>";
							echo "<th>仕入先名<br><button type=\"submit\" name=\"order_by\" value=\"msp.supplier_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"msp.supplier_name DESC\">↑</button></th>";
							// echo "<th>支払先CD<br><button type=\"submit\" name=\"order_by\" value=\"p.payee_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.payee_cd DESC\">↑</button></th>";
							// echo "<th>支払先名<br><button type=\"submit\" name=\"order_by\" value=\"mpy.payee_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mpy.payee_name DESC\">↑</button></th>";
							// echo "<th>支払帳端区分<br><button type=\"submit\" name=\"order_by\" value=\"p.payment_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.payment_kbn DESC\">↑</button></th>";
							// echo "<th>支払予定日<br><button type=\"submit\" name=\"order_by\" value=\"p.payment_date\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.payment_date DESC\">↑</button></th>";
							// echo "<th>支払方法CD<br><button type=\"submit\" name=\"order_by\" value=\"p.payment_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.payment_cd DESC\">↑</button></th>";
							// echo "<th>支払方法<br><button type=\"submit\" name=\"order_by\" value=\"mpm.payment_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mpm.payment_name DESC\">↑</button></th>";
							// echo "<th>仕入先注文番号<br><button type=\"submit\" name=\"order_by\" value=\"p.supplier_order_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.supplier_order_no DESC\">↑</button></th>";
							// echo "<th>案件番号<br><button type=\"submit\" name=\"order_by\" value=\"p.demand_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.demand_no DESC\">↑</button></th>";
							// echo "<th>配送業者CD<br><button type=\"submit\" name=\"order_by\" value=\"p.hauler_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.hauler_cd DESC\">↑</button></th>";
							// echo "<th>配送業者名<br><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name DESC\">↑</button></th>";
							// echo "<th>伝票摘要<br><button type=\"submit\" name=\"order_by\" value=\"p.slip_remarks\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.slip_remarks DESC\">↑</button></th>";
							// echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"p.created_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.created_cd DESC\">↑</button></th>";
							// echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"p.created_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.created_timestamp DESC\">↑</button></th>";
							// echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"p.modified_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.modified_cd DESC\">↑</button></th>";
							// echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"p.modified_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.modified_timestamp DESC\">↑</button></th>";
							// echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_slip_no DESC\">↑</button></th>";
							echo "<th>行<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_detail_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_detail_no DESC\">↑</button></th>";
							// echo "<th>明細発注区分<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_kbn DESC\">↑</button></th>";
							// echo "<th>債務科目区分<br><button type=\"submit\" name=\"order_by\" value=\"pd.debt_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.debt_kbn DESC\">↑</button></th>";
							// echo "<th>明細倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"pd.warehouse_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.warehouse_cd DESC\">↑</button></th>";
							echo "<th>商品CD<br><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN pd.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( pd.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE pd.item_cd END\">↓</button><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN pd.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( pd.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE pd.item_cd END DESC\">↑</button></th>";
							echo "<th>商品名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_name DESC\">↑</button></th>";
							echo "<th>JANCD<br><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd  DESC\">↑</button></th>";
							echo "<th>商品名補足<br><button type=\"submit\" name=\"order_by\" value=\"pd.model_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.model_name DESC\">↑</button></th>";
							// echo "<th>税率区分<br><button type=\"submit\" name=\"order_by\" value=\"pd.tax_rate_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.tax_rate_kbn DESC\">↑</button></th>";
							// echo "<th>明細荷数<br><button type=\"submit\" name=\"order_by\" value=\"pd.unit_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.unit_qty DESC\">↑</button></th>";
							// echo "<th>明細バラ数<br><button type=\"submit\" name=\"order_by\" value=\"pd.single_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.single_qty DESC\">↑</button></th>";
							echo "<th>仕入数<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_qty DESC\">↑</button></th>";
							// echo "<th>仕入単価<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_cost\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_cost DESC\">↑</button></th>";
							// echo "<th>仕入金額<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_amount\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_amount DESC\">↑</button></th>";
							// echo "<th>仕入消費税額<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_consumption_tax\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_consumption_tax DESC\">↑</button></th>";
							// echo "<th>明細仕入先注文番号<br><button type=\"submit\" name=\"order_by\" value=\"pd.supplier_order_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.supplier_order_no DESC\">↑</button></th>";
							// echo "<th>明細入荷日<br><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.purchase_ymd DESC\">↑</button></th>";
							// echo "<th>明細摘要<br><button type=\"submit\" name=\"order_by\" value=\"pd.remarks\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.remarks DESC\">↑</button></th>";
							// echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"pd.created_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.created_cd DESC\">↑</button></th>";
							// echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"pd.created_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.created_timestamp DESC\">↑</button></th>";
							// echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"pd.modified_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.modified_cd DESC\">↑</button></th>";
							// echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"pd.modified_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"pd.modified_timestamp DESC\">↑</button></th>";

							echo "<th>倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name DESC\">↑</button></th>";
							echo "<th>発注伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"p.order_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"p.order_slip_no DESC\">↑</button></th>";

							echo "</tr>";

							// 1行毎の処理
							while ( $row = pg_fetch_assoc ( $res ) ) {
								$row_no = ( string ) $row ['row_no'];
								$slip_no = ( string ) $row ['slip_no'];
								$purchase_kubun_cd = ( string ) $row ['purchase_kubun_cd'];
								$office_cd = ( string ) $row ['office_cd'];
								$warehouse_cd = ( string ) $row ['warehouse_cd'];
								$warehouse_name = ( string ) $row ['warehouse_name'];
								$order_slip_no = ( string ) $row ['order_slip_no'];
								$stock_ymd = ( string ) $row ['stock_ymd'];
								$acceptance_ymd = ( string ) $row ['acceptance_ymd'];
								$purchase_ymd = ( string ) $row ['purchase_ymd'];
								$staff_cd = ( string ) $row ['staff_cd'];
								$staff_name = ( string ) $row ['staff_name'];
								$supplier_cd = ( string ) $row ['supplier_cd'];
								$supplier_name = ( string ) $row ['supplier_name'];
								$payee_cd = ( string ) $row ['payee_cd'];
								$payee_name = ( string ) $row ['payee_name'];
								$payment_kubun_cd = ( string ) $row ['payment_kubun_cd'];
								$payment_date = ( string ) $row ['payment_date'];
								$payment_cd = ( string ) $row ['payment_cd'];
								$payment_name = ( string ) $row ['payment_name'];
								$supplier_order_no = ( string ) $row ['supplier_order_no'];
								$demand_no = ( string ) $row ['demand_no'];
								$hauler_cd = ( string ) $row ['hauler_cd'];
								$hauler_name = ( string ) $row ['hauler_name'];
								$slip_summary = ( string ) $row ['slip_summary'];
								$created_cd = ( string ) $row ['created_cd'];
								$created_timestamp = ( string ) $row ['created_timestamp'];
								$modified_cd = ( string ) $row ['modified_cd'];
								$modified_timestamp = ( string ) $row ['modified_timestamp'];
								$d_slip_no = ( string ) $row ['d_slip_no'];
								$d_detail_line_no = ( string ) $row ['d_detail_line_no'];
								$d_detail_order_kubun_cd = ( string ) $row ['d_detail_order_kubun_cd'];
								$d_debt_kubun_cd = ( string ) $row ['d_debt_kubun_cd'];
								$d_detail_warehouse_cd = ( string ) $row ['d_detail_warehouse_cd'];
								$d_item_cd = ( string ) $row ['d_item_cd'];
								$d_item_name = ( string ) $row ['d_item_name'];
								$d_jan_cd = ( string ) $row ['d_jan_cd'];
								$d_model_name = ( string ) $row ['d_model_name'];
								$d_tax_rate_kubun_cd = ( string ) $row ['d_tax_rate_kubun_cd'];
								$d_unit_quantity = ( string ) $row ['d_unit_quantity'];
								$d_single_quantity = ( string ) $row ['d_single_quantity'];
								$d_quantity = ( string ) $row ['d_quantity'];
								$d_supplier_price = ( string ) $row ['d_supplier_price'];
								$d_supplier_amount = ( string ) $row ['d_supplier_amount'];
								$d_supplier_consumption_tax = ( string ) $row ['d_supplier_consumption_tax'];
								$d_supplier_order_no = ( string ) $row ['d_supplier_order_no'];
								$d_stock_ymd = ( string ) $row ['d_stock_ymd'];
								$d_detail_summary = ( string ) $row ['d_detail_summary'];
								$d_created_cd = ( string ) $row ['d_created_cd'];
								$d_created_timestamp = ( string ) $row ['d_created_timestamp'];
								$d_modified_cd = ( string ) $row ['d_modified_cd'];
								$d_modified_timestamp = ( string ) $row ['d_modified_timestamp'];

								echo "<tr>";
								echo "<td>$row_no</td>";
								echo "<td onclick=\"InputSlipNo('" . $slip_no . "')\">$slip_no</td>";
								// echo "<td>$purchase_kubun_cd</td>";
								// echo "<td>$office_cd</td>";
								// echo "<td>$warehouse_cd</td>";
								// echo "<td>$warehouse_name</td>";
								echo "<td>$stock_ymd</td>";
								// echo "<td>$acceptance_ymd</td>";
								echo "<td>$purchase_ymd</td>";
								// echo "<td>$staff_cd</td>";
								// echo "<td>$staff_name</td>";
								echo "<td>$supplier_cd</td>";
								echo "<td>$supplier_name</td>";
								// echo "<td>$payee_cd</td>";
								// echo "<td>$payee_name</td>";
								// echo "<td>$payment_kubun_cd</td>";
								// echo "<td>$payment_date</td>";
								// echo "<td>$payment_cd</td>";
								// echo "<td>$payment_name</td>";
								// echo "<td>$supplier_order_no</td>";
								// echo "<td>$demand_no</td>";
								// echo "<td>$hauler_cd</td>";
								// echo "<td>$hauler_name</td>";
								// echo "<td>$slip_summary</td>";
								// echo "<td>$created_cd</td>";
								// echo "<td>$created_timestamp</td>";
								// echo "<td>$modified_cd</td>";
								// echo "<td>$modified_timestamp</td>";
								// echo "<td>$d_slip_no</td>";
								echo "<td Align=\"right\">$d_detail_line_no</td>";
								// echo "<td>$d_detail_order_kubun_cd</td>";
								// echo "<td>$d_debt_kubun_cd</td>";
								// echo "<td>$d_detail_warehouse_cd</td>";
								echo "<td>$d_item_cd</td>";
								echo "<td>$d_item_name</td>";
								echo "<td>$d_jan_cd</td>";
								echo "<td>$d_model_name</td>";
								// echo "<td>$d_tax_rate_kubun_cd</td>";
								// echo "<td Align=\"right\">$d_unit_quantity</td>";
								// echo "<td Align=\"right\">$d_single_quantity</td>";
								echo "<td Align=\"right\">$d_quantity</td>";
									// echo "<td Align=\"right\">$d_supplier_price</td>";
									// echo "<td Align=\"right\">$d_supplier_amount</td>";
									// echo "<td Align=\"right\">$d_supplier_consumption_tax</td>";
									// echo "<td>$d_supplier_order_no</td>";
									// echo "<td>$d_stock_ymd</td>";
									// echo "<td>$d_detail_summary</td>";
									// echo "<td>$d_created_cd</td>";
									// echo "<td>$d_created_timestamp</td>";
									// echo "<td>$d_modified_cd</td>";
									// echo "<td>$d_modified_timestamp</td>";
								echo "<td>$warehouse_name</td>";
								echo "<td onclick=\"LinkSlipNo('" . $order_slip_no . "')\">$order_slip_no</td>";
								echo "</tr>";
							}

							echo "</table>";
						}
					}
					?>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
