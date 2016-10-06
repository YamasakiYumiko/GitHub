<?php
	// 並び替え順序文字
	$order_by = (string) @$_POST['order_by'];
	if ($order_by == null) {
		$order_by = "m.warehause_cd";
	}

	$mst_cd1 = (string) @$_POST['mst_cd1'];
	$mst_cd2 = (string) @$_POST['mst_cd2'];
	$mst1_cd1 = (string) @$_POST['mst1_cd1'];
	$mst1_cd2 = (string) @$_POST['mst1_cd2'];

	$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$m_arr1 = array (
			"row_no",
			"warehause_cd",
			"warehause_name",
			"warehause_short_name",
			"warehause_kana",
			"warehause_place_cd",
			"office_cd",
			"office_name",
			"group_cd",
			"group_name",
			"zipcode1",
			"zipcode2",
			"address1",
			"address1_kana",
			"address2",
			"address2_kana",
			"tel1",
			"tel2",
			"tel3",
			"fax1",
			"fax2",
			"fax3",
			"staff_cd",
			"office_kbn",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp"
	);

	$m_arr2 = array (
			"",
			"倉庫CD",
			"倉庫正式名",
			"倉庫名",
			"倉庫カナ名",
			"倉庫場所CD",
			"事業所CD",
			"事業所名",
			"部門CD",
			"部門名",
			"郵便番号１",
			"郵便番号２",
			"住所１",
			"住所１カナ",
			"住所２",
			"住所２カナ",
			"電話番号１",
			"電話番号２",
			"電話番号３",
			"FAX番号１",
			"FAX番号２",
			"FAX番号３",
			"倉庫担当者名",
			"倉庫区分",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM mst_obic_warehause
QUERY_EOD;

// クエリ作成
	$query = <<<QUERY_EOD
	SELECT
QUERY_EOD;
	foreach ( $m_arr1 as $val ) {
		if ($val == "row_no") {
			$query .= " ROW_NUMBER() OVER(ORDER BY " . $order_by . ") AS row_no,";
		} else if ($val == "office_name") {
			$query .= "m1." . $val . " AS " . $val . ",";
		} else if ($val == "group_name") {
			$query .= "m2." . $val . " AS " . $val . ",";
		} else if ($val == "created_timestamp" || $val == "modified_timestamp") {
			$query .= "TO_CHAR(m." . $val . ", 'YYYY-MM-DD HH24:MI:SS') AS " . $val . ",";
		} else {
			$query .= "m." . $val . " AS " . $val . ",";
		}
	}
	$query .= "''";

	$wrk_query = $query;

	$query = <<<QUERY_EOD
	FROM mst_obic_warehause as m
	LEFT JOIN mst_obic_office as m1 ON
		m1.office_cd = m.office_cd
	LEFT JOIN mst_obic_group as m2 ON
		m2.group_cd = m.group_cd
	WHERE
		TRUE
QUERY_EOD;

	// 部門CD
	if ($mst_cd1 != '' && $mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.warehause_cd BETWEEN '$mst_cd1' AND '$mst_cd2'
QUERY_EOD;
	} else if ($mst_cd1 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.warehause_cd >= '$mst_cd1'
QUERY_EOD;
	} else if ($mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.warehause_cd <= '$mst_cd2'
QUERY_EOD;
	}

	// 事業所CD
	if ($mst1_cd1 != '' && $mst1_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.office_cd BETWEEN '$mst1_cd1' AND '$mst1_cd2'
QUERY_EOD;
	} else if ($mst1_cd1 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.office_cd >= '$mst1_cd1'
QUERY_EOD;
	} else if ($mst1_cd2 != '') {
		$query = $query . <<<QUERY_EOD
		AND m.office_cd <= '$mst1_cd2'
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
	<h2>倉庫マスタ一覧</h2>

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
							<th>事業所CD</th>
							<td><input type="text" name="mst1_cd1" value="<?php echo $mst1_cd1; ?>" maxlength="3" onkeypress="return EnterFocusCD(this, 'mst1_cd2', 'mst1_cd1', '', 3, true)" onblur="return FocusCD(this, 'mst1_cd2', 'mst1_cd1', '', 3, true)" />
								~
								<input type="text" name="mst1_cd2" value="<?php echo $mst1_cd2; ?>" maxlength="3" onkeypress="return EnterFocusCD(this, 'mst_cd1', 'mst1_cd2', '', 3, true)" onblur="return FocusCD(this, 'mst_cd1', 'mst1_cd2', '', 3, true)" /></td>
							<th>倉庫CD</th>
							<td><input type="text" name="mst_cd1" value="<?php echo $mst_cd1; ?>" maxlength="4" onkeypress="return EnterFocusCD(this, 'mst_cd2', 'mst_cd1', '', 4, true)" onblur="return FocusCD(this, 'mst_cd2', 'mst_cd1', '', 4, true)" />
								~
								<input type="text" name="mst_cd2" value="<?php echo $mst_cd2; ?>" maxlength="4" onkeypress="return EnterFocusCD(this, 'interval', 'mst_cd2', '', 4, true)" onblur="return FocusCD(this, 'interval', 'mst_cd2', '', 4, true)" /></td>
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

					$query1 = "SELECT COUNT(*) AS x_count " . $query;

					$query = $wrk_query . $query;

					$query = $query . <<<QUERY_EOD
					ORDER BY $order_by
QUERY_EOD;

					$query0 = $query;

					$query = $query . <<<QUERY_EOD
					LIMIT $interval OFFSET ($page - 1) * $interval
QUERY_EOD;


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
					<input type="hidden" name="interval" value="<?php echo "$interval"; ?>" />
					<input type="hidden" name="mst1_cd1" value="<?php echo "$mst1_cd1"; ?>" />
					<input type="hidden" name="mst1_cd2" value="<?php echo "$mst1_cd2"; ?>" />
					<input type="hidden" name="mst_cd1" value="<?php echo "$mst_cd1"; ?>" />
					<input type="hidden" name="mst_cd2" value="<?php echo "$mst_cd2"; ?>" />

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

							for ($count=0; $count < count($m_arr2); $count++)
							{
								if ($m_arr2[$count] == "") {
									echo "<th>".$m_arr2[$count]."</th>";
								} else if ($m_arr1[$count] == "office_name") {
									echo "<th>".$m_arr2[$count]."<br><button type=\"submit\" name=\"order_by\" value=\"m1.".$m_arr1[$count]."\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m1.".$m_arr1[$count]." DESC\">↑</button></th>";
								} else if ($m_arr1[$count] == "group_name") {
									echo "<th>".$m_arr2[$count]."<br><button type=\"submit\" name=\"order_by\" value=\"m2.".$m_arr1[$count]."\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m2.".$m_arr1[$count]." DESC\">↑</button></th>";
								} else {
									echo "<th>".$m_arr2[$count]."<br><button type=\"submit\" name=\"order_by\" value=\"m.".$m_arr1[$count]."\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m.".$m_arr1[$count]." DESC\">↑</button></th>";
								}
							}
							echo "</tr>";

							// 1行毎の処理
							while($row = pg_fetch_assoc($res)){
								echo "<tr>";
								foreach ($m_arr1 as $val)
								{
									$echo_str = (string) $row[$val];
									echo "<td>".$echo_str."</td>";
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

