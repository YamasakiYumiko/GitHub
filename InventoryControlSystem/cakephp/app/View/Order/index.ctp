<h1>発注照会</h1>
<?php
// js 読み込み
echo $this->Html->script('WebZks', array('inline' => false));

// 表示列
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


$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));





?>

	<div id="topscreen">
	<div id="search_box">
		<div class="searchFormContainer container-fluid">

			<?php echo $this->Form->create('Order',array('url'=> array('controller' => 'Order','action' => 'search'),'inputDefaults'=>array('label' => false, 'div' => false ,'hiddenField'=>false, 'legend'=>false, 'disabled'=>false, 'escape' => false))); ?>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>倉庫</label></div>
					<div class="col-xs-12 col-sm-3 col-form">
						<?php
							echo $this->Form->input('warehouse_cd1',
									array(  'type' => 'select',
											'options' =>(Hash::extract($wlist, '{n}.0.warehouse_name')),
											'name' =>'warehouse_cd1',
											'onkeypress' => "return EnterFocusR(this, 'stocked_flg')",
										)
									);
						?>
					</div>
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>入庫状態</label></div>
					<div class="col-xs-12 col-sm-4 col-form">
						<?php
							echo $this->Form->input('stocked_flg',
									array(  'type' => 'radio',
											'options' => array('All'=>'全て','0'=>'未入庫のみ'),
											'name' =>'stocked_flg',
											'value'=>'0',
											'separator'=>"&nbsp;&nbsp;&nbsp;",
											'onkeypress' => "return EnterFocus(this, 'slip_no1')",
										)
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>伝票NO</label></div>
					<div class="col-xs-12 col-sm-6 col-form">
						<?php
							echo $this->Form->input('slip_no1',
									array(  'type' => 'text',
											'name' =>'slip_no1',
											'size' =>'12',
											'maxlength' =>'12',
											'onkeypress' => "return EnterFocus(this, 'slip_no2')",
										)
									);
						?>
						～
						<?php
							echo $this->Form->input('slip_no2',
									array(  'type' => 'text',
										  	'name' =>'slip_no2',
											'size' =>'12',
											'maxlength' =>'12',
											'onkeypress' => "return EnterFocus(this, 'supplier_cd1')",
										  )
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>仕入先CD</label></div>
					<div class="col-xs-12 col-sm-2 col-form">
						<?php
							echo $this->Form->input('supplier_cd1',
									array(  'type' => 'text',
											'name' =>'supplier_cd1',
// 											'value'=>$supplier_cd1,
											'size' =>'10',
											'maxlength' =>'10',
											'onkeypress' => "return EnterFocusCD(this, 'supplier_name1', 'supplier_cd1', '', 10, true)",
											'onblur'=>"return FocusCD(this, 'supplier_name1', 'supplier_cd1', '', 10, true)",
										)
									);
						?>
					</div>
					<div class="col-xs-12 col-sm-3 searchformlabel"><label>仕入先名</label></div>
					<div class="col-xs-12 col-sm-2 col-form">
						<?php
							echo $this->Form->input('supplier_name1',
									array(  'type' => 'text',
											'name' =>'supplier_name1',
											'size' =>'12',
											'maxlength' =>'12',
											'onkeypress' => "return EnterFocus(this, 'item_cd1')",
										)
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>商品CD</label></div>
					<div class="col-xs-12 col-sm-4 col-form">
						<?php
							echo $this->Form->input('item_cd1',
									array(  'type' => 'text',
											'name' =>'item_cd1',
// 											'value'=> $item_cd1,
											'size' =>'7',
											'maxlength' =>'7',
											'onkeypress' => "return EnterFocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)",
											'onblur'=> "return FocusCD(this, 'item_cd2', 'item_cd1', 'D', 7, false)",
										  )
									);
						?>
						～
						<?php
							echo $this->Form->input('item_cd2',
									array(  'type' => 'text',
											'name' =>'item_cd2',
// 											'value'=> $item_cd2,
											'size' =>'7',
											'maxlength' =>'7',
											'onkeypress' => "return EnterFocusCD(this, 'item_name1', 'item_cd2', 'D', 7, false)",
											'onblur'=>  "return FocusCD(this, 'item_name1', 'item_cd2', 'D', 7, false)",
										  )
									);
						?>
					</div>
					<div class="col-xs-12 col-sm-1 searchformlabel"><label>商品名</label></div>
					<div class="col-xs-12 col-sm-3 col-form">
						<?php
							echo $this->Form->input('item_name1',
									array(  'type' => 'text',
											'name' =>'item_name1',
											'onkeypress' => "return EnterFocus(this, 'order_ymd1')",
										  )
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>発注日</label></div>
					<div class="col-xs-12 col-sm-6 col-form">
						<?php
							echo $this->Form->input('order_ymd1',
									array(  'type' => 'text',
											'name' =>'order_ymd1',
// 											'value'=> $order_ymd1,
											'size' =>'10',
											'maxlength' =>'10',
											'onkeypress' => "return EnterFocusDate(this, 'order_ymd2', 'order_ymd1', '')",
											'onblur'=> "return FocusDate(this, 'order_ymd2', 'order_ymd1', '')",
										  )
									);
						?>
						～
						<?php
							echo $this->Form->input('order_ymd2',
									array(  'type' => 'text',
											'name' =>'order_ymd2',
// 											'value'=> $order_ymd2,
											'size' =>'10',
											'maxlength' =>'10',
											'onkeypress' => "return EnterFocusDate(this, 'stock_ymd1', 'order_ymd2', '')",
											'onblur'=> "return FocusDate(this, 'stock_ymd1', 'order_ymd2', '')",
										  )
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>入荷予定日</label></div>
					<div class="col-xs-12 col-sm-6 col-form">
						<?php
							echo $this->Form->input('stock_ymd1',
									array(  'type' => 'text',
											'name' =>'stock_ymd1',
// 											'value'=> $stock_ymd1,
											'size' =>'10',
											'maxlength' =>'10',
											'onkeypress' => "return EnterFocusDate(this, 'stock_ymd2', 'stock_ymd1', '')",
											'onblur'=> "return FocusDate(this, 'stock_ymd2', 'stock_ymd1', '')",
										  )
									);
						?>
						～
						<?php
							echo $this->Form->input('stock_ymd2',
									array(  'type' => 'text',
											'name' =>'stock_ymd2',
// 											'value'=> $stock_ymd2,
											'size' =>'10',
											'maxlength' =>'10',
											'onkeypress' => "return EnterFocusDate(this, 'jan_cd1', 'stock_ymd2', '')",
											'onblur'=> "return FocusDate(this, 'jan_cd1', 'stock_ymd2', '')",
										  )
									);
						?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12">
					<div class="col-xs-12 col-sm-2 searchformlabel"><label>JANCD</label></div>
					<div class="col-xs-12 col-sm-2 ">
						<?php
							echo $this->Form->input('jan_cd1',
									array(  'type' => 'text',
											'name' =>'jan_cd1',
// 											'value'=> $jan_cd1,
											'size' =>'13',
											'maxlength' =>'13',
											'onkeypress' => "return EnterFocusCD(this, 'interval', 'jan_cd1', '', 13, false)",
											'onblur'=> "return FocusCD(this, 'interval', 'jan_cd1', '', 13, false)",
										  )
									);
						?>
					</div>
					<div class="col-xs-12 col-sm-4 searchformlabel"><label>表示件数</label></div>
					<div class="col-xs-12 col-sm-1 ">
						<?php
							echo $this->Form->input('interval_num',
									array(  'type' => 'select',
											'options' => $interval,
											'name' =>'interval_num',
											'onkeypress' => "return EnterFocus(this, 'button')",
										  )
									);
						?>
					</div>
					<div class="col-xs-12 col-sm-2 ">
						<?php
							echo $this->Form->end(
									array(
											'label' => '検索',
											'id' =>'submit_button',
											'div' => false,
											'value' => "検索",
											'escape' => true,
											)
									);
						?>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php
	echo "<div id ='pagenavi'>";
	if($this->Paginator->counter(array('format'=>'%page%')) != '0') {
		echo $this->Paginator->counter(array('format' => __('総件数  {:count}件')));
		echo "&nbsp";
		echo "ページ数：";
		echo $this->Paginator->counter(array('format' => '%page% / %pages% '));
	if($this->Paginator->counter(array('format'=>'%pages%')) != '1') {
		echo $this->Paginator->prev('<< ' . '前へ', array(), null, array());
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next('次へ' . ' >>', array(), null, array());
		}
	}
	echo "</div>";

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
?>


