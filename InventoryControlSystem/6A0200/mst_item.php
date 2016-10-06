<?php
	// 並び替え順序文字
// 	$order_by = (string) @$_POST['order_by'];
	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
	if ($order_by == null) {
		$order_by = "m.item_cd";
	}

	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	// 基準日
	$wrk_str = (string) @$_POST['ymd1'];
	if ($wrk_str == "") {
		$ymd1 = $today;
	} else {
		$ymd1 = $wrk_str;
	}

	$wrk_str = (string) @$_POST['mst_cd1'];
	$mst_cd1 = $wrk_str;

	$wrk_str = (string) @$_POST['mst_cd2'];
	$mst_cd2 = $wrk_str;

	$item_name1 = (string) @$_POST['item_name1'];

	$jan_cd1 = (string) @$_POST['jan_cd1'];

	$m_arr1 = array (
			"row_no",
			"item_cd",
			"item_name",
			"item_kana",
			"model_name",
			"sundry_flg",
			"stock_ctrl_not_use_flg",
			"active_span_st",
			"active_span_ed",
			"obsolete_date",
			"unit_cd",
			"jan_cd",
			"itf_cd",
			"item_kind_id",
			"supplier_id",
			"qty_per_unit_cd",
			"qty_per_unit",
			"size1",
			"size2",
			"size3",
			"weight",
			"totalization_cd1",
			"totalization_cd2",
			"totalization_cd3",
			"packing_ctrl_not_use_flg",
			"lot_ctrl_flg",
			"active_span_ctrl_flg",
			"active_span_convert_kbn",
			"unit_flg",
			"salable_rank",
			"inventories_evaluation_standard",
			"consumption_tax_kbn",
			"consumption_tax_rate_kbn",
			"unit_price_calc_kbn",
			"lot_stock_evaluation_flg",
			"selling_cost_of_unit_price_calc_kbn",
			"standard_cost_of_unit_price",
			"active_span_check_month",
			"item_name_alphabet1",
			"item_name_alphabet2",
			"standard_qty1",
			"standard_qty2",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp"
	);

	$m_arr2 = array (
			"",
			"商品CD",
			"商品名",
			"商品カナ名",
			"モデル名",
			"諸口フラグ",
			"在庫管理不要フラグ",
			"取扱開始日",
			"取扱終了日",
			"製造中止日",
			"単位CD",
			"JANCD",
			"ITFCD",
			"品種ID",
			"仕入先ID",
			"入数単位CD",
			"入数",
			"サイズ1",
			"サイズ2",
			"サイズ3",
			"商品重量",
			"集計CD1",
			"集計CD2",
			"集計CD3",
			"荷姿管理不要フラグ",
			"ロット管理フラグ",
			"有効期限管理フラグ",
			"有効期限変換区分",
			"セット品フラグ",
			"販売可能商品ランク",
			"棚卸評価基準",
			"消費税内外区分",
			"消費税率区分",
			"個別在庫評価単価計算区分",
			"ロット個別在庫評価フラグ",
			"販売原価計算区分",
			"標準原価単価",
			"有効期限チェック月数",
			"英字商品名1",
			"英字商品名2",
			"基準量1",
			"基準量2",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM mst_obic_item
QUERY_EOD;

	// クエリ作成
	$query = <<<QUERY_EOD
	SELECT
QUERY_EOD;
	foreach ( $m_arr1 as $val ) {
		if ($val == "row_no") {
			$query .= " ROW_NUMBER() OVER(ORDER BY " . $order_by . ") AS row_no,";
		} else if ($val == "qty_per_unit") {
			$query .= "CAST(m." . $val . " AS INTEGER) AS " . $val . ",";
		} else if ($val == "standard_cost_of_unit_price") {
			$query .= "REPLACE(TO_CHAR(m." . $val . ", '9999999990.99'), '.00', '&nbsp;&nbsp;&nbsp;') AS " . $val . ",";
		} else if ($val == "created_timestamp" || $val == "modified_timestamp") {
			$query .= "TO_CHAR(m." . $val . ", 'YYYY-MM-DD HH24:MI:SS') AS " . $val . ",";
		} else {
			$query .= "m." . $val . " AS " . $val . ",";
		}
	}
	$query .= "''";

	$wrk_query = $query;

	$query = <<<QUERY_EOD
	FROM mst_obic_item as m
	WHERE
		TRUE
QUERY_EOD;

	// 基準日
	$query = $query. <<<QUERY_EOD
		AND (
			('$ymd1' BETWEEN m.active_span_st AND m.active_span_ed) OR
			('$ymd1' >= m.active_span_st AND m.active_span_ed IS NULL)
		)
QUERY_EOD;

	// CD
	if ($mst_cd1 != '' && $mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND (m.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(m.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$mst_cd1','D','') AS INTEGER) AND CAST(REPLACE('$mst_cd2','D','') AS INTEGER))
QUERY_EOD;
	} else if ($mst_cd1 != '') {
		$query = $query . <<<QUERY_EOD
		AND (m.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(m.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$mst_cd1','D','') AS INTEGER))
QUERY_EOD;
	} else if ($mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND (m.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(m.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$mst_cd2','D','') AS INTEGER))
QUERY_EOD;
	}

	// 商品名
	if ($item_name1 != '') {
		$query = $query. <<<QUERY_EOD
		AND m.item_name LIKE '%$item_name1%'
QUERY_EOD;
	}

	// JANCD
	if ($jan_cd1 != '') {
		$query = $query. <<<QUERY_EOD
		AND m.jan_cd = '$jan_cd1'
QUERY_EOD;
	}

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
	<h2>商品マスタ一覧</h2>

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

				<form method="POST" action="">
					<table style="border-style: none">
						<tr>
							<th>商品CD</th>
							<td><input type="text" name="mst_cd1"
									value="<?php echo $mst_cd1; ?>" maxlength="7"
									onkeypress="return EnterFocusCD(this, 'mst_cd2', 'mst_cd1', 'D', 7, false)"
									onblur="return FocusCD(this, 'mst_cd2', 'mst_cd1', 'D', 7, false)" />
								~ <input type="text" name="mst_cd2"
									value="<?php echo $mst_cd2; ?>" maxlength="7"
									onkeypress="return EnterFocusCD(this, 'item_name1', 'mst_cd2', 'D', 7, false)"
									onblur="return FocusCD(this, 'item_name1', 'mst_cd2', 'D', 7, false)" /></td>
							<th>商品名</th>
							<td><input type="text" name="item_name1"
									value="<?php echo $item_name1; ?>"
									onkeypress="return EnterFocus(this, 'jan_cd1')" /></td>
						</tr>
						<tr>
							<th>JANCD</th>
							<td><input type="text" name="jan_cd1"
									value="<?php echo $jan_cd1; ?>" maxlength="13"
									onkeypress="return EnterFocusCD(this, 'ymd1', 'jan_cd1', '', 13, false)"
									onblur="return FocusCD(this, 'ymd1', 'jan_cd1', '', 13, false)" /></td>
							<th>基準日</th>
							<td><input type="text" name="ymd1" value="<?php echo $ymd1; ?>"
									maxlength="10"
									onkeypress="return EnterFocusDate(this, 'interval', 'ymd1', '<?php echo $today; ?>')"
									onblur="return FocusDate(this, 'interval', 'ymd1', '<?php echo $today; ?>')" /></td>
						</tr>
						<tr>
							<th>表示件数</th>
							<td><select name="interval"
								onkeypress="return EnterFocus(this, 'button')">

								<?php
									foreach ($interval_array as $val)
									{
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
				foreach ( $m_arr1 as $val ) {
					echo "<input type='hidden' name='arr1[]' value='" . $val . "' />";
				}
				foreach ( $m_arr2 as $val ) {
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
					<input type="hidden" name="ymd1" value="<?php echo "$ymd1"; ?>" />
					<input type="hidden" name="mst_cd1"
						value="<?php echo "$mst_cd1"; ?>" />
					<input type="hidden" name="mst_cd2"
						value="<?php echo "$mst_cd2"; ?>" />
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
							for($count = 0; $count < count ( $m_arr2 ); $count ++) {
								if ($m_arr2 [$count] == "") {
									echo "<th>" . $m_arr2 [$count] . "</th>";
								} else if ($m_arr1 [$count] == "item_cd") {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN m.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( m.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE m.item_cd END\">↓</button><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN m.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( m.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE m.item_cd END DESC\">↑</button></th>";
								} else {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . "\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . " DESC\">↑</button></th>";
								}
							}
							echo "</tr>";

							// 1行毎の処理
							while ( $row = pg_fetch_assoc ( $res ) ) {
								echo "<tr>";
								foreach ( $m_arr1 as $val ) {
									$echo_str = ( string ) $row [$val];

									if ($val == "row_no") {
										echo "<td>" . $echo_str . "</td>";
									} else if ($val == "quantity_unit" || $val == "selling_price") {
										echo "<td Align=\"right\">" . $echo_str . "</td>";
									} else if ($val == "standard_cost_of_unit_price") {
										echo "<td Align=\"right\">" . $echo_str . "</td>";
									} else {
										echo "<td>" . $echo_str . "</td>";
									}
								}
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