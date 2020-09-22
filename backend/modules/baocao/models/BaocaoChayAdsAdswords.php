<?php

namespace backend\modules\baocao\models;

use backend\modules\baocao\behaviors\BaocaoChayAdsBehaviors;
use kartik\form\ActiveForm;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "baocao_chay_adwords".
 *
 * @property int $id
 * @property string $amount_money
 * @property string $product
 * @property int $status
 * @property int $ngay_tao
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class BaocaoChayAdsAdswords extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */

	const PRODUCT_LIST = [ 'nieng' => 'Niềng', 'su' => 'Sứ', 'implant' => 'Implant' ];


	public static function tableName() {
		return 'baocao_chay_adwords';
	}

	public function behaviors() {
		return [
			[
				// auto calculate ctr cpv;
				'class' => BaocaoChayAdsBehaviors::class,
			],
			[
				'class'              => BlameableBehavior::class,
				'createdByAttribute' => 'created_by',
				'updatedByAttribute' => 'updated_by',
			],
			'timestamp' => [
				'class'                  => 'yii\behaviors\TimestampBehavior',
				'preserveNonEmptyValues' => true,
				'attributes'             => [
					ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at', 'updated_at' ],
					ActiveRecord::EVENT_BEFORE_UPDATE => [ 'updated_at' ],
				],
			],
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			/* [['post_type'], 'required'],*/
			[ [ 'amount_money' ], 'number' ],
			[
				[
					'status',
					'created_by',
					'updated_by',
					'created_at',
					'updated_at'
				],
				'integer',
			],
			[ 'product', 'safe' ],
			[
				[
					'amount_money',
					'status',
				],
				'default',
				'value' => 0,
			],

			[ [ 'ngay_tao' ], 'validate_ngaytao' ],
		];
	}


	public function validate_ngaytao( $attribute, $params, $validator ) {
		if ( $this->isNewRecord ) {
			$query = $this::find()->where( [
				'ngay_tao'  => $this->attributes['ngay_tao'],
			] )->limit( 1 )->one();
			if ( ! empty( $query ) ) {
				$this->addError(
					$attribute,
					'Ngày tạo của kiểu chạy này đã có. Vui lòng chọn ngày khác hoặc xóa/sửa bản lưu hiện có.'
				);
			}
		}
	}


	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id' => 'ID',

			'amount_money' => 'Số tiền',
			'product'      => 'Sản Phẩm',
			'ngay_tao'     => 'Ngày tạo',
			'created_by'   => 'Created By',
			'updated_by'   => 'Updated By',
			'created_at'   => 'Created At',
			'updated_at'   => 'Updated At',
		];
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			return null;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public function getUserUpdatedBy( $id ) {
		if ( $id == null ) {
			return null;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}
}
