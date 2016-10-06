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

	/**
	 * ページネート実行（カウント処理）
	 *
	 */
	public function paginateCount() {
		$extra = func_get_arg(2);
		return count($this->query(
				preg_replace(
						'/LIMIT \d+ OFFSET \d+$/u',
						'',
						$extra['extra']['type']
						)
				));
	}

	public function hasField($name, $checkVirtual = false) {
		return true;
	}

}