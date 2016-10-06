<?php
	include(dirname(__FILE__) . "/common.php");

	$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

	$arr1 = $_POST['arr1'];
	$arr2 = $_POST['arr2'];

	$query1 = str_replace("\'", "'", (string) $_POST['query']);

	// セルにフィールド名をセットする
	foreach ($arr2 as $val) {
		$csv .= mb_convert_encoding($val, 'SJIS-win', 'UTF-8').",";
	}

	$csv .= "\r\n";

	foreach ($conn_array as $key => $value) {
		$res = @pg_query($value, $query1);
		if (!$res) {
			echo("$key : Cannot execute SQL($query1)");
		} else {
			while($row = pg_fetch_assoc($res)) {
				// セルに値をセットする
				foreach ($arr1 as $val) {
//					$csv .= mb_convert_encoding(str_replace(",", "，", (string) $row[$val]), 'SJIS-win', 'UTF-8').",";
					$csv .= str_replace("?" ," " , mb_convert_encoding(str_replace("<br>", " " , str_replace(",", "，", (string) $row[$val])), 'SJIS-win', 'UTF-8')).",";
				}

				$csv .= "\r\n";
			}
		}
	}

	// ダウンロード * * * * * * * * * * * * * * * * * * *
	$page_name = (string) $_POST['page_name'];
	$output_name = $page_name.'_'.$today.'.csv';
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . $output_name);
	echo "$csv";
?>
