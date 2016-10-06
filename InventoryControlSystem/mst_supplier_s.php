<?php
	// 並び替え順序文字
	$order_by = (string) @$_POST['order_by'];
	if ($order_by == null) {
		$order_by = "m.supplier_cd";
	}

	$mst_cd1 = (string) @$_POST['mst_cd1'];
	$mst_cd2 = (string) @$_POST['mst_cd2'];

	$supplier_name1 = (string) @$_POST['supplier_name1'];

	$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$m_arr1 = array(
			"row_no"
			,"supplier_cd"
			,"supplier_name"
	//				,"supplier_short_name"
			,"supplier_kana"
	//				,"zipcode1"
	//				,"zipcode2"
	//				,"address1"
	//				,"address2"
	//				,"address3"
	//				,"address4"
	//				,"address5"
			,"tel"
	//				,"tel1_1"
	//				,"tel1_2"
	//				,"tel1_3"
	//				,"inner_tel1"
	//				,"tel2_1"
	//				,"tel2_2"
	//				,"tel2_3"
	//				,"inner_tel2"
			,"fax"
	//				,"fax1_1"
	//				,"fax1_2"
	//				,"fax1_3"
	//				,"group_name"
	//				,"post_name"
	//				,"staff_name"
	//				,"mail_address"
	//				,"payee_cd"
	//				,"payee_name"
	//				,"staff_cd"
	//				,"staff_name1"
	//				,"stock_office_cd"
	//				,"stock_office_name"
	//				,"sundry_flg"
	//				,"slip_unnecessary_flg"
	//				,"slip_immediately_kubun_cd"
	//				,"dm_unnecessary_flg"
	//				,"stock_recorded_source"
	//				,"purchase_record_standard"
	//				,"debt_kubun_cd"
	//				,"detal_span_st"
	//				,"detal_span_ed"
	//				,"created_cd"
	//				,"created_timestamp"
	//				,"modified_cd"
	//				,"modified_timestamp"
	);

	$m_arr2 = array(
			""
			,"仕入先CD"
			,"仕入先正式名"
			//				,"仕入先名"
			,"仕入先カナ名"
			//				,"郵便番号１"
	//				,"郵便番号２"
	//				,"住所１"
	//				,"住所２"
	//				,"住所３"
	//				,"住所４"
	//				,"住所５"
			,"電話番号"
	//				,"電話番号１－１"
	//				,"電話番号１－２"
	//				,"電話番号１－３"
	//				,"内線番号１"
	//				,"電話番号２-１"
	//				,"電話番号２-２"
	//				,"電話番号２-３"
	//				,"内線番号２"
			,"FAX番号"
	//				,"FAX番号１－１"
	//				,"FAX番号１－２"
	//				,"FAX番号１－３"
	//				,"担当部署名"
	//				,"担当役職名"
	//				,"担当者名"
	//				,"メールアドレス"
	//				,"支払先CD"
	//				,"支払先名"
	//				,"担当者CD"
	//				,"担当者名"
	//				,"在庫管理事業所CD"
	//				,"在庫管理事業所名"
	//				,"諸口フラグ"
	//				,"発注書不要フラグ"
	//				,"発注書即伝区分"
	//				,"DM不要フラグ"
	//				,"入荷入力計上元"
	//				,"仕入計上基準"
	//				,"債務科目区分"
	//				,"取引開始日"
	//				,"取引終了日"
	//				,"作成担当者CD"
	//				,"作成日時"
	//				,"更新担当者CD"
	//				,"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM mst_obic_supplier
QUERY_EOD;

// クエリ作成
	$query = <<<QUERY_EOD
	SELECT
QUERY_EOD;

	foreach ( $m_arr1 as $val ) {
		if ($val == "row_no") {
			$query .= " ROW_NUMBER() OVER(ORDER BY " . $order_by . ") AS row_no,";
		} else if ($val == "payee_name") {
			$query .= "m1." . $val . " AS " . $val . ",";
		} else if ($val == "staff_name1") {
			$query .= "m2.staff_name AS " . $val . ",";
		} else if ($val == "stock_office_name") {
			$query .= "m3.office_name AS " . $val . ",";
		} else if ($val == "created_timestamp" || $val == "modified_timestamp") {
			$query .= "TO_CHAR(m." . $val . ", 'YYYY-MM-DD HH24:MI:SS') AS " . $val . ",";
		} else if ($val == "tel" || $val == "fax") {
			$query .= "m." . $val . "1_1 || '-' || m." . $val . "1_2 || '-' || m." . $val . "1_3 AS " . $val . ",";
		} else {
			$query .= "m." . $val . " AS " . $val . ",";
		}
	}
	$query .= "''";

	$wrk_query = $query;

	$query = <<<QUERY_EOD
		FROM mst_obic_supplier as m
		LEFT JOIN mst_obic_payee as m1 ON
			m1.payee_cd = m.payee_cd
		LEFT JOIN (
			SELECT
				mm1.staff_cd,
				mm1.staff_name
			FROM (
				SELECT
					staff_cd,
					MAX(revision_date) AS revision_date
				FROM mst_obic_staff
				GROUP BY staff_cd
			) AS mm0
			INNER JOIN mst_obic_staff AS mm1 ON
				mm1.staff_cd = mm0.staff_cd AND
				mm1.revision_date = mm0.revision_date
		) as m2 ON
			m2.staff_cd = m.staff_cd
		LEFT JOIN mst_obic_office as m3 ON
			m3.office_cd = m.stock_office_cd
		WHERE
			m.supplier_cd LIKE '11%'
QUERY_EOD;

	// CD
	if ($mst_cd1 != '' && $mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
						AND m.supplier_cd BETWEEN '$mst_cd1' AND '$mst_cd2'
QUERY_EOD;
	} else if ($mst_cd1 != '') {
		$query = $query . <<<QUERY_EOD
						AND m.supplier_cd >= '$mst_cd1'
QUERY_EOD;
	} else if ($mst_cd2 != '') {
		$query = $query . <<<QUERY_EOD
						AND m.supplier_cd <= '$mst_cd2'
QUERY_EOD;
	}

	// 仕入先名
	if ($supplier_name1 != '') {
		$query = $query . <<<QUERY_EOD
						AND m.supplier_name LIKE '%$supplier_name1%'
QUERY_EOD;
	}
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

	<h2>仕入先マスタ一覧（店舗用）</h2>

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
							<th>仕入先CD</th>
							<td><input type="text" name="mst_cd1"
									value="<?php echo $mst_cd1; ?>" maxlength="10"
									onkeypress="return EnterFocusCD(this, 'mst_cd2', 'mst_cd1', '', 10, true)"
									onblur="return FocusCD(this, 'mst_cd2', 'mst_cd1', '', 10, true)" />
								~ <input type="text" name="mst_cd2"
									value="<?php echo $mst_cd2; ?>" maxlength="10"
									onkeypress="return EnterFocusCD(this, 'supplier_name1', 'mst_cd2', '', 10, true)"
									onblur="return FocusCD(this, 'supplier_name1', 'mst_cd2', '', 10, true)" /></td>
							<th>仕入先名</th>
							<td><input type="text" name="supplier_name1" value="<?php echo $supplier_name1; ?>" onkeypress="return EnterFocus(this, 'interval')" /></td>
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
					<input type="hidden" name="mst_cd1"
						value="<?php echo "$mst_cd1"; ?>" />
					<input type="hidden" name="mst_cd2"
						value="<?php echo "$mst_cd2"; ?>" />
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
							for($count = 0; $count < count ( $m_arr2 ); $count ++) {
								if ($m_arr2 [$count] == "") {
									echo "<th>" . $m_arr2 [$count] . "</th>";
								} else if ($m_arr1 [$count] == "payee_name") {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m1." . $m_arr1 [$count] . " ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m1." . $m_arr1 [$count] . " DESC\">↑</button></th>";
								} else if ($m_arr1 [$count] == "staff_name1") {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m2." . "staff_name" . " ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m2." . "staff_name" . " DESC\">↑</button></th>";
								} else if ($m_arr1 [$count] == "stock_office_name") {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m3." . "office_name" . " ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m3." . "office_name" . " DESC\">↑</button></th>";
								} else if ($m_arr1 [$count] == "tel" || $m_arr1 [$count] == "fax") {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . "1_1 ASC, m." . $m_arr1 [$count] . "1_2 ASC, m." . $m_arr1 [$count] . "1_3 ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . "1_1 DESC, m." . $m_arr1 [$count] . "1_2 DESC, m." . $m_arr1 [$count] . "1_3 DESC\">↑</button></th>";
								} else {
									echo "<th>" . $m_arr2 [$count] . "<br><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . " ASC\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m." . $m_arr1 [$count] . " DESC\">↑</button></th>";
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

