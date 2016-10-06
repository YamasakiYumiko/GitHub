<?php
	// DBへの接続 * * * * * * * * * * * * * * * * * * *
	function db_connect($dbname, $shop, $host, $password) {
		$conn = @pg_connect ( "host=$host dbname=$dbname user=postgres password=$password" );

		if (! $conn) {
			echo $shop . "サーバーに接続できませんでした。";
			// exit();
		} else {
			return $conn;
		}
	}

	// 接続先一覧
	$db_host_arr = array (
			"中間SV" => "192.168.11.213"
	);

	// DB接続関数を呼び出し
	foreach ( $db_host_arr as $key => $value ) {

		$conn_array [$key] = db_connect ( "jtc_headquarters", $key, $value, "jtc_kaihatsu" );
	}

	// 接続元確認 * * * * * * * * * * * * * * * * * * *


	//★注意★43SVで動かすときはこっちを有効にして
	$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$ip   = gethostbyname($hostname);

	//	$ip = $_SERVER["REMOTE_ADDR"]; //←これでは動かない
	//$ip = "192.168.1.50"; // この行はコメントアウト

	if (mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) ) == "192.168.5") {
		$sv_ip = mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) + 1 ) . "55";
	} else {
		$sv_ip = mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) + 1 ) . "50";
	}

	// ページング処理 * * * * * * * * * * * * * * * * * * *
	$page = $_GET ['page'];
	$interval = $_POST ['interval'];
	$page_all = ( int ) @$_POST ['page_all'];

	if ($interval <= 0) {
		$interval = 25;
	}
	if ($interval <= 0) {
		$interval = 1;
	}
	if (isset ( $_POST ['button_next'] )) {
		$page = ( int ) $_POST ['page'];

		if ($page < $page_all) {
			$page = $page + 1;
		}
	} else if (isset ( $_POST ['button_pre'] )) {
		$page = ( int ) $_POST ['page'] - 1;
	} else if (isset ( $_POST ['button_dl'] )) {
		$page = ( int ) $_POST ['page'];
	} else {
		$page = 1;
	}
	if ($page <= 0) {
		$page = 1;
	}

	// 表示件数 * * * * * * * * * * * * * * * * * * *
	$interval_array = array (
			25,
			50,
			100,
			200,
			500,
			1000
	);

	// csvファイルにつけるページ名 * * * * * * * * *
	$page_name = basename($_SERVER['PHP_SELF']);
	$page_name=str_replace("_s.php","",$page_name);

?>