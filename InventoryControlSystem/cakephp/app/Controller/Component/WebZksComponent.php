<?php
App::uses('Component', 'Controller');

class WebZksComponent extends Component {

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	public function getIP()
	{
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$ip   = gethostbyname($hostname);

		if (mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) ) == "192.168.5") {
			$sv_ip = mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) + 1 ) . "55";
		} else {
			$sv_ip = mb_strcut ( $ip, 0, mb_strrpos ( $ip, "." ) + 1 ) . "50";
		}

		return $sv_ip;
	}


	public function getWarehouse(){

		//本社の場合は全店舗+先頭に"すべて"を追加
		if ($this->getIP() == "192.168.1.50") {   //本社

			$strSql = <<<QUERY_EOD
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

			$wlist = $this->controller->Order->query($strSql);
			$wlist = array_reverse($wlist, true);
			$wlist[][0] =array('warehouse_cd' => '0000','warehouse_name'=>'すべて',);
			$wlist = array_reverse($wlist, true);

		}else{

			$strSql = <<<QUERY_EOD
	SELECT
		dept_cd AS warehouse_cd,
		shop_short_name AS warehouse_name,
		sv_ip
	FROM mst_shop_for_tool
	WHERE
		sv_ip = '$sv_ip';
QUERY_EOD;

			$wlist = $this->controller->Order->query($strSql);

			// 福岡港・福岡博多・イオン福岡の場合は別府・大村・熊本・臨時店を追加
			$shop_cd =array(Hash::extract($wlist, '{n}.0.warehouse_cd'));

			if(	($shop_cd[0][0] == '0209') or // 福岡港店
				($shop_cd[0][0] == '0219') or // 福岡博多店
				($shop_cd[0][0] == '0903'))   // イオン福岡店
			{
				$additems1 = array(array(array('warehouse_cd' => '0201','warehouse_name'=>'別府店',)));
				$additems2 = array(array(array('warehouse_cd' => '0218','warehouse_name'=>'大村店',)));
				$additems3 = array(array(array('warehouse_cd' => '0220','warehouse_name'=>'熊本店',)));
				$additems4 = array(array(array('warehouse_cd' => '0216','warehouse_name'=>'臨時店',)));

				$wlist = array_merge($wlist,$additems1,$additems2,$additems3,$additems4);
			}

		}

		return $wlist;

	}

	// 表示件数 * * * * * * * * * * * * * * * * * * *
	function getInterval(){
		return array(
				'25',
				'50',
				'100',
				'200',
				'500',
				'1000'
		);
	}



}
?>