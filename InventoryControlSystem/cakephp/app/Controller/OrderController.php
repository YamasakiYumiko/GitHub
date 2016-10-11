<?php
App::uses('AppController', 'Controller');


/* 発注照会 */
class OrderController extends AppController{

	public $components = array('Paginator','WebZks');
	public $paginate = array();

	public function beforeFilter() {
		parent::beforeFilter();

		// ドロップダウン
		$wlist = $this->WebZks->getWarehouse();
		$this->set('wlist', $wlist);

		$interval = $this->WebZks->getInterval();
		$this->set('interval', $interval);
	}





	public function search() {

		$url['action'] = 'orderList';


		foreach ($this->request->data as $key => $val){
			$result = '';
			if(!$val==null){
				$url[$key]=$val;
			}
		}

		$this->redirect($url, null, true);

	}


    public function orderList() {


    	// 並び替え順序文字
    	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
    	if ($order_by == null) {
    		$order_by = "o.order_slip_no";
    	}
    	$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);

    	if (!isset($interval_num)){
    		$interval_num = '25';
    	}

    	$where = "";

    	if(isset($this->passedArgs['stocked_flg'])) {
    		$stocked_flg = $this->passedArgs['stocked_flg'];
    		$this->paginate['conditions'][]['stocked_flg'] = $stocked_flg;
    		$title[] = __('stocked_flg',true).': '.$stocked_flg;

    		$where = $where .= <<< EOM
				AND od.purchased_kbn = '$stocked_flg'
EOM;
    	}
    	if(isset($this->passedArgs['warehouse_cd1'])) {
    		$num = $this->passedArgs['warehouse_cd1'];
    		$warehouse_cd1 =$this->viewVars[wlist][$num][0][warehouse_cd];
    		$this->paginate['conditions'][]['warehouse_cd1'] = $warehouse_cd1;
    		$title[] = __('warehouse_cd1',true).': '.$warehouse_cd1;

    		$where = $where .= <<< EOM
    			AND o.warehouse_cd = '$warehouse_cd1'
EOM;
    	}
    	if((isset($this->passedArgs['order_ymd1'])) &&isset($this->passedArgs['order_ymd2'])) {

    		$where = $where .= <<< EOM
				AND o.order_ymd BETWEEN '$order_ymd1' AND '$order_ymd2'
EOM;
    	} else if(isset($this->passedArgs['order_ymd1'])) {
    		$order_ymd1 = $this->passedArgs['order_ymd1'];
    		$this->paginate['conditions'][]['order_ymd1'] = $order_ymd1;
    		$title[] = __('order_ymd1',true).': '.$order_ymd1;

    		$where = $where .= <<< EOM
    			AND o.order_ymd >= '$order_ymd1'
EOM;
    	}else if(isset($this->passedArgs['order_ymd2'])) {
    		$order_ymd2 = $this->passedArgs['order_ymd2'];
    		$this->paginate['conditions'][]['order_ymd2'] = $order_ymd2;
    		$title[] = __('order_ymd2',true).': '.$order_ymd2;

    		$where = $where .= <<< EOM
    			AND o.order_ymd <= '$order_ymd2'
EOM;		}
		if((isset($this->passedArgs['stock_ymd1'])) &&isset($this->passedArgs['stock_ymd2'])) {

			$where = $where .= <<< EOM
				AND o.arrival_ex_ymd BETWEEN '$stock_ymd1' AND '$stock_ymd2'
EOM;
		} else if(isset($this->passedArgs['stock_ymd1'])) {
    		$stock_ymd1 = $this->passedArgs['stock_ymd1'];
    		$this->paginate['conditions'][]['stock_ymd1'] = $stock_ymd1;
    		$title[] = __('stock_ymd1',true).': '.$stock_ymd1;

    		$where = $where .= <<< EOM
				AND o.arrival_ex_ymd >= '$stock_ymd1'
EOM;
		} else if(isset($this->passedArgs['stock_ymd2'])) {
    		$stock_ymd2 = $this->passedArgs['stock_ymd2'];
    		$this->paginate['conditions'][]['stock_ymd2'] = $stock_ymd2;
    		$title[] = __('stock_ymd2',true).': '.$stock_ymd2;

    		$where = $where .= <<< EOM
				AND o.arrival_ex_ymd <= '$stock_ymd2'
EOM;
		}
		if((isset($this->passedArgs['slip_no1'])) &&isset($this->passedArgs['slip_no2'])) {

    		$where = $where .= <<< EOM
				AND o.order_slip_no BETWEEN '$slip_no1' AND '$slip_no2'
EOM;
		}else if(isset($this->passedArgs['slip_no1'])) {
				$slip_no1 = $this->passedArgs['slip_no1'];
				$this->paginate['conditions'][]['slip_no1'] = $slip_no1;
				$title[] = __('slip_no1',true).': '.$slip_no1;

    		$where = $where .= <<< EOM
				AND o.order_slip_no >= '$slip_no1'
EOM;
		}else if(isset($this->passedArgs['slip_no2'])) {
				$slip_no2 = $this->passedArgs['slip_no2'];
				$this->paginate['conditions'][]['slip_no2'] = $slip_no2;
				$title[] = __('slip_no2',true).': '.$slip_no2;

    		$where = $where .= <<< EOM
				AND o.order_slip_no <= '$slip_no2'
EOM;
		}

		if((isset($this->passedArgs['item_cd1'])) &&isset($this->passedArgs['item_cd2'])) {
			$where = $where .= <<< EOM
				AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
EOM;
		}else if(isset($this->passedArgs['item_cd1'])) {
    		$item_cd1 = $this->passedArgs['item_cd1'];
    		$this->paginate['conditions'][]['item_cd1'] = $item_cd1;
    		$title[] = __('item_cd1',true).': '.$item_cd1;

			$where = $where .= <<< EOM
	    		AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
EOM;
		}else if (isset($this->passedArgs['item_cd2'])) {
    		$item_cd2 = $this->passedArgs['item_cd2'];
    		$this->paginate['conditions'][]['item_cd2'] = $item_cd2;
    		$title[] = __('item_cd2',true).': '.$item_cd2;

    		$where = $where .= <<< EOM
	    		AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
EOM;
		}
    	if(isset($this->passedArgs['item_name1'])) {
    		$item_name1 = $this->passedArgs['item_name1'];
    		$this->paginate['conditions'][]['item_name1'] = $item_name1;
    		$title[] = __('item_name1',true).': '.$item_name1;


    		$where = $where .= <<< EOM
    			AND mi.item_name LIKE '%$item_name1%'
EOM;
    	}
    	if(isset($this->passedArgs['jan_cd1'])) {
    		$jan_cd1 = $this->passedArgs['jan_cd1'];
    		$this->paginate['conditions'][]['jan_cd1'] = $jan_cd1;
    		$title[] = __('jan_cd1',true).': '.$jan_cd1;

    		$where = $where .= <<< EOM
					AND mi.jan_cd = '$jan_cd1'
EOM;
    	}
    	if(isset($this->passedArgs['supplier_cd1'])) {
    		$supplier_cd1 = $this->passedArgs['supplier_cd1'];
    		$this->paginate['conditions'][]['supplier_cd1'] = $supplier_cd1;
    		$title[] = __('supplier_cd1',true).': '.$supplier_cd1;

    		$where = $where .= <<< EOM
    			AND o.supplier_cd = '$supplier_cd1'
EOM;
    	}
    	if(isset($this->passedArgs['supplier_name1'])) {
    		$supplier_name1 = $this->passedArgs['supplier_name1'];
    		$this->paginate['conditions'][]['supplier_name1'] = $supplier_name1;
    		$title[] = __('supplier_name1',true).': '.$supplier_name1;


    		$where = $where .= <<< EOM
					AND msp.supplier_name LIKE '%$supplier_name1%'
EOM;
    	}
    	if(isset($this->passedArgs['interval_num'])) {
    		$num = $this->passedArgs['interval_num'];
    		$interval_num = $this->viewVars[interval][$num];
    		$this->paginate['conditions'][]['interval_num'] = $interval_num;
    		$title[] = __('interval_num',true).': '.$interval_num;
    	}




// 		// モデルにバーチャルフィールドを定義
// 		$this->Order->virtualFields = array(
// 				'order_by'	=> $order_by,
// 				'min_day'	=> $min_day,
// 				'interval'	=> $interval,
// 				'stocked_flg'	=> $stocked_flg,
// 				'warehouse_cd1'	=> $warehouse_cd1,
// 				'order_ymd1'	=> $order_ymd1,
// 				'order_ymd2'	=> $order_ymd2,
// 				'stock_ymd1'	=> $stock_ymd1,
// 				'stock_ymd2'	=> $stock_ymd2,
// 				'slip_no1'	=> $slip_no1,
// 				'slip_no2'	=> $slip_no2,
// 				'item_cd1'	=> $item_cd1,
// 				'item_cd2'	=> $item_cd2,
// 				'item_name1'	=> $item_name1,
// 				'jan_cd1'	=> $jan_cd1,
// 				'supplier_cd1'	=> $supplier_cd1,
// 				'supplier_name1'	=> $supplier_name1,
// 				'interval'	=> $interval,
// 				'order_by'	=> $order_by,
// 				'min_day'	=> $min_day,
// 		);


		// SQL
		$query = array(
				'order' => array('o.order_slip_no' => 'asc'),
				'limit' => $interval_num,
				'maxLimit' => 100,
				'extra' => array(
						'type' => $this->Order->getData($order_by,$min_day,$where),
				),
		);




    	// ページャー設定
    	$this->Paginator->settings = $query;
    	// データ取得
    	$data = $this->Paginator->paginate('Order');

    	// リストデータ
    	$this->set('data', $data);

    	// View設定
    	$this->render('index');

    }

}