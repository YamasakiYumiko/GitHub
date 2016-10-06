<?php
App::uses('AppController', 'Controller');


/* 発注照会 */
class OrderController extends AppController{

	public $uses   = array('PaginateOrigin', 'Order');
	public $components = array('Paginator');

    public function entryList() {

    	// 検索データがある場合、Where句に設定
    	$where = null;
//     	if ($this->data['search']) {
//     		$where = $this->data['search'];
//     	}

    	// 並び替え順序文字
    	$order_by = ( string ) stripslashes(@$_POST ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用

    	if ($order_by == null) {
    		$order_by = "o.order_slip_no";
    	}

    	$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);


    	// SQL
    	$query = array(
    			'order' => array('o.order_slip_no' => 'asc'),
    			'limit' => 25,

    			'maxLimit' => 100,

    			'extra' => array(
    					'type' => $this->Order->getData($order_by,$min_day,$where),
    			),
    	);


    	// ページャー設定
    	$this->Paginator->settings = $query;
    	// データ取得
    	$data = $this->Paginator->paginate('PaginateOrigin');

    	// リストデータ
    	$this->set('data', $data);

    	// View設定
    	$this->render('Order/index');


    }




}