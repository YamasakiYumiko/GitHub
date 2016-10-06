<?php
	// DBへの接続
	function db_connect($dbname, $shop, $host, $password) {
		$conn = @pg_connect("host=$host dbname=$dbname user=postgres password=$password");

		if (!$conn) {
			echo $shop . "サーバーに接続できませんでした。";
			//exit();
		} else {
			return $conn;
		}
	}

	// 接続先一覧
	$db_host_arr = array (
			"中間SV" => "192.168.11.213"
	);

	// DB接続関数を呼び出し
	foreach ($db_host_arr as $key => $value) {
		$conn_array[$key] = db_connect("jtc_headquarters", $key, $value, "jtc_kaihatsu");
	}


	// ページング処理 * * * * * * * * * * * * * * * * * * *
	$page= $_GET ['page'];
	$interval = $_POST ['interval'];
	if ($interval <= 0) {
		$interval = 25;
	}
	if (isset ( $_POST ['button_next'] )) {
		$page = ( int ) $_POST ['page'] + 1;
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
	$page_name = basename($_SERVER['PHP_SELF'], ".php" );


?>