<?php
	// 並び替え順序文字
	$order_by = (string) @$_POST['order_by'];
	if ($order_by == null) {
		$order_by = "m.payee_cd";
	}

	$mst_cd1 = (string) @$_POST['mst_cd1'];
	$mst_cd2 = (string) @$_POST['mst_cd2'];

	$payee_name1 = (string) @$_POST['payee_name1'];

	$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$m_arr1 = array (
			"row_no",
			"payee_cd",
			"payee_name",
			"payee_short_name",
			"payee_kana",
			"zipcode1",
			"zipcode2",
			"address1",
			"address2",
			"address3",
			"address4",
			"address5",
			"tel1_1",
			"tel1_2",
			"tel1_3",
			"inner_tel1",
			"tel2_1",
			"tel2_2",
			"tel2_3",
			"inner_tel2",
			"fax1_1",
			"fax1_2",
			"fax1_3",
			"group_name",
			"post_name",
			"staff_name",
			"mail_address",
			"corporation_cd",
			"payment_payee_cd",
			"staff_cd",
			"staff_name1",
			"account_custmer_cd",
			"created_cd",
			"created_timestamp",
			"modified_cd",
			"modified_timestamp"
	);

	$m_arr2 = array (
			"",
			"支払先CD",
			"支払先正式名",
			"支払先名",
			"支払先カナ名",
			"郵便番号１",
			"郵便番号２",
			"住所１",
			"住所２",
			"住所３",
			"住所４",
			"住所５",
			"電話番号１－１",
			"電話番号１－２",
			"電話番号１－３",
			"内線番号１",
			"電話番号２-１",
			"電話番号２-２",
			"電話番号２-３",
			"内線番号２",
			"FAX番号１－１",
			"FAX番号１－２",
			"FAX番号１－３",
			"担当部署名",
			"担当役職名",
			"担当者名",
			"メールアドレス",
			"法人CD",
			"出金支払先CD",
			"担当者CD",
			"担当者名",
			"会計用取引先CD",
			"作成担当者CD",
			"作成日時",
			"更新担当者CD",
			"更新日時"
	);

	// クエリ作成
	$query2 = <<<QUERY_EOD
	SELECT
		TO_CHAR(MAX(output_timestamp), 'YYYY-MM-DD HH24:MI') As output_timestamp
	FROM mst_obic_payee
QUERY_EOD;

// クエリ作成
	$query = <<<QUERY_EOD
	SELECT
QUERY_EOD;

	foreach ($m_arr1 as $val)
	{
		if ($val == "row_no") {
			$query .= " ROW_NUMBER() OVER(ORDER BY ".$order_by.") AS row_no,";
		} else if ($val == "staff_name1") {
			$query .= "m1.staff_name AS ".$val.",";
		} else if ($val == "created_timestamp" || $val == "modified_timestamp") {
			$query .= "TO_CHAR(m.".$val.", 'YYYY-MM-DD HH24:MI:SS') AS ".$val.",";
		} else {
			$query .= "m.".$val." AS ".$val.",";
		}
	}

	$query .= "''";

	$wrk_query = $query;

	$query = <<<QUERY_EOD
	FROM mst_obic_payee as m
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
	) as m1 ON
		m1.staff_cd = m.staff_cd
	WHERE
		TRUE
QUERY_EOD;

	// CD
	if ($mst_cd1 != '' && $mst_cd2 != '') {
		$query = $query. <<<QUERY_EOD
		AND m.payee_cd BETWEEN '$mst_cd1' AND '$mst_cd2'
QUERY_EOD;
	} else if ($mst_cd1 != '') {
	$query = $query. <<<QUERY_EOD
		AND m.payee_cd >= '$mst_cd1'
QUERY_EOD;
	} else if ($mst_cd2 != '') {
	$query = $query. <<<QUERY_EOD
		AND m.payee_cd <= '$mst_cd2'
QUERY_EOD;
	}
	// 支払先名
	if ($payee_name1 != '') {
		$query = $query . <<<QUERY_EOD
						AND m.payee_name LIKE '%$payee_name1%'
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
	<h2>支払先マスタ一覧</h2>

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
							<th>支払先CD</th>
							<td><input type="text" name="mst_cd1" value="<?php echo $mst_cd1; ?>" maxlength="10" onkeypress="return EnterFocusCD(this, 'mst_cd2', 'mst_cd1', '', 10, true)" onblur="return FocusCD(this, 'mst_cd2', 'mst_cd1', '', 10, true)" />
								~
								<input type="text" name="mst_cd2" value="<?php echo $mst_cd2; ?>" maxlength="10" onkeypress="return EnterFocusCD(this, 'payee_name1', 'mst_cd2', '', 10, true)" onblur="return FocusCD(this, 'payee_name1', 'mst_cd2', '', 10, true)" /></td>
							<th>支払先名</th>
							<td><input type="text" name="payee_name1" value="<?php echo $payee_name1; ?>" onkeypress="return EnterFocus(this, 'interval')" /></td>
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
							for ($count=0; $count < count($m_arr2); $count++)
							{
								if ($m_arr2[$count] == "") {
									echo "<th nowrap>".$m_arr2[$count]."</th>";
								} else if ($m_arr1[$count] == "staff_name1") {
									echo "<th nowrap>".$m_arr2[$count]."<br><button type=\"submit\" name=\"order_by\" value=\"m1."."staff_name"."\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m1."."staff_name"." DESC\">↑</button></th>";
								} else {
									echo "<th nowrap>".$m_arr2[$count]."<br><button type=\"submit\" name=\"order_by\" value=\"m.".$m_arr1[$count]."\">↓</button><button type=\"submit\" name=\"order_by\" value=\"m.".$m_arr1[$count]." DESC\">↑</button></th>";
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