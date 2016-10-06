<h1>一覧表示</h1>
<?php


$arr1 = array (
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

$arr2 = array (
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
	echo "<div class ='resultbox'>";

	echo "<table>";
	echo $this->Html->tableHeaders($arr2);
	foreach($data as $result){
		$val = array();
		foreach($arr1 as $arr){
			array_push($val,$result[0][$arr]);
		}
		echo $this->Html->tableCells($val);
	}
	echo "</table>";
	echo "</div>";


	echo $this->Html->url(array('controller' => 'Order', 'action' => 'entryList')) . '/page:1/sort:PaginateOrigin.slip_no/direction:asc';







?>


