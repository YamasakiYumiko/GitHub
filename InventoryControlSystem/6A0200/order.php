<?php
	// 並び替え順序文字
// 	$order_by = (string) @$_POST['order_by'];
	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
	if ($order_by == null) {
		$order_by = "o.order_slip_no";
	}

	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);

	$stocked_flg = (string) @$_POST['stocked_flg'];

	$warehouse_cd1 = (string) @$_POST['warehouse_cd1'];

	$wrk_str = (string) @$_POST['order_ymd1'];
	$order_ymd1 = $wrk_str;

	$wrk_str = (string) @$_POST['order_ymd2'];
	$order_ymd2 = $wrk_str;

	$wrk_str = (string) @$_POST['stock_ymd1'];
	$stock_ymd1 = $wrk_str;

	$wrk_str = (string) @$_POST['stock_ymd2'];
	$stock_ymd2 =$wrk_str;

	$wrk_str = (string) @$_POST['slip_no1'];
	if ($wrk_str != "") {
		$slip_no1 = $wrk_str;
	} else {
		$slip_no1 = (string) @$_GET['slip_no1'];
	}

	$wrk_str = (string) @$_POST['slip_no2'];
	if ($wrk_str != "") {
		$slip_no2 = $wrk_str;
	} else {
		$slip_no2 = (string) @$_GET['slip_no2'];
	}

	$wrk_str = (string) @$_POST['item_cd1'];
	$item_cd1 = $wrk_str;

	$wrk_str = (string) @$_POST['item_cd2'];
	$item_cd2 = $wrk_str;

	$item_name1 = (string) @$_POST['item_name1'];

	$jan_cd1 = (string) @$_POST['jan_cd1'];

	$payee_cd1 = (string) @$_POST['payee_cd1'];
	$supplier_cd1 = (string) @$_POST['supplier_cd1'];
	$supplier_name1 = (string) @$_POST['supplier_name1'];

	$today = date ( "Ymd", mktime ( 0, 0, 0, date ( "m" ), date ( "d" ), date ( "Y" ) ) );

	$arr1 = array (
			"row_no",
			"slip_no",
			"order_kubun_cd",
			"office_cd",
			"warehouse_cd",
			"warehouse_name",
			"order_ymd",
			"stock_ymd",
			"purchase_ymd",
			"staff_cd",
			"staff_name",
			"supplier_cd",
			"supplier_name",
			"payee_cd",
			"payee_name",
			"payment_kubun_cd",
			"payment_date",
			"payment_cd",
			"payment_name",
			"supplier_order_no",
			"demand_no",
			"hauler_cd",
			"hauler_name",
			"slip_summary",
			"purchase_summary",
			"slip_unnecessary_flg",
			"slip_target_flg",
			"slip_immediately_flg",
			"slip_timestamp",
			"stocked_kubun_cd",
			"purchased_kubun_cd",
			"item_status_kubun_cd",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp",
			// ,"d_slip_no"
			"d_detail_line_no",
			"d_detail_order_kubun_cd",
			"d_debt_kubun_cd",
			"d_detail_warehouse_cd",
			"d_item_cd",
			"d_item_name",
			"d_jan_cd",
			"d_model_name",
			"d_tax_rate_kubun_cd",
			"d_unit_quantity",
			"d_single_quantity",
			"d_quantity",
			"d_temporary_price_kubun_cd",
			"d_supplier_price",
			"d_supplier_amount",
			"d_supplier_consumption_tax",
			"d_payee_order_no",
			"d_stock_ymd",
			"d_purchase_ymd",
			"d_detail_summary",
			"d_stock_quantity_ex",
			"d_stock_quantity",
			"d_purchase_quantity",
			// ,"d_cancel_flg"
			"d_created_cd",
			"d_created_timestamp",
			"d_modified_cd",
			"d_modified_timestamp"
	);

	$arr2 = array (
			"",
			"伝票番号",
			"発注区分",
			"事業所CD",
			"倉庫CD",
			"倉庫名",
			"発注日",
			"入荷予定日",
			"仕入予定日",
			"担当者CD",
			"担当者名",
			"仕入先CD",
			"仕入先名",
			"支払先CD",
			"支払先名",
			"支払帳端区分",
			"支払予定日",
			"支払方法CD",
			"支払方法",
			"仕入先注文番号",
			"案件番号",
			"配送業者CD",
			"配送業者名",
			"伝票摘要",
			"発注書摘要",
			"発注書不要フラグ",
			"発注書発行対象フラグ",
			"発注書即伝発行フラグ",
			"発注書摘要発行日時",
			"入荷完了区分",
			"仕入完了区分",
			"商品状態区分",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時",
			// ,"伝票番号"
			"明細行番号",
			"明細発注区分",
			"債務科目区分",
			"明細倉庫CD",
			"商品CD",
			"商品名",
			"規格",
			"税率区分",
			"明細荷数",
			"明細バラ数",
			"明細数量",
			"仮単価区分",
			"仕入単価",
			"仕入金額",
			"仕入消費税額",
			"明細仕入先注文番号",
			"明細入荷予定日",
			"明細仕入予定日",
			"明細摘要",
			"入荷予定数",
			"入荷数",
			"仕入数",
			// ,"取消フラグ"
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);


	// クエリ作成
	$wrk_query = <<<QUERY_EOD
	SELECT
		ROW_NUMBER() OVER(ORDER BY $order_by) AS row_no,
		o.order_slip_no AS slip_no,
		o.order_kbn AS order_kubun_cd,
		o.office_cd AS office_cd,
		o.warehouse_cd AS warehouse_cd,
		mw.warehause_name AS warehouse_name,
		o.order_ymd AS order_ymd,
		o.arrival_ex_ymd AS stock_ymd,
		o.purchase_ex_ymd AS purchase_ymd,
		o.supplier_staff_cd AS staff_cd,
		mst.staff_name AS staff_name,
		o.supplier_cd AS supplier_cd,
		msp.supplier_name AS supplier_name,
		o.payee_cd AS payee_cd,
		mpy.payee_name AS payee_name,
		o.payment_kbn AS payment_kubun_cd,
		o.payment_date AS payment_date,
		o.payment_cd AS payment_cd,
		mpm.payment_name AS payment_name,
		o.supplier_order_no AS supplier_order_no,
		o.demand_no AS demand_no,
		o.hauler_cd AS hauler_cd,
		mh.hauler_name AS hauler_name,
		o.slip_remarks AS slip_summary,
		o.order_remarks AS purchase_summary,
		o.slip_not_use_flg AS slip_unnecessary_flg,
		o.order_print_flg AS slip_target_flg,
		o.ex_order_flg AS slip_immediately_flg,
		TO_CHAR(o.print_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS slip_timestamp,
		o.arrived_kbn AS stocked_kubun_cd,
		o.purchased_kbn AS purchased_kubun_cd,
		o.approval_status_kbn AS item_status_kubun_cd,
		o.created_cd AS created_cd,
		TO_CHAR(o.created_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS created_timestamp,
		o.modified_cd AS modified_cd,
		TO_CHAR(o.modified_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS modified_timestamp,
		od.order_slip_no AS d_slip_no,
		od.order_detail_no AS d_detail_line_no,
		od.order_kbn AS d_detail_order_kubun_cd,
		od.debt_kbn AS d_debt_kubun_cd,
		od.warehouse_cd AS d_detail_warehouse_cd,
		od.item_cd AS d_item_cd,
		mi.item_name AS d_item_name,
		mi.jan_cd AS d_jan_cd,
		od.model_name AS d_model_name,
		od.tax_rate_kbn AS d_tax_rate_kubun_cd,
		CAST(od.unit_qty AS INTEGER) AS d_unit_quantity,
		CAST(od.single_qty AS INTEGER) AS d_single_quantity,
		CAST(od.order_qty AS INTEGER) AS d_quantity,
		od.temporary_price_kbn AS d_temporary_price_kubun_cd,
		REPLACE(TO_CHAR(od.purchase_cost, '9999999990.99'), '.00', '&nbsp;&nbsp;&nbsp;') AS d_supplier_price,
		CAST(od.purchase_amount AS INTEGER) AS d_supplier_amount,
		CAST(od.purchase_consumption_tax AS INTEGER) AS d_supplier_consumption_tax,
		od.payee_order_no AS d_payee_order_no,
		od.arrival_ex_ymd AS d_stock_ymd,
		od.purchase_ex_ymd AS d_purchase_ymd,
		od.remarks AS d_detail_summary,
		CAST(od.arrival_ex_qty AS INTEGER) AS d_stock_quantity_ex,
		CAST(od.arrival_qty AS INTEGER) AS d_stock_quantity,
		CAST(od.purchase_qty AS INTEGER) AS d_purchase_quantity,
		od.cancel_flg AS d_cancel_flg,
		od.created_cd AS d_created_cd,
		TO_CHAR(od.created_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS d_created_timestamp,
		od.modified_cd AS d_modified_cd,
		TO_CHAR(od.modified_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS d_modified_timestamp
QUERY_EOD;

	$query = <<<QUERY_EOD
	FROM tbl_zks_obic_order as o
	INNER JOIN tbl_zks_obic_order_detail AS od ON
		od.order_slip_no = o.order_slip_no
	LEFT JOIN mst_obic_warehause AS mw ON
		mw.warehause_cd = o.warehouse_cd
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
		mst.staff_cd = o.supplier_staff_cd
	LEFT JOIN mst_obic_supplier AS msp ON
		msp.supplier_cd = o.supplier_cd
	LEFT JOIN mst_obic_payee AS mpy ON
		mpy.payee_cd = o.payee_cd
	LEFT JOIN mst_obic_payment AS mpm ON
		mpm.payment_cd = o.payment_cd
	LEFT JOIN mst_obic_hauler AS mh ON
		mh.hauler_cd = o.hauler_cd
	LEFT JOIN mst_obic_item AS mi ON
		mi.item_cd = od.item_cd
	WHERE
		od.cancel_flg = '0'
QUERY_EOD;

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM tbl_zks_obic_order_detail
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

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<title>在庫管理システム</title>
	<meta charset="UTF-8">
	<script type="text/javascript" src="../default.js" charset=UTF-8></script>
	<link rel="stylesheet" type="text/css" href="../default.css" />
	<?php include(dirname(__FILE__) . "/common.php"); ?>
</head>
<body class="cute">
	<h2>発注照会</h2>

	<div id="wrapper">
		<div id="headWrap">
			<?php
			foreach ( $conn_array as $key => $value ) {
				$res = @pg_query ( $value, $query2 );
				if ($res) {

					while ( $row = pg_fetch_assoc ( $res ) ) {
						$output_timestamp = ( string ) $row ['output_timestamp'];

						echo "<div Align = \"right\"><b>更新日時：" . $output_timestamp . "</b></div>";
					}
				}
			}
			?>

			<input type="button" value="メニューへ戻る" onclick="location.href='./main.html'">

		</div>
		<div id="container">
			<div class="searchbox">

				<form method="POST" action="" name="form1">
					<table>
						<tr>
							<?php
							echo "<th>倉庫</th>";
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
							?>

							<th>仕入先CD</th>
							<td><input type="text" name="supplier_cd1"
									value="<?php echo $supplier_cd1; ?>" maxlength="10"
									onkeypress="return EnterFocusCD(this, 'slip_no1', 'supplier_cd1', '', 10, true)"
									onblur="return FocusCD(this, 'slip_no1', 'supplier_cd1', '', 10, true)" />
							</td>

						</tr>
						<tr>
							<th>伝票NO</th>
							<td>
								<input type="text" name="slip_no1" value="<?php echo $slip_no1; ?>" maxlength="12" onkeypress="return EnterFocus(this, 'slip_no2')" />
								~ <input type="text" name="slip_no2" value="<?php echo $slip_no2; ?>" maxlength="12" onkeypress="return EnterFocus(this, 'supplier_name1')" />
							</td>
							<th>仕入先名</th>
							<td>
								<input type="text" name="supplier_name1" value="<?php echo $supplier_name1; ?>" onkeypress="return EnterFocusR(this, 'stocked_flg')" />
							</td>
						</tr>
						<tr>
							<th>入庫状態</th>
							<td>
								<?php
								if ($stocked_flg == "all") {
									echo "<input type=\"radio\" name=\"stocked_flg\" value=\"all\" checked onkeypress=\"return EnterFocus(this, 'item_cd1')\">すべて";
									echo "<input type=\"radio\" name=\"stocked_flg\" value=\"0\" onkeypress=\"return EnterFocus(this, 'item_cd1')\">未入庫のみ";
								} else {
									echo "<input type=\"radio\" name=\"stocked_flg\" value=\"all\" onkeypress=\"return EnterFocus(this, 'item_cd1')\">すべて";
									echo "<input type=\"radio\" name=\"stocked_flg\" value=\"0\" checked onkeypress=\"return EnterFocus(this, 'item_cd1')\">未入庫のみ";
									$stocked_flg = "0";
								}
								?>
							</td>
							<th>商品CD</th>
							<td>
								<input type="text" name="item_cd1" value="<?php echo $item_cd1; ?>" maxlength="7" onkeypress="return EnterFocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)" onblur="return FocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)" />
								~
								<input type="text" name="item_cd2" value="<?php echo $item_cd2; ?>" maxlength="7" onkeypress="return EnterFocusCD(this, 'order_ymd1', 'item_cd2', 'D', 7, false)" onblur="return FocusCD(this, 'order_ymd1', 'item_cd2', 'D', 7, false)" />
							</td>
						</tr>
						<tr>
							<th>発注日</th>
							<td><input type="text" name="order_ymd1"
									value="<?php echo $order_ymd1; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'order_ymd2', 'order_ymd1', '')"
									onblur="return FocusDate(this, 'order_ymd2', 'order_ymd1', '')" />
								~ <input type="text" name="order_ymd2"
									value="<?php echo $order_ymd2; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'item_name1', 'order_ymd2', '')"
									onblur="return FocusDate(this, 'item_name1', 'order_ymd2', '')" /></td>
							<th>商品名</th>
							<td><input type="text" name="item_name1"
									value="<?php echo $item_name1; ?>"
									onkeypress="return EnterFocus(this, 'stock_ymd1')" /></td>
						</tr>
						<tr>
							<th>入荷予定日</th>
							<td><input type="text" name="stock_ymd1"
									value="<?php echo $stock_ymd1; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'stock_ymd2', 'stock_ymd1', '')"
									onblur="return FocusDate(this, 'stock_ymd2', 'stock_ymd1', '')" />
								~ <input type="text" name="stock_ymd2"
									value="<?php echo $stock_ymd2; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'jan_cd1', 'stock_ymd2', '')"
									onblur="return FocusDate(this, 'jan_cd1', 'stock_ymd2', '')" /></td>
							<th>JANCD</th>
							<td><input type="text" name="jan_cd1"
									value="<?php echo $jan_cd1; ?>" maxlength="13"
									onkeypress="return EnterFocusCD(this, 'interval', 'jan_cd1', '', 13, false)"
									onblur="return FocusCD(this, 'interval', 'jan_cd1', '', 13, false)" /></td>
						</tr>
						<tr>
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

					<input type="hidden" name="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="order_by" value="<?php echo $order_by; ?>" />
					<input type="submit" value="更新" name="button" />
					<input type="submit" value="前へ" name="button_pre" />
					<input type="submit" value="次へ" name="button_next" />

					<?php
					// 入荷完了区分
					if ($stocked_flg != "all") {
						$query = $query . <<<QUERY_EOD
					AND od.purchased_kbn = '$stocked_flg'
QUERY_EOD;
					}

					// 倉庫CD
					if ($warehouse_cd1 != '0000' && $warehouse_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.warehouse_cd = '$warehouse_cd1'
QUERY_EOD;
					}

					// 発注日
					if ($order_ymd1 != '' && $order_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_ymd BETWEEN '$order_ymd1' AND '$order_ymd2'
QUERY_EOD;
					} else if ($order_ymd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_ymd >= '$order_ymd1'
QUERY_EOD;
					} else if ($order_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_ymd <= '$order_ymd2'
QUERY_EOD;
					}
						// 入荷予定日
					if ($stock_ymd1 != '' && $stock_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.arrival_ex_ymd BETWEEN '$stock_ymd1' AND '$stock_ymd2'
QUERY_EOD;
					} else if ($stock_ymd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.arrival_ex_ymd >= '$stock_ymd1'
QUERY_EOD;
					} else if ($stock_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.arrival_ex_ymd <= '$stock_ymd2'
QUERY_EOD;
					}

					// 伝票番号
					if ($slip_no1 != '' && $slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_slip_no BETWEEN '$slip_no1' AND '$slip_no2'
QUERY_EOD;
					} else if ($slip_no1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_slip_no >= '$slip_no1'
QUERY_EOD;
					} else if ($slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.order_slip_no <= '$slip_no2'
QUERY_EOD;
					}

					// 仕入先CD
					if ($supplier_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.supplier_cd = '$supplier_cd1'
QUERY_EOD;
					}

					// 仕入先名
					if ($supplier_name1 != '') {
						$query = $query . <<<QUERY_EOD
					AND msp.supplier_name LIKE '%$supplier_name1%'
QUERY_EOD;
					}

						// 支払先CD
					if ($payee_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND o.payee_cd = '$payee_cd1'
QUERY_EOD;
					}

					// 商品CD
					if ($item_cd1 != '' && $item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
					AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					}

					// 商品名
					if ($item_name1 != '') {
						$query = $query . <<<QUERY_EOD
					AND mi.item_name LIKE '%$item_name1%'
QUERY_EOD;
					}

					// JANCD
					if ($jan_cd1 != '') {
						$query = $query . <<<QUERY_EOD
					AND mi.jan_cd = '$jan_cd1'
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
						value="<?php echo "$interval"; ?>" />
					<input type="hidden" name="stocked_flg"
						value="<?php echo "$stocked_flg"; ?>" />
					<input type="hidden" name="warehouse_cd1"
						value="<?php echo "$warehouse_cd1"; ?>" />
					<input type="hidden" name="order_ymd1"
						value="<?php echo "$order_ymd1"; ?>" />
					<input type="hidden" name="order_ymd2"
						value="<?php echo "$order_ymd2"; ?>" />
					<input type="hidden" name="stock_ymd1"
						value="<?php echo "$stock_ymd1"; ?>" />
					<input type="hidden" name="stock_ymd2"
						value="<?php echo "$stock_ymd2"; ?>" />
					<input type="hidden" name="slip_no1"
						value="<?php echo "$slip_no1"; ?>" />
					<input type="hidden" name="slip_no2"
						value="<?php echo "$slip_no2"; ?>" />
					<input type="hidden" name="item_cd1"
						value="<?php echo "$item_cd1"; ?>" />
					<input type="hidden" name="item_cd2"
						value="<?php echo "$item_cd2"; ?>" />
					<input type="hidden" name="item_name1"
						value="<?php echo "$item_name1"; ?>" />
					<input type="hidden" name="payee_cd1"
						value="<?php echo "$payee_cd1"; ?>" />
					<input type="hidden" name="supplier_cd1"
						value="<?php echo "$supplier_cd1"; ?>" />
					<input type="hidden" name="supplier_name1"
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
								echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"o.order_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.order_slip_no DESC\">↑</button></th>";
								echo "<th>発注区分<br><button type=\"submit\" name=\"order_by\" value=\"o.order_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.order_kbn DESC\">↑</button></th>";
								echo "<th>事業所CD<br><button type=\"submit\" name=\"order_by\" value=\"o.office_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.office_cd DESC\">↑</button></th>";
								echo "<th>倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"o.warehouse_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.warehouse_cd DESC\">↑</button></th>";
								echo "<th>倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"mw.warehause_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mw.warehause_name DESC\">↑</button></th>";
								echo "<th>発注日<br><button type=\"submit\" name=\"order_by\" value=\"o.order_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.order_ymd DESC\">↑</button></th>";
								echo "<th>入荷予定日<br><button type=\"submit\" name=\"order_by\" value=\"o.arrival_ex_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.arrival_ex_ymd DESC\">↑</button></th>";
								echo "<th>仕入予定日<br><button type=\"submit\" name=\"order_by\" value=\"o.purchase_ex_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.purchase_ex_ymd DESC\">↑</button></th>";
								echo "<th>担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"o.supplier_staff_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.supplier_staff_cd  DESC\">↑</button></th>";
								echo "<th>担当者名<br><button type=\"submit\" name=\"order_by\" value=\"mst.staff_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mst.staff_name  DESC\">↑</button></th>";
								echo "<th>仕入先CD<br><button type=\"submit\" name=\"order_by\" value=\"o.supplier_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.supplier_cd  DESC\">↑</button></th>";
								echo "<th>仕入先名<br><button type=\"submit\" name=\"order_by\" value=\"msp.supplier_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"msp.supplier_name  DESC\">↑</button></th>";
								echo "<th>支払先CD<br><button type=\"submit\" name=\"order_by\" value=\"o.payee_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.payee_cd  DESC\">↑</button></th>";
								echo "<th>支払先名<br><button type=\"submit\" name=\"order_by\" value=\"mpy.payee_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mpy.payee_name  DESC\">↑</button></th>";
								echo "<th>支払帳端区分<br><button type=\"submit\" name=\"order_by\" value=\"o.payment_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.payment_kbn  DESC\">↑</button></th>";
								echo "<th>支払予定日<br><button type=\"submit\" name=\"order_by\" value=\"o.payment_date \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.payment_date  DESC\">↑</button></th>";
								echo "<th>支払方法CD<br><button type=\"submit\" name=\"order_by\" value=\"o.payment_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.payment_cd  DESC\">↑</button></th>";
								echo "<th>支払方法<br><button type=\"submit\" name=\"order_by\" value=\"mpm.payment_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mpm.payment_name  DESC\">↑</button></th>";
								echo "<th>仕入先注文番号<br><button type=\"submit\" name=\"order_by\" value=\"o.supplier_order_no \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.supplier_order_no  DESC\">↑</button></th>";
								echo "<th>案件番号<br><button type=\"submit\" name=\"order_by\" value=\"o.demand_no \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.demand_no  DESC\">↑</button></th>";
								echo "<th>配送業者CD<br><button type=\"submit\" name=\"order_by\" value=\"o.hauler_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.hauler_cd  DESC\">↑</button></th>";
								echo "<th>配送業者名<br><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name  DESC\">↑</button></th>";
								echo "<th>伝票摘要<br><button type=\"submit\" name=\"order_by\" value=\"o.slip_remarks \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.slip_remarks  DESC\">↑</button></th>";
								echo "<th>発注書摘要<br><button type=\"submit\" name=\"order_by\" value=\"o.order_remarks \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.order_remarks  DESC\">↑</button></th>";
								echo "<th>発注書不要フラグ<br><button type=\"submit\" name=\"order_by\" value=\"o.slip_not_use_flg \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.slip_not_use_flg  DESC\">↑</button></th>";
								echo "<th>発注書発行対象フラグ<br><button type=\"submit\" name=\"order_by\" value=\"o.order_print_flg \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.order_print_flg  DESC\">↑</button></th>";
								echo "<th>発注書即伝発行フラグ<br><button type=\"submit\" name=\"order_by\" value=\"o.ex_order_flg \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.ex_order_flg  DESC\">↑</button></th>";
								echo "<th>発注書摘要発行日時<br><button type=\"submit\" name=\"order_by\" value=\"o.print_timestamp \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.print_timestamp  DESC\">↑</button></th>";
								echo "<th>入荷完了区分<br><button type=\"submit\" name=\"order_by\" value=\"o.arrived_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.arrived_kbn  DESC\">↑</button></th>";
								echo "<th>仕入完了区分<br><button type=\"submit\" name=\"order_by\" value=\"o.purchased_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.purchased_kbn  DESC\">↑</button></th>";
								echo "<th>商品状態区分<br><button type=\"submit\" name=\"order_by\" value=\"o.approval_status_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.approval_status_kbn  DESC\">↑</button></th>";
								echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"o.created_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.created_cd  DESC\">↑</button></th>";
								echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"o.created_timestamp \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.created_timestamp  DESC\">↑</button></th>";
								echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"o.modified_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.modified_cd  DESC\">↑</button></th>";
								echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"o.modified_timestamp \">↓</button><button type=\"submit\" name=\"order_by\" value=\"o.modified_timestamp  DESC\">↑</button></th>";
//								echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"od.order_slip_no \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.order_slip_no  DESC\">↑</button></th>";
								echo "<th>明細行番号<br><button type=\"submit\" name=\"order_by\" value=\"od.order_detail_no \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.order_detail_no  DESC\">↑</button></th>";
								echo "<th>明細発注区分<br><button type=\"submit\" name=\"order_by\" value=\"od.order_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.order_kbn  DESC\">↑</button></th>";
								echo "<th>債務科目区分<br><button type=\"submit\" name=\"order_by\" value=\"od.debt_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.debt_kbn  DESC\">↑</button></th>";
								echo "<th>明細倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"od.warehouse_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.warehouse_cd  DESC\">↑</button></th>";
								echo "<th>商品CD<br><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN od.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( od.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE od.item_cd END\">↓</button><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN od.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( od.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE od.item_cd END DESC\">↑</button></th>";
								echo "<th>商品名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_name  DESC\">↑</button></th>";
								echo "<th>JANCD<br><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd  DESC\">↑</button></th>";
								echo "<th>規格<br><button type=\"submit\" name=\"order_by\" value=\"od.model_name \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.model_name  DESC\">↑</button></th>";
								echo "<th>税率区分<br><button type=\"submit\" name=\"order_by\" value=\"od.tax_rate_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.tax_rate_kbn  DESC\">↑</button></th>";
								echo "<th>明細荷数<br><button type=\"submit\" name=\"order_by\" value=\"od.unit_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.unit_qty  DESC\">↑</button></th>";
								echo "<th>明細バラ数<br><button type=\"submit\" name=\"order_by\" value=\"od.single_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.single_qty  DESC\">↑</button></th>";
								echo "<th>明細数量<br><button type=\"submit\" name=\"order_by\" value=\"od.order_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.order_qty  DESC\">↑</button></th>";
								echo "<th>仮単価区分<br><button type=\"submit\" name=\"order_by\" value=\"od.temporary_price_kbn \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.temporary_price_kbn  DESC\">↑</button></th>";
								echo "<th>仕入単価<br><button type=\"submit\" name=\"order_by\" value=\"od.purchase_cost\">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.purchase_cost DESC\">↑</button></th>";
								echo "<th>仕入金額<br><button type=\"submit\" name=\"order_by\" value=\"od.purchase_amount\">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.purchase_amount DESC\">↑</button></th>";
								echo "<th>仕入消費税額<br><button type=\"submit\" name=\"order_by\" value=\"od.purchase_consumption_tax\">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.purchase_consumption_tax DESC\">↑</button></th>";
								echo "<th>明細仕入先注文番号<br><button type=\"submit\" name=\"order_by\" value=\"od.payee_order_no \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.payee_order_no  DESC\">↑</button></th>";
								echo "<th>明細入荷予定日<br><button type=\"submit\" name=\"order_by\" value=\"od.arrival_ex_ymd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.arrival_ex_ymd  DESC\">↑</button></th>";
								echo "<th>明細仕入予定日<br><button type=\"submit\" name=\"order_by\" value=\"od.purchase_ex_ymd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.purchase_ex_ymd  DESC\">↑</button></th>";
								echo "<th>明細摘要<br><button type=\"submit\" name=\"order_by\" value=\"od.remarks \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.remarks  DESC\">↑</button></th>";
								echo "<th>入荷予定数<br><button type=\"submit\" name=\"order_by\" value=\"od.arrival_ex_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.arrival_ex_qty  DESC\">↑</button></th>";
								echo "<th>入荷数<br><button type=\"submit\" name=\"order_by\" value=\"od.arrival_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.arrival_qty  DESC\">↑</button></th>";
								echo "<th>仕入数<br><button type=\"submit\" name=\"order_by\" value=\"od.purchase_qty \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.purchase_qty  DESC\">↑</button></th>";
//								echo "<th>取消フラグ<br><button type=\"submit\" name=\"order_by\" value=\"od.cancel_flg \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.cancel_flg  DESC\">↑</button></th>";
								echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"od.created_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.created_cd  DESC\">↑</button></th>";
								echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"od.created_timestamp \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.created_timestamp  DESC\">↑</button></th>";
								echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"od.modified_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.modified_cd  DESC\">↑</button></th>";
								echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"od.modified_timestamp \">↓</button><button type=\"submit\" name=\"order_by\" value=\"od.modified_timestamp  DESC\">↑</button></th>";
								echo "</tr>";

								// 1行毎の処理
								while ( $row = pg_fetch_assoc ( $res ) ) {
									$row_no = (string) $row['row_no'];
									$slip_no = (string) $row['slip_no'];
									$order_kubun_cd = (string) $row['order_kubun_cd'];
									$office_cd = (string) $row['office_cd'];
									$warehouse_cd = (string) $row['warehouse_cd'];
									$warehouse_name = (string) $row['warehouse_name'];
									$order_ymd = (string) $row['order_ymd'];
									$stock_ymd = (string) $row['stock_ymd'];
									$purchase_ymd = (string) $row['purchase_ymd'];
									$staff_cd = (string) $row['staff_cd'];
									$staff_name = (string) $row['staff_name'];
									$supplier_cd = (string) $row['supplier_cd'];
									$supplier_name = (string) $row['supplier_name'];
									$payee_cd = (string) $row['payee_cd'];
									$payee_name = (string) $row['payee_name'];
									$payment_kubun_cd = (string) $row['payment_kubun_cd'];
									$payment_date = (string) $row['payment_date'];
									$payment_cd = (string) $row['payment_cd'];
									$payment_name = (string) $row['payment_name'];
									$supplier_order_no = (string) $row['supplier_order_no'];
									$demand_no = (string) $row['demand_no'];
									$hauler_cd = (string) $row['hauler_cd'];
									$hauler_name = (string) $row['hauler_name'];
									$slip_summary = (string) $row['slip_summary'];
									$purchase_summary = (string) $row['purchase_summary'];
									$slip_unnecessary_flg = (string) $row['slip_unnecessary_flg'];
									$slip_target_flg = (string) $row['slip_target_flg'];
									$slip_immediately_flg = (string) $row['slip_immediately_flg'];
									$slip_timestamp = (string) $row['slip_timestamp'];
									$stocked_kubun_cd = (string) $row['stocked_kubun_cd'];
									$purchased_kubun_cd = (string) $row['purchased_kubun_cd'];
									$item_status_kubun_cd = (string) $row['item_status_kubun_cd'];
									$created_cd = (string) $row['created_cd'];
									$created_timestamp = (string) $row['created_timestamp'];
									$modified_cd = (string) $row['modified_cd'];
									$modified_timestamp = (string) $row['modified_timestamp'];
									$d_slip_no = (string) $row['d_slip_no'];
									$d_detail_line_no = (string) $row['d_detail_line_no'];
									$d_detail_order_kubun_cd = (string) $row['d_detail_order_kubun_cd'];
									$d_debt_kubun_cd = (string) $row['d_debt_kubun_cd'];
									$d_detail_warehouse_cd = (string) $row['d_detail_warehouse_cd'];
									$d_item_cd = (string) $row['d_item_cd'];
									$d_item_name = (string) $row['d_item_name'];
									$d_jan_cd = ( string ) $row ['d_jan_cd'];
									$d_model_name = (string) $row['d_model_name'];
									$d_tax_rate_kubun_cd = (string) $row['d_tax_rate_kubun_cd'];
									$d_unit_quantity = (string) $row['d_unit_quantity'];
									$d_single_quantity = (string) $row['d_single_quantity'];
									$d_quantity = (string) $row['d_quantity'];
									$d_temporary_price_kubun_cd = (string) $row['d_temporary_price_kubun_cd'];
									$d_supplier_price = (string) $row['d_supplier_price'];
									$d_supplier_amount = (string) $row['d_supplier_amount'];
									$d_supplier_consumption_tax = (string) $row['d_supplier_consumption_tax'];
									$d_payee_order_no = (string) $row['d_payee_order_no'];
									$d_stock_ymd = (string) $row['d_stock_ymd'];
									$d_purchase_ymd = (string) $row['d_purchase_ymd'];
									$d_detail_summary = (string) $row['d_detail_summary'];
									$d_stock_quantity_ex = (string) $row['d_stock_quantity_ex'];
									$d_stock_quantity = (string) $row['d_stock_quantity'];
									$d_purchase_quantity = (string) $row['d_purchase_quantity'];
									$d_cancel_flg = (string) $row['d_cancel_flg'];
									$d_created_cd = (string) $row['d_created_cd'];
									$d_created_timestamp = (string) $row['d_created_timestamp'];
									$d_modified_cd = (string) $row['d_modified_cd'];
									$d_modified_timestamp = (string) $row['d_modified_timestamp'];

									if ($d_purchased_kubun_cd == '済') {
										echo "<tr class=\"disable\">";
									} else {
										echo "<tr>";
									}
									echo "<td>$row_no</td>";
									echo "<td onclick=\"InputSlipNo('" . $slip_no . "')\">$slip_no</td>";
									echo "<td>$order_kubun_cd</td>";
									echo "<td>$office_cd</td>";
									echo "<td>$warehouse_cd</td>";
									echo "<td>$warehouse_name</td>";
									echo "<td>$order_ymd</td>";
									echo "<td>$stock_ymd</td>";
									echo "<td>$purchase_ymd</td>";
									echo "<td>$staff_cd</td>";
									echo "<td>$staff_name</td>";
									echo "<td>$supplier_cd</td>";
									echo "<td>$supplier_name</td>";
									echo "<td>$payee_cd</td>";
									echo "<td>$payee_name</td>";
									echo "<td>$payment_kubun_cd</td>";
									echo "<td>$payment_date</td>";
									echo "<td>$payment_cd</td>";
									echo "<td>$payment_name</td>";
									echo "<td>$supplier_order_no</td>";
									echo "<td>$demand_no</td>";
									echo "<td>$hauler_cd</td>";
									echo "<td>$hauler_name</td>";
									echo "<td>$slip_summary</td>";
									echo "<td>$purchase_summary</td>";
									echo "<td>$slip_unnecessary_flg</td>";
									echo "<td>$slip_target_flg</td>";
									echo "<td>$slip_immediately_flg</td>";
									echo "<td>$slip_timestamp</td>";
									echo "<td>$stocked_kubun_cd</td>";
									echo "<td>$purchased_kubun_cd</td>";
									echo "<td>$item_status_kubun_cd</td>";
									echo "<td>$created_cd</td>";
									echo "<td>$created_timestamp</td>";
									echo "<td>$modified_cd</td>";
									echo "<td>$modified_timestamp</td>";
//									echo "<td>$d_slip_no</td>";
									echo "<td>$d_detail_line_no</td>";
									echo "<td>$d_detail_order_kubun_cd</td>";
									echo "<td>$d_debt_kubun_cd</td>";
									echo "<td>$d_detail_warehouse_cd</td>";
									echo "<td>$d_item_cd</td>";
									echo "<td>$d_item_name</td>";
									echo "<td>$d_jan_cd</td>";
									echo "<td>$d_model_name</td>";
									echo "<td>$d_tax_rate_kubun_cd</td>";

									echo "<td Align=\"right\">$d_unit_quantity</td>";
									echo "<td Align=\"right\">$d_single_quantity</td>";
									echo "<td Align=\"right\">$d_quantity</td>";

									echo "<td>$d_temporary_price_kubun_cd</td>";

									echo "<td Align=\"right\">$d_supplier_price</td>";
									echo "<td Align=\"right\">$d_supplier_amount</td>";
									echo "<td Align=\"right\">$d_supplier_consumption_tax</td>";

									echo "<td>$d_payee_order_no</td>";
									echo "<td>$d_stock_ymd</td>";
									echo "<td>$d_purchase_ymd</td>";
									echo "<td>$d_detail_summary</td>";

									echo "<td Align=\"right\">$d_stock_quantity_ex</td>";
									echo "<td Align=\"right\">$d_stock_quantity</td>";
									echo "<td Align=\"right\">$d_purchase_quantity</td>";

//									echo "<td>$d_cancel_flg</td>";
									echo "<td>$d_created_cd</td>";
									echo "<td>$d_created_timestamp</td>";
									echo "<td>$d_modified_cd</td>";
									echo "<td>$d_modified_timestamp</td>";

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
