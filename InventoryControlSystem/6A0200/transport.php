<?php
	// 並び替え順序文字
	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
	if ($order_by == null) {
		$order_by = "t.transfer_slip_no";
	}

	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);

	$transport_kubun_cd1 = (string) @$_POST['transport_kubun_cd1'];

	$warehouse_cd1 = (string) @$_POST['warehouse_cd1'];
	$warehouse_cd2 = (string) @$_POST['warehouse_cd2'];

	$wrk_str = (string) @$_POST['transport_ymd1'];
	$transport_ymd1 = $wrk_str;

	$wrk_str = (string) @$_POST['transport_ymd2'];
	$transport_ymd2 = $wrk_str;

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

	$arr1 = array (
			"row_no",
			"slip_no",
			"transfer_kbn",
			"office_cd",
			"transport_ymd",
			"out_office_cd",
			"out_warehause_cd",
			"out_warehause_name",
			"in_office_cd",
			"in_warehause_cd",
			"in_warehause_name",
			"demand_no",
			"hauler_cd",
			"hauler_name",
			"slip_summary",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp",
			// ,"d_slip_no"
			"d_detail_line_no",
			"d_item_cd",
			"d_item_name",
			"d_jan_cd",
			"d_model_name",
			"d_unit_quantity",
			"d_single_quantity",
			"d_quantity",
			"d_detail_summary",
			"d_created_cd",
			"d_created_timestamp",
			"d_modified_cd",
			"d_modified_timestamp"
	);

	$arr2 = array (
			"",
			"伝票番号",
			"移動区分",
			"事業所CD",
			"移動日",
			"出庫事業所CD",
			"出庫倉庫CD",
			"出庫倉庫名",
			"入庫事業所CD",
			"入庫倉庫CD",
			"入庫倉庫名",
			"案件番号",
			"配送業者CD",
			"配送業者名",
			"伝票摘要",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時",
			// ,"伝票番号"
			"明細行番号",
			"商品CD",
			"商品名",
			"規格",
			"明細荷数",
			"明細バラ数",
			"明細数量",
			"明細摘要",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
		SELECT
			TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
		FROM tbl_zks_obic_transfer_detail
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
	$wrk_query = <<<QUERY_EOD
	SELECT
		ROW_NUMBER() OVER(ORDER BY $order_by) AS row_no,
		t.transfer_slip_no AS slip_no,
		case transfer_kbn when '11' then '調整入庫' when '12' then '調整出庫' when '13' then '移動' when '14' then '移動入庫' when '15' then '移動出庫' else '' end AS transfer_kbn,
		t.office_cd AS office_cd,
		t.transfer_ymd AS transport_ymd,
		t.shipping_office_cd AS out_office_cd,
		t.shipping_warehouse_cd AS out_warehause_cd,
		mow.warehause_name AS out_warehause_name,
		t.arrival_office_cd AS in_office_cd,
		t.arrival_warehouse_cd AS in_warehause_cd,
		miw.warehause_name AS in_warehause_name,
		t.demand_no AS demand_no,
		t.hauler_cd AS hauler_cd,
		mh.hauler_name AS hauler_name,
		t.slip_remarks AS slip_summary,
		t.created_cd AS created_cd,
		TO_CHAR(t.created_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS created_timestamp,
		t.modified_cd AS modified_cd,
		TO_CHAR(t.modified_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS modified_timestamp,
		td.transfer_slip_no AS d_slip_no,
		td.transfer_detail_no AS d_detail_line_no,
		td.item_cd AS d_item_cd,
		mi.item_name AS d_item_name,
		mi.jan_cd AS d_jan_cd,
		td.model_name AS d_model_name,
		CAST(td.unit_qty AS INTEGER) AS d_unit_quantity,
		CAST(td.single_qty AS INTEGER) AS d_single_quantity,
		CAST(td.transfer_qty AS INTEGER) AS d_quantity,
		td.remarks AS d_detail_summary,
		td.created_cd AS d_created_cd,
		TO_CHAR(td.created_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS d_created_timestamp,
		td.modified_cd AS d_modified_cd,
		TO_CHAR(td.modified_timestamp, 'YYYY-MM-DD HH24:MI:SS') AS d_modified_timestamp
QUERY_EOD;

	$query = <<<QUERY_EOD
	FROM tbl_zks_obic_transfer as t
	INNER JOIN tbl_zks_obic_transfer_detail AS td ON
		td.transfer_slip_no = t.transfer_slip_no
	LEFT JOIN mst_obic_warehause AS mow ON
		mow.warehause_cd = t.shipping_warehouse_cd
	LEFT JOIN mst_obic_warehause AS miw ON
		miw.warehause_cd = t.arrival_warehouse_cd
	LEFT JOIN mst_obic_hauler AS mh ON
		mh.hauler_cd = t.hauler_cd
	LEFT JOIN mst_obic_item AS mi ON
		mi.item_cd = td.item_cd
	WHERE
		TRUE
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

	<h2>移動照会</h2>

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

			<input type="button" value="メニューへ戻る"
				onclick="location.href='./main.html'">

		</div>
		<div id="container">
			<div class="searchbox">

				<form method="POST" action="" name="form1">

					<table>
						<tr>
						<?php
							echo "<th>出庫倉庫</th>";
							echo "<td><select name=\"warehouse_cd1\" onkeypress=\"return EnterFocus(this, 'warehouse_cd2')\">";
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

							echo "<th>入庫倉庫</th>";
							echo "<td><select name=\"warehouse_cd2\" onkeypress=\"return EnterFocus(this, 'slip_no1')\">";
							echo "<option value=\"0000\">0000 すべて</option>\n";
							foreach ( $conn_array as $key => $value ) {
								$res = @pg_query ( $value, $query3 );
								if ($res) {

									while ( $row = pg_fetch_assoc ( $res ) ) {
										$warehouse_cd = ( string ) $row ['warehouse_cd'];
										$warehouse_name = ( string ) $row ['warehouse_name'];

										$echo_str = "<option value=\"" . $warehouse_cd . "\"";
										if ($warehouse_cd2 == $warehouse_cd) {
											$echo_str .= " selected";
										}

										$echo_str .= ">" . $warehouse_cd . " " . $warehouse_name . "</option>\n";
										echo "$echo_str";
									}
									echo "</select></td>";
								}
							}
						?>
					</tr>

						<tr>
							<th>伝票NO</th>

							<td><input type="text" name="slip_no1"
									value="<?php echo $slip_no1; ?>" maxlength="12"
									onkeypress="return EnterFocus(this, 'slip_no2')" /> ~ <input
									type="text" name="slip_no2" value="<?php echo $slip_no2; ?>"
									maxlength="12" onkeypress="return EnterFocus(this, 'item_cd1')" />
							</td>

							<th>商品CD</th>
							<td><input type="text" name="item_cd1"
									value="<?php echo $item_cd1; ?>" maxlength="7"
									onkeypress="return EnterFocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)"
									onblur="return FocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)" />
								~ <input type="text" name="item_cd2"
									value="<?php echo $item_cd2; ?>" maxlength="7"
									onkeypress="return EnterFocusCD(this, 'transport_kubun_cd1', 'item_cd2', 'D', 7, false)"
									onblur="return FocusCD(this, 'transport_kubun_cd1', 'item_cd2', 'D', 7, false)" /></td>
						</tr>
						<tr>
							<th>移動区分</th>
							<td><select name="transport_kubun_cd1"
								onkeypress="return EnterFocus(this, 'item_name1')">
									<option value=""
										<?php if ($transport_kubun_cd1 == "") {echo "selected";} ?>>00
										すべて</option>
									<option value="11"
										<?php if ($transport_kubun_cd1 == "11") {echo "selected";} ?>>11
										調整入庫</option>
									<option value="12"
										<?php if ($transport_kubun_cd1 == "12") {echo "selected";} ?>>12
										調整出庫</option>
									<option value="13"
										<?php if ($transport_kubun_cd1 == "13") {echo "selected";} ?>>13
										移動</option>
									<option value="14"
										<?php if ($transport_kubun_cd1 == "14") {echo "selected";} ?>>14
										移動入庫</option>
									<option value="15"
										<?php if ($transport_kubun_cd1 == "15") {echo "selected";} ?>>15
										移動出庫</option>
							</select></td>
							<th>商品名</th>
							<td><input type="text" name="item_name1"
									value="<?php echo $item_name1; ?>"
									onkeypress="return EnterFocus(this, 'transport_ymd1')" /></td>
						</tr>
						<tr>
							<th>移動日</th>
							<td><input type="text" name="transport_ymd1"
									value="<?php echo $transport_ymd1; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'transport_ymd2', 'transport_ymd1', '')"
									onblur="return FocusDate(this, 'transport_ymd2', 'transport_ymd1', '')" />
								~ <input type="text" name="transport_ymd2"
									value="<?php echo $transport_ymd2; ?>" maxlength="10"
									onkeypress="return EnterFocusDate(this, 'jan_cd1', 'transport_ymd2', '')"
									onblur="return FocusDate(this, 'jan_cd1', 'transport_ymd2', '')" /></td>
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
					<input type="hidden" name="order_by"
						value="<?php echo $order_by; ?>" />
					<input type="submit" value="更新" name="button" />
					<input type="submit" value="前へ" name="button_pre" />
					<input type="submit" value="次へ" name="button_next" />

				<?php

					// 出庫倉庫CD
					if ($warehouse_cd1 != '0000' && $warehouse_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.shipping_warehouse_cd = '$warehouse_cd1'
QUERY_EOD;
					}

					// 入庫倉庫CD
					if ($warehouse_cd2 != '0000' && $warehouse_cd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.arrival_warehouse_cd = '$warehouse_cd2'
QUERY_EOD;
					}

					// 移動区分
					if ($transport_kubun_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_kbn = '$transport_kubun_cd1'
QUERY_EOD;
					}

					// 移動日
					if ($transport_ymd1 != '' && $transport_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_ymd BETWEEN '$transport_ymd1' AND '$transport_ymd2'
QUERY_EOD;
					} else if ($transport_ymd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_ymd >= '$transport_ymd1'
QUERY_EOD;
					} else if ($transport_ymd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_ymd <= '$transport_ymd2'
QUERY_EOD;
					}

						// 伝票番号
					if ($slip_no1 != '' && $slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_slip_no BETWEEN '$slip_no1' AND '$slip_no2'
QUERY_EOD;
					} else if ($slip_no1 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_slip_no >= '$slip_no1'
QUERY_EOD;
					} else if ($slip_no2 != '') {
						$query = $query . <<<QUERY_EOD
						AND t.transfer_slip_no <= '$slip_no2'
QUERY_EOD;
					}

						// 商品CD
					if ($item_cd1 != '' && $item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND (td.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(td.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND (td.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(td.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND (td.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(td.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
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
					<input type="hidden" name="transport_kubun_cd1"
						value="<?php echo "$transport_kubun_cd1"; ?>" />
					<input type="hidden" name="warehouse_cd1"
						value="<?php echo "$warehouse_cd1"; ?>" />
					<input type="hidden" name="warehouse_cd2"
						value="<?php echo "$warehouse_cd2"; ?>" />
					<input type="hidden" name="transport_ymd1"
						value="<?php echo "$transport_ymd1"; ?>" />
					<input type="hidden" name="transport_ymd2"
						value="<?php echo "$transport_ymd2"; ?>" />
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
								echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"t.transfer_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.transfer_slip_no DESC\">↑</button></th>";
								echo "<th>移動区分<br><button type=\"submit\" name=\"order_by\" value=\"t.transfer_kbn\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.transfer_kbn DESC\">↑</button></th>";
								echo "<th>事業所CD<br><button type=\"submit\" name=\"order_by\" value=\"t.office_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.office_cd DESC\">↑</button></th>";
								echo "<th>移動日<br><button type=\"submit\" name=\"order_by\" value=\"t.transfer_ymd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.transfer_ymd DESC\">↑</button></th>";
								echo "<th>出庫事業所CD<br><button type=\"submit\" name=\"order_by\" value=\"t.shipping_office_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.shipping_office_cd DESC\">↑</button></th>";
								echo "<th>出庫倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"t.shipping_warehouse_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.shipping_warehouse_cd DESC\">↑</button></th>";
								echo "<th>出庫倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"mow.warehause_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mow.warehause_name DESC\">↑</button></th>";
								echo "<th>入庫事業所CD<br><button type=\"submit\" name=\"order_by\" value=\"t.arrival_office_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.arrival_office_cd DESC\">↑</button></th>";
								echo "<th>入庫倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"t.arrival_warehouse_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.arrival_warehouse_cd DESC\">↑</button></th>";
								echo "<th>入庫倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"miw.warehause_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"miw.warehause_name DESC\">↑</button></th>";
								echo "<th>案件番号<br><button type=\"submit\" name=\"order_by\" value=\"t.demand_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.demand_no DESC\">↑</button></th>";
								echo "<th>配送業者CD<br><button type=\"submit\" name=\"order_by\" value=\"t.hauler_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.hauler_cd DESC\">↑</button></th>";
								echo "<th>配送業者名<br><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mh.hauler_name DESC\">↑</button></th>";
								echo "<th>伝票摘要<br><button type=\"submit\" name=\"order_by\" value=\"t.slip_remarks\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.slip_remarks DESC\">↑</button></th>";
								echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"t.created_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.created_cd DESC\">↑</button></th>";
								echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"t.created_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.created_timestamp DESC\">↑</button></th>";
								echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"t.modified_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.modified_cd DESC\">↑</button></th>";
								echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"t.modified_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"t.modified_timestamp DESC\">↑</button></th>";
								// echo "<th>伝票番号<br><button type=\"submit\" name=\"order_by\" value=\"td.transfer_slip_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.transfer_slip_no DESC\">↑</button></th>";
								echo "<th>明細行番号<br><button type=\"submit\" name=\"order_by\" value=\"td.transfer_detail_no\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.transfer_detail_no DESC\">↑</button></th>";
								echo "<th>商品CD<br><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN td.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( td.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE td.item_cd END\">↓</button><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN td.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( td.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE td.item_cd END DESC\">↑</button></th>";
								echo "<th>商品名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_name DESC\">↑</button></th>";
								echo "<th>JANCD<br><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd \">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd  DESC\">↑</button></th>";
								echo "<th>規格<br><button type=\"submit\" name=\"order_by\" value=\"td.model_name\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.model_name DESC\">↑</button></th>";
								echo "<th>明細荷数<br><button type=\"submit\" name=\"order_by\" value=\"td.unit_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.unit_qty DESC\">↑</button></th>";
								echo "<th>明細バラ数<br><button type=\"submit\" name=\"order_by\" value=\"td.single_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.single_qty DESC\">↑</button></th>";
								echo "<th>明細数量<br><button type=\"submit\" name=\"order_by\" value=\"td.transfer_qty\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.transfer_qty DESC\">↑</button></th>";
								echo "<th>明細摘要<br><button type=\"submit\" name=\"order_by\" value=\"td.remarks\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.remarks DESC\">↑</button></th>";
								echo "<th>作成担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"td.created_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.created_cd DESC\">↑</button></th>";
								echo "<th>作成日時<br><button type=\"submit\" name=\"order_by\" value=\"td.created_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.created_timestamp DESC\">↑</button></th>";
								echo "<th>更新担当者CD<br><button type=\"submit\" name=\"order_by\" value=\"td.modified_cd\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.modified_cd DESC\">↑</button></th>";
								echo "<th>更新日時<br><button type=\"submit\" name=\"order_by\" value=\"td.modified_timestamp\">↓</button><button type=\"submit\" name=\"order_by\" value=\"td.modified_timestamp DESC\">↑</button></th>";
							echo "</tr>";

								// 1行毎の処理
							while ( $row = pg_fetch_assoc ( $res ) ) {
								$row_no = ( string ) $row ['row_no'];
								$slip_no = ( string ) $row ['slip_no'];
								$transfer_kbn = ( string ) $row ['transfer_kbn'];
								$office_cd = ( string ) $row ['office_cd'];
								$transport_ymd = ( string ) $row ['transport_ymd'];
								$out_office_cd = ( string ) $row ['out_office_cd'];
								$out_warehause_cd = ( string ) $row ['out_warehause_cd'];
								$out_warehause_name = ( string ) $row ['out_warehause_name'];
								$in_office_cd = ( string ) $row ['in_office_cd'];
								$in_warehause_cd = ( string ) $row ['in_warehause_cd'];
								$in_warehause_name = ( string ) $row ['in_warehause_name'];
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
								$d_item_cd = ( string ) $row ['d_item_cd'];
								$d_item_name = ( string ) $row ['d_item_name'];
								$d_jan_cd = ( string ) $row ['d_jan_cd'];
								$d_model_name = ( string ) $row ['d_model_name'];
								$d_unit_quantity = ( string ) $row ['d_unit_quantity'];
								$d_single_quantity = ( string ) $row ['d_single_quantity'];
								$d_quantity = ( string ) $row ['d_quantity'];
								$d_detail_summary = ( string ) $row ['d_detail_summary'];
								$d_created_cd = ( string ) $row ['d_created_cd'];
								$d_created_timestamp = ( string ) $row ['d_created_timestamp'];
								$d_modified_cd = ( string ) $row ['d_modified_cd'];
								$d_modified_timestamp = ( string ) $row ['d_modified_timestamp'];

								echo "<tr>";
									echo "<td>$row_no</td>";
									echo "<td onclick=\"InputSlipNo('" . $slip_no . "')\">$slip_no</td>";
									echo "<td>$transfer_kbn</td>";
									echo "<td>$office_cd</td>";
									echo "<td>$transport_ymd</td>";
									echo "<td>$out_office_cd</td>";
									echo "<td>$out_warehause_cd</td>";
									echo "<td>$out_warehause_name</td>";
									echo "<td>$in_office_cd</td>";
									echo "<td>$in_warehause_cd</td>";
									echo "<td>$in_warehause_name</td>";
									echo "<td>$demand_no</td>";
									echo "<td>$hauler_cd</td>";
									echo "<td>$hauler_name</td>";
									echo "<td>$slip_summary</td>";
									echo "<td>$created_cd</td>";
									echo "<td>$created_timestamp</td>";
									echo "<td>$modified_cd</td>";
									echo "<td>$modified_timestamp</td>";
									// echo "<td>$d_slip_no</td>";
									echo "<td>$d_detail_line_no</td>";
									echo "<td>$d_item_cd</td>";
									echo "<td>$d_item_name</td>";
									echo "<td>$d_jan_cd</td>";
									echo "<td>$d_model_name</td>";
									echo "<td Align=\"right\">$d_unit_quantity</td>";
									echo "<td Align=\"right\">$d_single_quantity</td>";
									echo "<td Align=\"right\">$d_quantity</td>";
									echo "<td>$d_detail_summary</td>";
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
