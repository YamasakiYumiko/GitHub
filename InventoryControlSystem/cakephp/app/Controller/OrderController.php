<?php
App::uses('AppController', 'Controller');

/* 発注照会 */
class OrderController extends AppController{

	public $components = array('RequestHandler', 'Paginator','WebZks','Session');
	public $helper = array('Csv');
	public $paginate = array();


	// フォームから取得する値
	public $wNum;
	public $warehouse_cd1;
	public $stocked_flg;
	public $slip_no1;
	public $slip_no2;
	public $supplier_cd1;
	public $supplier_name1;
	public $item_cd1;
	public $item_cd2;
	public $item_name1;
	public $order_ymd1;
	public $order_ymd2;
	public $stock_ymd1;
	public $stock_ymd2;
	public $jan_cd1;
	public $interval_num;
	public $iNum;
	public $sFlg;	 // 検索ボタンフラグ（クリック時『1』）

	public $where;	 // クエリの条件


	//表示列
	public $arr1 = array (
			"row_no",
			"slip_no",
			// ,"order_kubun_cd"
			// ,"office_cd"
			// ,"warehouse_cd"
			// ,"warehouse_name"
			"order_ymd",
			// ,"stock_ymd"
			// ,"purchase_ymd"
			"d_purchase_ymd",
			// ,"staff_cd"
			// ,"staff_name"
			"supplier_cd",
			"supplier_name",
			// ,"payee_cd"
			// ,"payee_name"
			// ,"payment_kubun_cd"
			// ,"payment_date"
			// ,"payment_cd"
			// ,"payment_name"
			// ,"supplier_order_no"
			// ,"demand_no"
			// ,"hauler_cd"
			// ,"hauler_name"
			"slip_summary",
			// ,"purchase_summary"
			// ,"slip_unnecessary_flg"
			// ,"slip_target_flg"
			// ,"slip_immediately_flg"
			// ,"slip_timestamp"
			// ,"stocked_kubun_cd"
			// ,"purchased_kubun_cd"
			// ,"approval_status_kbn"
			// ,"created_cd"
			// ,"created_timestamp"
			// ,"modified_cd"
			// ,"modified_timestamp"
			// ,"d_slip_no"
			"d_detail_line_no",
			// ,"d_detail_order_kubun_cd"
			// ,"d_debt_kubun_cd"
			// ,"d_detail_warehouse_cd"
			"d_item_cd",
			"d_item_name",
			"d_jan_cd",
			"d_model_name",
			// ,"d_tax_rate_kubun_cd"
			// ,"d_unit_quantity"
			// ,"d_single_quantity"
			"d_quantity",
			// ,"d_temporary_price_kubun_cd"
			// ,"d_supplier_price"
			// ,"d_supplier_amount"
			// ,"d_supplier_consumption_tax"
			// ,"d_payee_order_no"
			// ,"d_stock_ymd"
			// ,"d_purchase_ymd"
			// ,"d_detail_summary"
			// ,"d_stocked_kubun_cd"
			"d_purchased_kubun_cd",
			// ,"d_stock_quantity_ex"
			// ,"d_stock_quantity"
			"d_purchase_quantity",
			// ,"d_cancel_flg"
			// ,"d_created_cd"
			// ,"d_created_timestamp"
			// ,"d_modified_cd"
			// ,"d_modified_timestamp"
			"warehouse_name"
	);

	public $arr2 = array (
			"NO",
			"伝票番号",
			// ,"発注区分"
			// ,"事業所CD"
			// ,"倉庫CD"
			// ,"倉庫名"
			"発注日",
			// ,"入荷予定日"
			"入荷予定日",
			// ,"担当者CD"
			// ,"担当者名"
			"仕入先CD",
			"仕入先名",
			// ,"支払先CD"
			// ,"支払先名"
			// ,"支払帳端区分"
			// ,"支払予定日"
			// ,"支払方法CD"
			// ,"支払方法"
			// ,"仕入先注文番号"
			// ,"案件番号"
			// ,"配送業者CD"
			// ,"配送業者名"
			"伝票摘要",
			// ,"発注書摘要"
			// ,"発注書不要フラグ"
			// ,"発注書発行対象フラグ"
			// ,"発注書即伝発行フラグ"
			// ,"発注書摘要発行日時"
			// ,"入荷完了区分"
			// ,"仕入完了区分"
			// ,"商品状態区分"
			// ,"作成担当者CD"
			// ,"作成日時"
			// ,"更新担当者CD"
			// ,"更新日時"
			// ,"伝票番号"
			"行",
			// ,"明細行番号"
			// ,"明細発注区分"
			// ,"債務科目区分"
			// ,"明細倉庫CD"
			"商品CD",
			"商品名",
			"JANCD",
			"商品名補足", // ,"規格"
			// ,"税率区分"
			// ,"明細荷数"
			// ,"明細バラ数"
			"発注数",
			// ,"仮単価区分"
			// ,"仕入単価"
			// ,"仕入金額"
			// ,"仕入消費税額"
			// ,"明細仕入先注文番号"
			// ,"明細入荷予定日"
			// ,"明細仕入予定日"
			// ,"明細摘要"
			// ,"入荷完了区分"
			"完納",
			// ,"仕入完了区分"
			// ,"入荷予定数"
			// ,"入荷数"
			"仕入済／発注残",
			// ,"仕入数"
			// ,"取消フラグ"
			// ,"作成担当者CD"
			// ,"作成日時"
			// ,"更新担当者CD"
			// ,"更新日時"
			"倉庫名"
	);

	public function beforeFilter() {
		parent::beforeFilter();

		// ドロップダウン
		$wlist = $this->WebZks->getWarehouse();
		$this->set('wlist', $wlist);

		$interval = $this->WebZks->getInterval();
		$this->set('interval', $interval);
	}

    public function download_csv() {

    	//デバッグ出力制御
    	//Configure::write('debug', 0);

    	$this->layout = false;


		// モデルにバーチャルフィールドを定義
		$this->Order->virtualFields = array(
				'order_by' =>$this->Session->read('order_by'),
				'min_day' =>$this->Session->read('min_day'),
				'where'	=> $this->Session->read('where'),
		);

    	// ダウンロードcsvファイルにつけるページ名 * * * * * * * * *
		$today = date("Ymd", mktime(0, 0, 0, date("m"), date("d"), date("Y")));

// 		$page_name = (string) $_POST['page_name'];
// 		$output_name = $page_name.'_'.$today.'.csv';
// 		header('Content-Type: application/octet-stream');
// 		header('Content-Disposition: attachment; filename=' . $output_name);

// 		$output_name = $today.'.csv';

//      $filename = $output_name;

		$filename ='test';

        // 表の一行目を設定
        $th = $this->arr2;

		// 表の内容を設定
        $col = $this->arr1;
        $td = $this->Order->query(str_replace("\'", "'",$this->Order->getData()));
//  		$this->set(compact('filename', 'th','col', 'td'));


//         $this->view->element('download_csv',compact('filename', 'th','col', 'td'));


        $view = new View($this, false);
        $view->set(compact('filename', 'th','col', 'td')); // set variables
        $view->viewPath = 'Elements'; // Viewの下のフォルダ名
        $html = $view->render('message'); // get the rendered markup




        $view->element('download_csv',compact('filename', 'th','col', 'td'));
    }

	// 初期表示
	public function orderList() {

			// 初期値設定
		$this->wNum='0';
		$this->stocked_flg= '0';
		$this->interval_num='25';
		$this->iNum='0';
		$this->sFlg='0';

		// ビューに値を渡す
		$this -> set('wNum',$this->wNum);
		$this -> set('stocked_flg',$this->stocked_flg);
		$this -> set('slip_no1',$this->slip_no1);
		$this -> set('slip_no2',$this->slip_no2);
		$this -> set('supplier_cd1',$this->supplier_cd1);
		$this -> set('supplier_name1',$this->supplier_name1);
		$this -> set('item_cd1',$this->item_cd1);
		$this -> set('item_cd2',$this->item_cd2);
		$this -> set('item_name1',$this->item_name1);
		$this -> set('order_ymd1',$this->order_ymd1);
		$this -> set('order_ymd2',$this->order_ymd2);
		$this -> set('stock_ymd1',$this->stock_ymd1);
		$this -> set('stock_ymd2',$this->stock_ymd2);
		$this -> set('jan_cd1',$this->jan_cd1);
		$this -> set('interval_num',$this->interval_num);
		$this -> set('iNum',$this->iNum);
		$this -> set('sFlg',$this->sFlg);


		$this->set('this', $this);

		// View設定
		$this->render('index');
	}


	public function search() {	//検索ボタンクリック
		global $wNum;
		global $warehouse_cd1;
		global $stocked_flg;
		global $slip_no1;
		global $slip_no2;
		global $supplier_cd1;
		global $supplier_name1;
		global $item_cd1;
		global $item_cd2;
		global $item_name1;
		global $order_ymd1;
		global $order_ymd2;
		global $stock_ymd1;
		global $stock_ymd2;
		global $jan_cd1;
		global $interval_num;
		global $iNum;
		global $sFlg;


		// 初期値設定
		$wNum='0';
		$stocked_flg= '0';
		$interval_num='25';
		$iNum='0';
		$sFlg='1';

		// 並び替え順序文字
		$order_by = ( string ) stripslashes(@$_GET ['order_by']);	// シングルクォートを勝手に書き換えないようにstripslashesを使用
		if ($order_by == null) {
			$order_by = "o.order_slip_no";
		}
		$min_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")) - 730*86400);

		$where = "";

		foreach ($this->request->query as $key => $val){
			if(!$val==null){
				$srcData[$key]=$val;
			}
		}

		if(isset($srcData['warehouse_cd1'])) {
			$wNum = $srcData['warehouse_cd1'];
		}
		if(isset($srcData['stocked_flg'])) {
			$stocked_flg = $srcData['stocked_flg'];
		}
		if(isset($srcData['slip_no1'])) {
			$slip_no1 = $srcData['slip_no1'];
		}
		if(isset($srcData['slip_no2'])) {
			$slip_no2 = $srcData['slip_no2'];
		}
		if(isset($srcData['supplier_cd1'])) {
			$supplier_cd1 = $srcData['supplier_cd1'];
		}
		if(isset($srcData['supplier_name1'])) {
			$supplier_name1 = $srcData['supplier_name1'];
		}
		if(isset($srcData['item_cd1'])) {
			$item_cd1 = $srcData['item_cd1'];
		}
		if (isset($srcData['item_cd2'])) {
			$item_cd2 = $srcData['item_cd2'];
		}
		if(isset($srcData['item_name1'])) {
			$item_name1 = $srcData['item_name1'];
		}
		if(isset($srcData['order_ymd1'])) {
			$order_ymd1 = $srcData['order_ymd1'];
		}
		if(isset($srcData['order_ymd2'])) {
			$order_ymd2 = $srcData['order_ymd2'];
		}
		if(isset($srcData['stock_ymd1'])) {
			$stock_ymd1 = $srcData['stock_ymd1'];
		}
		if(isset($srcData['stock_ymd2'])) {
			$stock_ymd2 = $srcData['stock_ymd2'];
		}
		if(isset($srcData['jan_cd1'])) {
			$jan_cd1 = $srcData['jan_cd1'];
		}
		if(isset($srcData['interval_num'])) {
			$iNum = $srcData['interval_num'];
		}

		// 本社の場合
		if ($this->WebZks->getIP() == "192.168.1.50"){			//本社の場合は先頭に"すべて"が追加されているので-1
			if($wNum != '0') {
				$warehouse_cd1 =$this->viewVars['wlist'][$wNum-1][0]['warehouse_cd'];

				$where = $where .= <<< EOM
    			AND o.warehouse_cd = '$warehouse_cd1'
EOM;
			}
			// 店舗の場合
		} else {
			if($wNum == '0') {
				// 初期表示＆選択を変えなかった場合
				$warehouse_cd1 =$this->viewVars['wlist'][0][0]['warehouse_cd'];
			}else{
				$warehouse_cd1 =$this->viewVars['wlist'][$wNum][0]['warehouse_cd'];
			}
			$where = $where .= <<< EOM
    			AND o.warehouse_cd = '$warehouse_cd1'
EOM;
		}

		// 初期表示＆選択を変えなかった場合
		if($stocked_flg=='0') {
			$where = $where .= <<< EOM
				AND od.purchased_kbn = '0'
EOM;
		} else {
			if ($stocked_flg!='All') {
				$where = $where .=
				<<< EOM
				AND od.purchased_kbn = '$stocked_flg'
EOM;
			}
		}

		if(($slip_no1 != "") && ($slip_no2 != "")) {
			$where = $where .= <<< EOM
			AND o.order_slip_no BETWEEN '$slip_no1' AND '$slip_no2'
EOM;
		} else if($slip_no1 != "") {
			$where = $where .= <<< EOM
			AND o.order_slip_no >= '$slip_no1'
EOM;
		} else if($slip_no2 != "") {
			$where = $where .= <<< EOM
			AND o.order_slip_no <= '$slip_no2'
EOM;
		}
		if($supplier_cd1 != "") {
			$where = $where .= <<< EOM
    		AND o.supplier_cd = '$supplier_cd1'
EOM;
		}
		if($supplier_name1 != "") {
			$where = $where .= <<< EOM
				AND msp.supplier_name LIKE '%$supplier_name1%'
EOM;
		}
		if(($item_cd1 != "") && ($item_cd2 != "")) {
			$where = $where .= <<< EOM
			AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) BETWEEN CAST(REPLACE('$item_cd1','D','') AS INTEGER) AND CAST(REPLACE('$item_cd2','D','') AS INTEGER))
EOM;
		}else if($item_cd1 != "") {
			$where = $where .= <<< EOM
    		AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) >= CAST(REPLACE('$item_cd1','D','') AS INTEGER))
EOM;
		}else if ($item_cd2 != "") {
			$where = $where .= <<< EOM
    		AND (od.item_cd ~ '^D[0-9]{1,}') AND (CAST(REPLACE(od.item_cd,'D','') AS INTEGER) <= CAST(REPLACE('$item_cd2','D','') AS INTEGER))
EOM;
		}
		if($item_name1 != "") {
			$where = $where .= <<< EOM
   			AND mi.item_name LIKE '%$item_name1%'
EOM;
		}
		if(($order_ymd1 != "") && ($order_ymd2 != "")) {
			$where = $where .= <<< EOM
			AND o.order_ymd BETWEEN '$order_ymd1' AND '$order_ymd2'
EOM;
		} else if ($order_ymd1 != "") {
			$where = $where .= <<< EOM
   			AND o.order_ymd >= '$order_ymd1'
EOM;
		}else if ($order_ymd2 != "") {
			$where = $where .= <<< EOM
   			AND o.order_ymd <= '$order_ymd2'
EOM;
		}
		if(($stock_ymd1 != "") && ($stock_ymd2 != "")) {

			$where = $where .= <<< EOM
			AND o.arrival_ex_ymd BETWEEN '$stock_ymd1' AND '$stock_ymd2'
EOM;
		} else if ($stock_ymd1 != "") {
			$where = $where .= <<< EOM
			AND o.arrival_ex_ymd >= '$stock_ymd1'
EOM;
		} else if ($stock_ymd2 !="") {
			$where = $where .= <<< EOM
			AND o.arrival_ex_ymd <= '$stock_ymd2'
EOM;
		}
		if( $jan_cd1 != "") {
			$where = $where .= <<< EOM
				AND mi.jan_cd = '$jan_cd1'
EOM;
		}
		if($iNum != '0') {
			$interval_num = $this->viewVars['interval'][$iNum];
		}

		// ビューに値を渡す
		$this -> set('wNum',$wNum);
		$this -> set('stocked_flg',$stocked_flg);
		$this -> set('slip_no1',$slip_no1);
		$this -> set('slip_no2',$slip_no2);
		$this -> set('supplier_cd1',$supplier_cd1);
		$this -> set('supplier_name1',$supplier_name1);
		$this -> set('item_cd1',$item_cd1);
		$this -> set('item_cd2',$item_cd2);
		$this -> set('item_name1',$item_name1);
		$this -> set('order_ymd1',$order_ymd1);
		$this -> set('order_ymd2',$order_ymd2);
		$this -> set('stock_ymd1',$stock_ymd1);
		$this -> set('stock_ymd2',$stock_ymd2);
		$this -> set('jan_cd1',$jan_cd1);
		$this -> set('interval_num',$interval_num);
		$this -> set('iNum',$iNum);
		$this -> set('sFlg',$sFlg);
		$this -> set('where',$where);

    	// セッションに保存
		$this->Session->write(array(
				'order_by' =>$order_by,
				'min_day' =>$min_day,
				'where'	=> $where,
		));


		// モデルにバーチャルフィールドを定義
		$this->Order->virtualFields = array(
				'order_by' =>$this->Session->read('order_by'),
				'min_day' =>$this->Session->read('min_day'),
				'where'	=> $this->Session->read('where'),
		);

// 		// モデルにバーチャルフィールドを定義
// 		$this->Order->virtualFields = array(
// 				'order_by' =>$order_by,
// 				'min_day' =>$min_day,
// 				'where'	=> $where,
// 		);



		// SQL
		$query = array(
				'order' => array('o.order_slip_no' => 'asc'),
				'limit' => $interval_num,
				'maxLimit' => 100,
				'extra' => array(
						'type' => $this->Order->getData(),
				),
		);

		// ページャー設定
		$this->Paginator->settings = $query;

		// データ取得
		$data = $this->Paginator->paginate('Order');

		// リストデータ
		$this -> set('arr1', $this->arr1);
		$this -> set('arr2', $this->arr2);
		$this -> set('data', $data);

		// View設定
		$this->render('index');

	}
}
