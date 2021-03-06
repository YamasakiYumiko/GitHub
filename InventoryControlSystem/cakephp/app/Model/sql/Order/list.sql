$query = <<<EOD
	SELECT
		(ROW_NUMBER() OVER(ORDER BY $order_by)) AS row_no,
		o.order_slip_no AS slip_no,
		o.order_kbn AS order_kubun_cd,
		o.office_cd AS office_cd,
		o.warehouse_cd AS warehouse_cd,
		ms.shop_short_name AS warehouse_name,
		TO_CHAR(o.order_ymd, 'YYYY-MM-DD') AS order_ymd,
		o.arrival_ex_ymd AS stock_ymd,
		o.purchase_ex_ymd AS purchase_ymd,
		o.supplier_staff_cd AS staff_cd,
		mst.staff_name AS staff_name,
		o.supplier_cd AS supplier_cd,
		msp.supplier_name AS supplier_name,
		o.payee_cd AS payee_cd,
		mpy.payee_name AS payee_name,
		o.payment_kbn AS payment_kubun_cd,
		o.payment_date AS payment_date,
		o.payment_cd AS payment_cd,
		mpm.payment_name AS payment_name,
		o.supplier_order_no AS supplier_order_no,
		o.demand_no AS demand_no,
		o.hauler_cd AS hauler_cd,
		mh.hauler_name AS hauler_name,
		o.slip_remarks AS slip_summary,
		o.order_remarks AS purchase_summary,
		o.slip_not_use_flg AS slip_unnecessary_flg,
		o.order_print_flg AS slip_target_flg,
		o.ex_order_flg AS slip_immediately_flg,
		o.print_timestamp AS slip_timestamp,
		o.arrived_kbn AS stocked_kubun_cd,
		o.purchased_kbn AS purchased_kubun_cd,
		o.approval_status_kbn AS approval_status_kbn,
		o.created_cd AS created_cd,
		o.created_timestamp AS created_timestamp,
		o.modified_cd AS modified_cd,
		o.modified_timestamp AS modified_timestamp,
		od.order_slip_no AS d_slip_no,
		od.order_detail_no AS d_detail_line_no,
		od.order_kbn AS d_detail_order_kubun_cd,
		od.debt_kbn AS d_debt_kubun_cd,
		od.warehouse_cd AS d_detail_warehouse_cd,
		od.item_cd AS d_item_cd,
		mi.item_name AS d_item_name,
		mi.jan_cd AS d_jan_cd,
		od.model_name AS d_model_name,
		od.tax_rate_kbn AS d_tax_rate_kubun_cd,
		CAST(od.unit_qty AS INTEGER) AS d_unit_quantity,
		CAST(od.single_qty AS INTEGER) AS d_single_quantity,
		TO_CHAR(CAST(od.order_qty AS INTEGER), 'FM9,999,999,990') AS d_quantity,
		od.temporary_price_kbn AS d_temporary_price_kubun_cd,
		REPLACE(TO_CHAR(od.purchase_cost, 'FM9,999,999,990.00'), '.00', '&nbsp;&nbsp;&nbsp;') AS d_supplier_price,
		TO_CHAR(CAST(od.purchase_amount AS INTEGER), 'FM9,999,999,990') AS d_supplier_amount,
		CAST(od.purchase_consumption_tax AS INTEGER) AS d_supplier_consumption_tax,
		od.payee_order_no AS d_payee_order_no,
		od.arrival_ex_ymd AS d_stock_ymd,
		TO_CHAR(od.purchase_ex_ymd, 'YYYY-MM-DD') AS d_purchase_ymd,
		od.remarks AS d_detail_summary,
		od.arrived_kbn AS d_stocked_kubun_cd,
		CASE od.purchased_kbn WHEN '0' THEN '未' WHEN '1' THEN '済' ELSE '' END AS d_purchased_kubun_cd,
		CAST(od.arrival_ex_qty AS INTEGER) AS d_stock_quantity_ex,
		CAST(od.arrival_qty AS INTEGER) AS d_stock_quantity,
		TO_CHAR(CAST(od.purchase_qty AS INTEGER), 'FM9,999,999,990') || '<br>' || TO_CHAR(CAST(od.order_qty - od.purchase_qty AS INTEGER), 'FM9,999,999,990') AS d_purchase_quantity,	--CAST(od.purchase_qty AS INTEGER) AS d_purchase_quantity,
		od.cancel_flg AS d_cancel_flg,
		od.created_cd AS d_created_cd,
		od.created_timestamp AS d_created_timestamp,
		od.modified_cd AS d_modified_cd,
		od.modified_timestamp AS d_modified_timestamp
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
	WHERE
		mi.item_cd LIKE 'D%'
		AND o.order_ymd >= '$min_day'
		AND od.cancel_flg = '0'
EOD;
$results = $this-<query($query);