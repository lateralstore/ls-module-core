<?

class Core_Metrics {

	/*
	 * @deprecated
	 * This was used to report application metrics to a central server
	 */
	public static function update_metrics() {
		return;
	}


	public static function log_pageview() {
		try {
			if ( Phpr::$config->get( 'DISABLE_USAGE_STATISTICS' ) ) {
				return;
			}

			Db_DbHelper::query( 'update core_metrics set page_views = page_views+1' );
		} catch ( exception $ex ) {
		}
	}

	public static function log_order( $order ) {
		try {
			if ( Phpr::$config->get( 'DISABLE_USAGE_STATISTICS' ) ) {
				return;
			}

			Db_DbHelper::query(
				'update core_metrics set total_order_num = total_order_num+1, total_amount = total_amount + :order_total',
				array( 'order_total' => $order->total )
			);
		} catch ( exception $ex ) {
		}
	}

	protected static function extract_shipping_module_usage( $last_update ) {
		$shipping_module_records = Db_DbHelper::objectArray( 'select 
					shop_shipping_options.class_name as class_name,
					count(shop_orders.id) as order_num,
					shop_shipping_options.class_name as module_name
				from 
					shop_shipping_options, 
					shop_orders 
				where 
					shop_orders.shipping_method_id = shop_shipping_options.id
					and shop_orders.order_datetime >= :update_date
				group by shop_shipping_options.class_name', array(
			'update_date' => $last_update
		) );

		foreach ( $shipping_module_records as &$record ) {
			try {
				if ( class_exists( $record->class_name ) ) {
					$method = new $record->class_name();
					$info   = $method->get_info();
					if ( isset( $info['name'] ) ) {
						$record->module_name = $info['name'];
					}
				}
			} catch ( exception $ex ) {
			}
		}

		return $shipping_module_records;
	}

	protected static function extract_payment_module_usage( $last_update ) {
		$payment_module_records = Db_DbHelper::objectArray( 'select 
					shop_payment_methods.class_name as class_name,
					count(shop_orders.id) as order_num,
					shop_payment_methods.class_name as module_name,
					sum(shop_orders.total) as totals
				from 
					shop_payment_methods, 
					shop_orders 
				where 
					shop_orders.payment_method_id = shop_payment_methods.id
					and shop_orders.order_datetime >= :update_date
				group by shop_payment_methods.class_name', array(
			'update_date' => $last_update
		) );

		foreach ( $payment_module_records as &$record ) {
			try {
				if ( class_exists( $record->class_name ) ) {
					$method = new $record->class_name();
					$info   = $method->get_info();
					if ( isset( $info['name'] ) ) {
						$record->module_name = $info['name'];
					}
				}
			} catch ( exception $ex ) {
			}
		}

		return $payment_module_records;
	}
}