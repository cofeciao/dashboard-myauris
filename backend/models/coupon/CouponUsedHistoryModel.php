<?php

namespace backend\models\coupon;

use backend\models\coupon\behavior\CouponBehavior;
use GuzzleHttp\Client;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "coupon_used_history".
 *
 * @property string $coupon_code
 * @property int $orderitem_id_api
 * @property int $customer_id_api
 * @property int $order_id_api
 * @property int $order_id
 * @property int $giaban
 * @property int $giamua
 * @property string $coupon_name
 * @property string $customer_name
 * @property string $phone
 * @property string $email
 * @property int $created_at
 * @property int $created_by
 */
class CouponUsedHistoryModel extends \yii\db\ActiveRecord {
	public static function tableName() {
		return 'coupon_used_history';
	}

	public function behaviors() {
		return [
			[
				'class'             => CouponBehavior::class,
				'order_item_id_api' => $this->orderitem_id_api
			],
			[
				'class'      => TimestampBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at' ],
				],
			],
			[
				'class'              => BlameableBehavior::class,
				'createdByAttribute' => 'created_by',
				'updatedByAttribute' => 'created_by',
			]
		];
	}

	const COUPON_IS_PAID = [ 2, 3, 4 ];

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[
				[
//					'coupon_code',
//					'orderitem_id_api',
//					'customer_id_api',
//					'order_id_api',
//					'order_id',
//					'giaban',
//					'giamua'
				],
				'required'
			],
			[
				[
					'orderitem_id_api',
					'customer_id_api',
					'order_id_api',
					'order_id',
					'giaban',
					'giamua',
					'created_at',
					'created_by'
				],
				'integer'
			],
			[ [ 'coupon_code', 'coupon_name', 'customer_name', 'phone', 'email' ], 'string', 'max' => 255 ],
			[ 'coupon_code', 'checkIsValidToUse' ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'coupon_code'      => 'Coupon Code',
			'orderitem_id_api' => 'Orderitem Id Api',
			'customer_id_api'  => 'Customer Id Api',
			'order_id_api'     => 'Order Id Api',
			'order_id'         => 'Order Id',
			'giaban'           => 'Giá trị sử dụng',
			'giamua'           => 'Giamua',
			'coupon_name'      => 'Tên coupon',
			'customer_name'    => 'phone',
			'email'            => 'Email',
			'phone'            => 'Phone',
			'created_at'       => 'Created At',
			'created_by'       => 'Created By',
		];
	}

	public static function getCodeCoupon( $coupon_code ) {
		$res = [];

		if ( ! empty( $coupon_code ) ) {

			$client      = new Client( [
				// Base URI is used with relative request
			] );
			$coupon_code = substr( $coupon_code, 4, strlen( $coupon_code ) );
			$url         = API_MYAURIS . '/coupon-api';
			$respone     = $client->request( 'GET', $url,
				[ 'query' => [ 'id' => $coupon_code ] ]
			);
			$res['code'] = $respone->getStatusCode();
			$json_decode = json_decode( $respone->getBody()->getContents() );
			if ( isset( $json_decode[0] ) ) {
				$res['data'] = $json_decode[0];
			} else {
				$res['data'] = [];
			}
		}

		return $res;
	}

	public function checkIsValidToUse() {
		if ( isset( $this->coupon_code ) && ! empty( $this->coupon_code ) ) {
			$coupon_data = self::getCodeCoupon( $this->coupon_code );
			if ( isset( $coupon_data['data'] ) && ! empty( $coupon_data['data'] ) ) {
				if ( isset( $coupon_data['data']->couponHasOne ) && ! empty( $coupon_data['data']->couponHasOne ) && $coupon_data['data']->status == 1 ) {

				} else {
					self::addError( 'coupon_code', 'Coupon đã sử dụng' );
				}
				if ( isset( $coupon_data['data']->orderHasOne ) && ! empty( $coupon_data['data']->orderHasOne ) && in_array( $coupon_data['data']->orderHasOne->status, self::COUPON_IS_PAID ) ) {

				} else {
					self::addError( 'coupon_code', 'Coupon chưa thanh toán' );
				}
			} else {
				self::addError( 'coupon_code', 'Không tìm thấy coupon.' );
			}
		}
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			return null;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public function loadAttributesApi() {
		if ( isset( $this->coupon_code ) && ! empty( $this->coupon_code ) ) {
			$coupon_data = self::getCodeCoupon( $this->coupon_code );
			if ( ! empty( $coupon_data ) ) {
				$this->giaban           = $coupon_data['data']->couponHasOne->giaban;
				$this->giamua           = $coupon_data['data']->couponHasOne->giamua;
				$this->order_id_api     = $coupon_data['data']->order_id;
				$this->customer_id_api  = $coupon_data['data']->customerHasOne->id;
				$this->orderitem_id_api = $coupon_data['data']->id;
				$this->coupon_name      = $coupon_data['data']->couponHasOne->name;
				$this->customer_name    = $coupon_data['data']->customerHasOne->name;
				$this->email            = $coupon_data['data']->customerHasOne->email;
				$this->phone            = $coupon_data['data']->customerHasOne->phone;
			}

			return true;
		} else {
			return false;
		}
	}

}
