<?php
	$today = date ( "Ymd", mktime ( 0, 0, 0, date ( "m" ), date ( "d" ), date ( "Y" ) ) );

	// 並び替え順序文字
	// $order_by = ( string ) @$_POST ['order_by'];
	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
	// 2016-05-20 kinoshita END
	if ($order_by == null) {
		$order_by = "tcs.warehause_cd";
	}

	$stock0_flg = ( string ) @$_POST ['stock0_flg'];

	$warehouse_cd1 = ( string ) @$_POST ['warehouse_cd1'];
	$item_kind_category_cd1 = ( string ) @$_POST ['item_kind_category_cd1'];
	$item_kind_cd1 = ( string ) @$_POST ['item_kind_cd1'];

	$wrk_str = ( string ) @$_POST ['item_cd1'];
	if ($wrk_str == "") {
	} else if (substr ( $wrk_str, 0, 1 ) != "D") {
		$wrk_str = "D" . $wrk_str;
	}
	$item_cd1 = $wrk_str;

	$wrk_str = ( string ) @$_POST ['item_cd2'];
	if ($wrk_str == "") {
	} else if (substr ( $wrk_str, 0, 1 ) != "D") {
		$wrk_str = "D" . $wrk_str;
	}
	$item_cd2 = $wrk_str;

	$item_name1 = ( string ) @$_POST ['item_name1'];

	$jan_cd1 = ( string ) @$_POST ['jan_cd1'];

	$arr1 = array (
			"row_no",
			// ,"warehouse_cd"
			"warehouse_name",
			// ,"item_kind_category_cd"
			// ,"item_kind_category_name"
			// ,"item_kind_cd"
			// ,"item_kind_name"
			"item_cd",
			"item_name",
			"jan_cd",
			"current_quantity"
	);

	$arr2 = array (
			"",
			// ,"倉庫CD"
			"倉庫名",
			// ,"品種カテゴリCD"
			// ,"品種カテゴリ名"
			// ,"品種CD"
			// ,"品種名"
			"商品CD",
			"商品名",
			"JANCD",
			"現在庫数"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM tbl_zks_current_stock
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
		tcs.warehause_cd AS warehouse_cd,
		ms.shop_short_name AS warehouse_name,
		mikc.item_kind_category_cd AS item_kind_category_cd,
		mikc.item_kind_category_name AS item_kind_category_name,
		mik.item_kind_cd AS item_kind_cd,
		mik.item_kind_name AS item_kind_name,
		tcs.item_cd AS item_cd,
		mi.item_name AS item_name,
		mi.jan_cd AS jan_cd,
		TO_CHAR(CAST(tcs.current_quantity AS INTEGER), 'FM9,999,999,990') AS current_quantity
QUERY_EOD;

	$query = <<<QUERY_EOD
	FROM tbl_zks_current_stock as tcs
	LEFT JOIN (
		SELECT
			mi1.item_cd AS item_cd,
			mi1.active_span_st AS active_span_st,
			mi1.item_name AS item_name,
			mi1.jan_cd AS jan_cd,
			mi1.item_kind_id AS item_kind_id
		FROM (
			SELECT
				item_cd AS item_cd,
				MAX(active_span_st) AS active_span_st
			FROM mst_obic_item
			GROUP BY item_cd
		) as mi0
		INNER JOIN mst_obic_item AS mi1 ON
			mi1.item_cd = mi0.item_cd AND
			mi1.active_span_st = mi0.active_span_st
	) AS mi ON
		mi.item_cd = tcs.item_cd
	LEFT JOIN mst_item_kind AS mik ON
		mik.item_kind_cd = mi.item_kind_id
	LEFT JOIN mst_item_kind_category AS mikc ON
		mikc.item_kind_category_cd = mik.item_kind_category_cd
	LEFT JOIN mst_shop_for_tool AS ms ON
		ms.dept_cd = tcs.warehause_cd
	WHERE
		mi.item_cd LIKE 'D%'
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

	<h2>在庫照会（店舗用）</h2>

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
					echo "</select>";
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
							<th>倉庫</th>
							<?php
							if ($sv_ip == "192.168.1.50") {
								if ($warehouse_cd1 == "") {
									$warehouse_cd1 = "0000";
								}

								echo "<td><select name=\"warehouse_cd1\" onkeypress=\"return EnterFocusR(this, 'stock0_flg')\">";
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
									echo "<td><select name=\"warehouse_cd1\" onkeypress=\"return EnterFocusR(this, 'stock0_flg')\">";

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
							<th>在庫数条件</th>
							<td>
								<?php
								if ($stock0_flg == "false") {
									echo "<input type=\"radio\" name=\"stock0_flg\" value=\"all\" onkeypress=\"return EnterFocus(this, 'item_cd1')\">すべて";
									echo "<input type=\"radio\" name=\"stock0_flg\" value=\"false\" checked onkeypress=\"return EnterFocus(this, 'item_cd1')\">在庫数0を含めない";
								} else {
									echo "<input type=\"radio\" name=\"stock0_flg\" value=\"all\" checked onkeypress=\"return EnterFocus(this, 'item_cd1')\">すべて";
									echo "<input type=\"radio\" name=\"stock0_flg\" value=\"false\" onkeypress=\"return EnterFocus(this, 'item_cd1')\">在庫数0を含めない";
									$stock0_flg = "all";
								}
								?>
							</td>
<?php
/*
 * echo "<br>";
 * echo "品種カテゴリCD";
 * echo "<select name=\"item_kind_category_cd1\">";
 * echo "<option value=\"\">000 すべて</option>\n";
 *
 * // クエリ作成
 * $query4 = <<<QUERY_EOD
 * SELECT
 * item_kind_category_cd,
 * item_kind_category_name
 * FROM mst_item_kind_category
 * ORDER BY item_kind_category_cd
 * QUERY_EOD;
 * // QUERY_EODの前には空白などを含めない
 *
 * foreach ($conn_array as $key => $value) {
 * $res = @pg_query($value, $query4);
 * if ($res) {
 *
 * while($row = pg_fetch_assoc($res)){
 * $item_kind_category_cd = (string) $row['item_kind_category_cd'];
 * $item_kind_category_name = (string) $row['item_kind_category_name'];
 *
 * $echo_str = "<option value=\"".$item_kind_category_cd."\"";
 * if ($item_kind_category_cd1 == $item_kind_category_cd) {
 * $echo_str .= " selected";
 * }
 *
 * $echo_str .= ">".$item_kind_category_cd." ".$item_kind_category_name."</option>\n";
 * echo "$echo_str";
 * }
 * echo "</select>";
 * }
 * }
 *
 *
 * echo "<br>";
 * echo "品種CD";
 * echo "<select name=\"item_kind_cd1\">";
 * echo "<option value=\"\">00000 すべて</option>\n";
 *
 * // クエリ作成
 * $query5 = <<<QUERY_EOD
 * SELECT
 * item_kind_cd,
 * item_kind_name
 * FROM mst_item_kind
 * ORDER BY item_kind_cd
 * QUERY_EOD;
 * // QUERY_EODの前には空白などを含めない
 *
 * foreach ($conn_array as $key => $value) {
 * $res = @pg_query($value, $query5);
 * if ($res) {
 *
 * while($row = pg_fetch_assoc($res)){
 * $item_kind_cd = (string) $row['item_kind_cd'];
 * $item_kind_name = (string) $row['item_kind_name'];
 *
 * $echo_str = "<option value=\"".$item_kind_cd."\"";
 * if ($item_kind_cd1 == $item_kind_cd) {
 * $echo_str .= " selected";
 * }
 *
 * $echo_str .= ">".$item_kind_cd." ".$item_kind_name."</option>\n";
 * echo "$echo_str";
 * }
 * echo "</select>";
 * }
 * }
 */
?>
						</tr>
						<tr>
							<th>商品CD</th>
							<td><input type="text" name="item_cd1"
								value="<?php echo $item_cd1; ?>" maxlength="7"
								onkeypress="return EnterFocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)"
								onblur="return FocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)" />
								~ <input type="text" name="item_cd2"
								value="<?php echo $item_cd2; ?>" maxlength="7"
								onkeypress="return EnterFocusCD(this, 'item_name1', 'item_cd2', 'D', 7, false)"
								onblur="return FocusCD(this, 'item_name1', 'item_cd2', 'D', 7, false)" /></td>
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
					<input type="hidden" name="order_by"
						value="<?php echo $order_by; ?>" /> <input type="hidden"
						name="page" value="<?php echo $page; ?>" /> <input type="submit"
						value="更新" name="button" /> <input type="submit" value="前へ"
						name="button_pre" /> <input type="submit" value="次へ"
						name="button_next" />

					<?php

						// 入荷完了区分
					if ($stock0_flg == "false") {
						$query = $query . <<<QUERY_EOD
					AND tcs.current_quantity > 0
QUERY_EOD;
					}

					// 倉庫CD
					if ($sv_ip == "192.168.1.50") {
						if ($warehouse_cd1 != '0000' && $warehouse_cd1 != '') {
							$query = $query . <<<QUERY_EOD
					AND tcs.warehause_cd = '$warehouse_cd1'
QUERY_EOD;
						}
					} else {
						// if ($warehouse_cd1 == "0000") {
						// $query .= " AND tcs.warehause_cd IN (";
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
						AND tcs.warehause_cd = '$warehouse_cd1'
QUERY_EOD;
						// }
					}

					// 品種カテゴリCD
					if ($item_kind_category_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND mikc.item_kind_category_cd = '$item_kind_category_cd1'
QUERY_EOD;
					}

					// 品種CD
					if ($item_kind_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND mik.item_kind_cd = '$item_kind_cd1'
QUERY_EOD;
					}

					// 商品CD
					if ($item_cd1 != '' && $item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND (tcs.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(tcs.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd1 != '') {
						$query = $query . <<<QUERY_EOD
						AND (tcs.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(tcs.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
QUERY_EOD;
					} else if ($item_cd2 != '') {
						$query = $query . <<<QUERY_EOD
						AND (tcs.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(tcs.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
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
					<input type="hidden" name="stock0_flg"
						value="<?php echo "$stock0_flg"; ?>" />
					<input type="hidden" name="warehouse_cd1"
						value="<?php echo "$warehouse_cd1"; ?>" />
					<input type="hidden" name="item_kind_category_cd1"
						value="<?php echo "$item_kind_category_cd1"; ?>" />
					<input type="hidden" name="item_kind_cd1"
						value="<?php echo "$item_kind_cd1"; ?>" />
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
								// echo "<th>倉庫CD<br><button type=\"submit\" name=\"order_by\" value=\"tcs.warehause_cd ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"tcs.warehause_cd DESC\">↑</button></th>";
								echo "<th>倉庫名<br><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"ms.shop_short_name DESC\">↑</button></th>";
								// echo "<th>品種カテゴリCD<br><button type=\"submit\" name=\"order_by\" value=\"tcs.item_kind_category_cd ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"tcs.item_kind_category_cd DESC\">↑</button></th>";
								// echo "<th>品種カテゴリ名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_kind_category_name ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_kind_category_name DESC\">↑</button></th>";
								// echo "<th>品種CD<br><button type=\"submit\" name=\"order_by\" value=\"tcs.item_kind_cd ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"tcs.item_kind_cd DESC\">↑</button></th>";
								// echo "<th>品種名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_kind_name ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_kind_name DESC\">↑</button></th>";
								echo "<th>商品CD<br><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN tcs.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( tcs.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE tcs.item_cd END ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"CASE WHEN tcs.item_cd~ '^D[0-9]{1,}' THEN 'D' || TO_CHAR(CAST(REPLACE( tcs.item_cd ,'D' ,'' ) AS INTEGER),'FM000000') ELSE tcs.item_cd END DESC\">↑</button></th>";
								echo "<th>商品名<br><button type=\"submit\" name=\"order_by\" value=\"mi.item_name ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.item_name DESC\">↑</button></th>";
								echo "<th>JANCD<br><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"mi.jan_cd DESC\">↑</button></th>";
								echo "<th>現在庫数<br><button type=\"submit\" name=\"order_by\" value=\"tcs.current_quantity ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"tcs.current_quantity DESC\">↑</button></th>";
								echo "</tr>";

								// 1行毎の処理
								while ( $row = pg_fetch_assoc ( $res ) ) {
									$row_no = ( string ) $row ['row_no'];
									$warehouse_cd = ( string ) $row ['warehouse_cd'];
									$warehouse_name = ( string ) $row ['warehouse_name'];
									$item_kind_category_cd = ( string ) $row ['item_kind_category_cd'];
									$item_kind_category_name = ( string ) $row ['item_kind_category_name'];
									$item_kind_cd = ( string ) $row ['item_kind_cd'];
									$item_kind_name = ( string ) $row ['item_kind_name'];
									$item_cd = ( string ) $row ['item_cd'];
									$item_name = ( string ) $row ['item_name'];
									$jan_cd = ( string ) $row ['jan_cd'];
									$quantity = ( string ) $row ['current_quantity'];

									echo "<tr>";
									echo "<td>$row_no</td>";
									// echo "<td>$warehouse_cd</td>";
									echo "<td>$warehouse_name</td>";
									// echo "<td>$item_kind_category_cd</td>";
									// echo "<td>$item_kind_category_name</td>";
									// echo "<td>$item_kind_cd</td>";
									// echo "<td>$item_kind_name</td>";
									echo "<td>$item_cd</td>";
									echo "<td>$item_name</td>";
									echo "<td>$jan_cd</td>";
									echo "<td Align=\"right\">$quantity</td>";
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