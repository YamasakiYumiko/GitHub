<?php
App::uses('AppModel', 'Model');

class PaginateOrigin extends AppModel {
	public $name = 'PaginateOrigin';
	public $useTable = false;

	//ソートに使用する列
	public $virtualFields = array(
			'slip_no'				=> 'PaginateOrigin.slip_no',
			'order_ymd' 			=> 'PaginateOrigin.order_ymd',
			'd_purchase_ymd'		=> 'PaginateOrigin.d_purchase_ymd',
			'supplier_cd' 			=> 'PaginateOrigin.supplier_cd',
			'supplier_name' 		=> 'PaginateOrigin.supplier_name',
			'slip_summary' 			=> 'PaginateOrigin.slip_summary',
			'd_detail_line_no' 		=> 'PaginateOrigin.d_detail_line_no',
			'd_item_cd' 			=> 'PaginateOrigin.d_item_cd',
			'd_item_name' 			=> 'PaginateOrigin.d_item_name',
			'd_jan_cd' 				=> 'PaginateOrigin.d_jan_cd',
			'd_model_name' 			=> 'PaginateOrigin.d_model_name',
			'd_quantity' 			=> 'PaginateOrigin.d_quantity',
			'd_purchased_kubun_cd' 	=> 'PaginateOrigin.d_purchased_kubun_cd',
			'd_purchase_quantity' 	=> 'PaginateOrigin.d_purchase_quantity',
			'warehouse_name' 		=> 'PaginateOrigin.warehouse_name',
	);

	/**
	 * ページネート実行 未審査取得
	 */
	public function paginate() {
		$condition = func_get_arg(0);
		$fileds    = func_get_arg(1);
		$order     = func_get_arg(2);
		$limit     = func_get_arg(3);
		$page      = func_get_arg(4);
		$recursive = func_get_arg(5);
		$extra     = func_get_arg(6);

		// SQL文
		$sql = $extra['extra']['type'];
		if (count($order) > 0) {
			$strOrderSql = '';
			$numCnt = 0;
			foreach ($order as $key => $value) {
				$keys = explode('.', $key);
				$key = $keys[1];
				if ($numCnt > 0) {
					$strOrderSql .= ',' . $key . ' ' . $value;
				} else {
					$strOrderSql .= $key . ' ' . $value;
				}
				$numCnt++;
			}
			$sql .= ' ORDER BY ' . $strOrderSql;
		}
		$sql .= ' LIMIT ' . $limit;

		if ($page > 1) {
			$sql .= ' OFFSET ' . ($limit * ($page - 1));
		}

		return $this->query($sql);
	}

// 	/**
// 	 * ページネート実行（カウント処理）
// 	 *
// 	 */
// 	public function paginateCount() {
// 		$extra = func_get_arg(2);

// 		return count($this->query(
// 				preg_replace(
// 						'/LIMIT \d+ OFFSET \d+$/u',
// 						'',
// 						$extra['extra']['type']
// 						)
// 				));
// 	}

	/**
	 * ページネート実行（カウント処理）
	 *
	 */
	public function paginateCount() {
		$extra = func_get_arg(2);




		$strSql = <<< EOM
  SELECT
    COUNT(*)
  FROM tbl_zks_obic_order as o
    INNER JOIN tbl_zks_obic_order_detail AS od ON
    od.order_slip_no = o.order_slip_no
    LEFT JOIN mst_shop_for_tool AS ms ON
    ms.dept_cd = o.warehouse_cd
    LEFT JOIN (
    		SELECT
    		m1.staff_cd,
    		m1.staff_name
    		FROM (
    				SELECT
    				staff_cd,
    				MAX(revision_date) AS revision_date
    				FROM mst_obic_staff
    				GROUP BY staff_cd
    				) AS m0
    		INNER JOIN mst_obic_staff AS m1 ON
    		m1.staff_cd = m0.staff_cd AND
    		m1.revision_date = m0.revision_date
    		) AS mst ON
    		mst.staff_cd = o.supplier_staff_cd
    		LEFT JOIN mst_obic_supplier AS msp ON
    		msp.supplier_cd = o.supplier_cd
    		LEFT JOIN mst_obic_payee AS mpy ON
    		mpy.payee_cd = o.payee_cd
    		LEFT JOIN mst_obic_payment AS mpm ON
    		mpm.payment_cd = o.payment_cd
    		LEFT JOIN mst_obic_hauler AS mh ON
    		mh.hauler_cd = o.hauler_cd
    		LEFT JOIN mst_obic_item AS mi ON
    		mi.item_cd = od.item_cd
	WHERE mi.item_cd LIKE 'D%'
	AND o.order_ymd >= '2016-10-06'
	AND od.cancel_flg = '0'


EOM;

		$extra['extra']['type'] = $strSql;



		$strArray=$this->query($extra['extra']['type']);


// 		$count = $this->query($extra['extra']['type'])[0][0]['count'];

		$count =$strArray[0][0]['count'];




		return $count;



	}








	public function hasField($name, $checkVirtual = false) {
		return true;
	}









}