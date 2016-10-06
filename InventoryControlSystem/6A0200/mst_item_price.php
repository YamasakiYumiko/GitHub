<?php
	// 並び替え順序文字
	// 2016-05-20 kinoshita
	// $order_by = ( string ) @$_POST ['order_by'];
	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
		if ($order_by == null) {
			$order_by = "m.item_cd";
		}


	// 2016-05-20 kinoshita END



	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$wrk_str = (string) @$_POST['mst_cd1'];
	$mst_cd1 = $wrk_str;

	$wrk_str = (string) @$_POST['mst_cd2'];
	$mst_cd2 = $wrk_str;

	$item_name1 = (string) @$_POST['item_name1'];

	$m_arr1 = array (
			"row_no",
			"item_cd",
			"item_name",
			"active_span_st",
			"active_span_ed",
			"retail_price",
			"retail_consumption_tax",
			"selling_price",
			"selling_consumption_tax",
			"purchase_cost",
			"purchase_consumption_tax",
			"standard_cost_of_unit_price",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp"
	);

	$m_arr2 = array (
			"",
			"商品CD",
			"商品名",
			"適用開始日",
			"適用終了日",
			"上代本体単価",
			"上代単価消費税",
			"売上本体単価",
			"売上単価消費税",
			"仕入本体単価",
			"仕入単価消費税",
			"標準原価単価",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM mst_obic_item_price
QUERY_EOD;

	// クエリ作成
	$query = <<<QUERY_EOD
	SELECT
QUERY_EOD;
	foreach ( $m_arr1 as $val ) {
		if ($val == "row_no") {
			$query .= " ROW_NUMBER() OVER(ORDER BY " . $order_by . ") AS row_no,";
		} else if ($val == "item_name") {
			$query .= "m1." . $val . " AS " . $val . ",";
		} else if ($val == "retail_price" || $val == "retail_consumption_tax" || $val == "selling_price" || $val == "selling_consumption_tax" || $val == "purchase_cost" || $val == "purchase_consumption_tax" || $val == "standard_cost_of_unit_price") {
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
	FROM mst_obic_item_price as m
	LEFT JOIN mst_obic_item as m1 ON
		m1.item_cd = m.item_cd
	WHERE
		m.item_cd LIKE 'D%'
QUERY_EOD;

	// 商品CD
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
		AND m1.item_name LIKE '%$item_name1%'
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
	<h2>商品単価マスタ一覧</h2>

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
									onkeypress="return EnterFocus(this, 'interval')" /></td>
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
								} else if ($m_arr1 [$count] == "item_name") {
									echo "<th>". $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m1." . $m_arr1 [$count] . "\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m1." . $m_arr1 [$count] . " DESC\">↑</button></th>";
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

									if ($val == "retail_price" || $val == "retail_consumption_tax" || $val == "selling_price" || $val == "selling_consumption_tax" || $val == "purchase_cost" || $val == "purchase_consumption_tax" || $val == "standard_cost_of_unit_price") {
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