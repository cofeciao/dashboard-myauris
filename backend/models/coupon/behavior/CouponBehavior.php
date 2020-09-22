<?php
/**
 * Date: 8/18/20
 * Time: 12:36 AM
 */

namespace backend\models\coupon\behavior;


use GuzzleHttp\Client;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class CouponBehavior extends Behavior {

	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert'
		];
	}

	public $_order_item_id_api;


	public function setOrder_item_id_api( $value ) {
		$this->_order_item_id_api = $value;
	}


	public function afterInsert( $event ) {

		$client  = new Client( [
			// Base URI is used with relative request
		] );
		$url     = API_MYAURIS . '/coupon-api';
		$respone = $client->request( 'PUT', $url . '/put',
			[ 'query' => [ 'id' => $event->sender->orderitem_id_api ], 'form_params' => [ 'status' => 0 ] ]
		);

	}


}