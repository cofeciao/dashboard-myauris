<?php

namespace backend\modules\chi\models;

use backend\modules\chi\models\query\DeXuatChiQuery;
use backend\modules\user\models\User;
use common\models\UserProfile;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "thuchi_de_xuat_chi".
 *
 * @property int $id
 * @property string $type_dexuat
 * @property int $nguoi_trien_khai
 * @property int $inspectioner  : Người nghiệm thu.
 * @property int $chosen_one  : Người được chỉ định duyệt.
 * @property int $so_tien_chi Loại đánh giá
 * @property int $thoi_han_thanh_toan Loại đánh giá
 * @property int $khoan_chi Khoản Chi
 * @property int $coso Cơ Sở
 * @property string receiver Người nhận
 * @property string $receiver_phone Số Điện Thoại Người Nhận
 * @property int method_payment Phương Thức Thanh Toán
 * @property string owner_credit_name Tên Chủ Thẻ
 * @property string credit_number Số Thẻ
 * @property string Banking_name Tên Ngân Hàng
 * @property int $status 0: Đang đợi duyệt,1: Trưởng phòng đã duyệt,2: Không được duyệt,3: Kế toán đã duyệt,4: Hoàn thành,5: Hoàn tiền,6: Huỷ đề xuất
 * @property int $tp_status 0: Chưa duyệt,1: Trưởng phòng đã duyệt người đề xuất toàn bộ tiêu chí, 2: Yêu cầu kế toán hủy đề xuất, hoàn tiền.
 * @property int $leader_accept
 * @property int $leader_accept_at
 * @property int $accountant_accept
 * @property int $accountant_accept_at
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class DeXuatChi extends DeXuatChiModel {

	public static function getDb() {
		return \Yii::$app->db;   // TODO: Research 2 db 2 server khac nhau. Tao. duoi' local
	}

	public $cosoquery = [];

	public function init() {
		parent::init(); // TODO: Change the autogenerated stub
		$this->cosoquery = \backend\modules\setting\models\Dep365CoSo::getCoSoArrayBy( 'slug', \backend\modules\setting\models\Dep365CoSo::HEAD_OFFICE );
	}


	public function behaviors() {
		return [
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
			[
				'class'      => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_VALIDATE => [ 'so_tien_chi' ]
				],
				'value'      => function () {
					if ( $this->so_tien_chi != null ) {
						return str_replace( '.', '', $this->so_tien_chi );
					}

					return null;
				}
			],
			[
				'class'      => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => [ 'thoi_han_thanh_toan' ],
					ActiveRecord::EVENT_BEFORE_UPDATE => [ 'thoi_han_thanh_toan' ]
				],
				'value'      => function () {
					return strtotime( $this->thoi_han_thanh_toan );
				}
			],
			/*[
				'class' => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['khoan_chi'],
					ActiveRecord::EVENT_BEFORE_UPDATE => ['khoan_chi'],
				],
				'value' => function ($value) {
					$khoanChi = KhoanChi::getKhoanChiByCode($this->khoan_chi);
					if ($khoanChi == null) {
						return null;
					}
					return $khoanChi->primaryKey;
				}
			]*/
		];
	}


	public static function find() {
		return new DeXuatChiQuery( get_called_class() );
	}


	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'nguoi_trien_khai', 'thoi_han_thanh_toan', 'title', 'type_dexuat', 'chosen_one' ], 'required' ],
			[ [ 'so_tien_chi' ], 'required' ],
			[
				[
					'chosen_one',
					'inspectioner',
					'status',
					'tp_status',
					'leader_accept',
					'leader_accept_at',
					'accountant_accept',
					'accountant_accept_at'
				],
				'integer'
			],
			[ 'coso', 'required' ],
			[ [ 'nguoi_trien_khai' ], 'integer' ],
			/* RULES KE TOAN */
//            [['danh_muc_chi', 'nhom_chi', 'khoan_chi'], 'required', 'on' => self::SCENARIO_KE_TOAN],
			[ [ 'danh_muc_chi', 'nhom_chi' ], 'integer', 'on' => self::SCENARIO_KE_TOAN ],
			[ [ 'coso', 'type_dexuat', 'khoan_chi' ], 'safe' ],
			[
				[ 'danh_muc_chi' ],
				'exist',
				'targetClass'     => DanhMucChi::class,
				'targetAttribute' => 'id',
				'on'              => self::SCENARIO_KE_TOAN
			],
			[
				[ 'nhom_chi' ],
				'exist',
				'targetClass'     => NhomChi::class,
				'targetAttribute' => 'id',
				'on'              => self::SCENARIO_KE_TOAN
			],
			[
				[ 'khoan_chi' ],
				'exist',
				'targetClass'     => KhoanChi::class,
				'targetAttribute' => 'id',
				'when'            => function ( $model ) {
					return ! empty( $model->khoan_chi );
				},
				'on'              => self::SCENARIO_KE_TOAN
			],
			/* END RULES KE TOAN */
			[ [ 'thoi_han_thanh_toan', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc' ], 'safe' ],
			[ [ 'so_tien_chi' ], 'safe' ],
			[
				[
					'receiver',
					'receiver_phone',
					'method_payment',
					'owner_credit_name',
					'credit_number',
					'banking_name'
				],
				'required',
				'on' => [ self::SCENARIO_CHUYENKHOAN_QUY, self::SCENARIO_CHUYENKHOAN ]
			],
			[
				[
					'receiver',
					'receiver_phone',
					'method_payment',
					'owner_credit_name',
					'credit_number',
					'banking_name'
				],
				'safe'
			],
			[ 'tieu_chi_group', 'safe' ],
			[ 'tieu_chi_group', 'validateTieuChiGroup' ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'                   => Yii::t( 'backend', 'ID' ),
			'title'                => Yii::t( 'backend', 'Tiêu Đề' ),
			'type_dexuat'          => Yii::t( 'backend', 'Loại Đề Xuất' ),
			'tp_status'            => Yii::t( 'backend', '' ),
			'chosen_one'           => Yii::t( 'backend', 'Người duyệt' ),
			'nguoi_trien_khai'     => Yii::t( 'backend', 'Người Triển Khai' ),
			'inspectioner'         => Yii::t( 'backend', 'Người Nghiệm Thu' ),
			'so_tien_chi'          => Yii::t( 'backend', 'Số Tiền Chi' ),
			'thoi_han_thanh_toan'  => Yii::t( 'backend', 'Thời Hạn Thanh Toán' ),
			'coso'                 => Yii::t( 'backend', 'Cơ Sở' ),
			'khoan_chi'            => Yii::t( 'backend', 'Khoản Chi' ),
			'status'               => Yii::t( 'backend', 'Status' ),
			'thoi_gian_bat_dau'    => Yii::t( 'backend', 'Thời gian bắt đầu' ),
			'thoi_gian_ket_thuc'   => Yii::t( 'backend', 'Thời gian kết thúc' ),
			'leader_accept'        => Yii::t( 'backend', 'Trưởng phòng duyệt' ),
			'leader_accept_at'     => Yii::t( 'backend', 'Leader Accept At' ),
			'accountant_accept'    => Yii::t( 'backend', 'Kế toán duyệt' ),
			'accountant_accept_at' => Yii::t( 'backend', 'Accountant Accept At' ),
			'created_at'           => Yii::t( 'backend', 'Created At' ),
			'created_by'           => Yii::t( 'backend', 'Người Đề Xuất' ),
			'updated_by'           => Yii::t( 'backend', 'Updated By' ),
			'updated_at'           => Yii::t( 'backend', 'Updated At' ),
			'receiver'             => Yii::t( 'backend', 'Người nhận' ),
			'receiver_phone'       => Yii::t( 'backend', 'Số Điện Thoại Người Nhận' ),
			'method_payment'       => Yii::t( 'backend', 'Phương Thức Thanh Toán' ),
			'owner_credit_name'    => Yii::t( 'backend', 'Tên Chủ Thẻ' ),
			'credit_number'        => Yii::t( 'backend', 'Số Thẻ' ),
			'Banking_name'         => Yii::t( 'backend', 'Tên Ngân Hàng' ),
		];
	}

	public function findModel( $id ) {
		if ( ( $model = DeXuatChi::findOne( $id ) ) !== null ) {
			$activeRecords         = ThuchiTieuChi::find()->where( [ 'id_de_xuat_chi' => $id ] )->orderBy( [ 'id' => SORT_DESC ] )->all();
			$data                  = ArrayHelper::toArray( $activeRecords, [
				ThuchiTieuChi::class => [
					'id',
					'id_de_xuat_chi',
					'tieu_chi',
					'nd_hoan_thanh',
					'thoi_gian_bat_dau',
					'thoi_gian_ket_thuc',
					'status',
				]
			] );
			$model->tieu_chi_group = $data;

			return $model;
		}

		throw new NotFoundHttpException( Yii::t( 'backend', 'The requested page does not exist.' ) );
	}

	public function validateTieuChiGroup() {
		if ( ! $this->hasErrors() ) {
			$has_error = false;
			$errors    = [];
			foreach ( $this->tieu_chi_group as $k => $tieu_chi_group ) {
				$tieu_chi = new ThuchiTieuChi();/*
                if ($tieu_chi_group['id'] != null) {
                    $tieu_chi = ThuchiTieuChiModel::getById($tieu_chi_group['id']);
                    $tieu_chi->scenario = ThuchiTieuChi::SCENARIO_SAVE;
                }*/
				$tieu_chi->setAttributes( $tieu_chi_group );
				if ( ! $tieu_chi->validate() ) {
					$has_error = true;
					foreach ( $tieu_chi->getErrors() as $key => $error ) {
//                        $errors[strtolower($this->formName()) . '-tieu_chi_group-' . $k . '-' . $key] = $error;
						$errors[ $tieu_chi->getAttributeLabel( $key ) ] = " của tiêu chí phải nhập";
					}
				}
			}
			if ( $has_error == true ) {
				$this->addError( 'tieu_chi_group', $errors );
			}
		}
	}


}
