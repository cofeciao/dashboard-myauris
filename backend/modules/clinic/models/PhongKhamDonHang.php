<?php

namespace backend\modules\clinic\models;

use backend\models\coupon\CouponUsedHistoryModel;
use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\User;
use common\models\UserProfile;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "phong_kham_don_hang".
 *
 * @property int $id
 * @property int $customer_id
 * @property int $chiet_khau
 * @property string $thanh_toan
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class PhongKhamDonHang extends DonHangModel {
	const SCENARIO_PAYMENT = 'payment';
	const HOAN_THANH_KHAM = 1;
	const CHUA_HOAN_THANH_KHAM = 0;

	const HOAN_THANH_THANH_TOAN = 1;
	const CHUA_HOAN_THANH_THANH_TOAN = 0;

	const CONFIRM_DONE = 1;
	const CONFIRM_NEW = 0;

	const HOAN_THANH_DICH_VU = 1;
	const CHUA_HOAN_THANH_DICH_VU = 0;

	public $customer;
	public $id_oder;
	public $tien_thanh_toan;
	public $customer_order;
	public $thanh_toan;
	public $dich_vu;
	public $s_p;
	public $t_T_t;
	public $name;
	public $clinic_code;
	public $phone_number;
	public $con_no;
	public $dat_coc;
	public $hoan_coc;
	public $total;
	public $tien_thu;
	public $chiet_khau_order;

	/*
	 * Thuộc tính trong quản lý đơn hàng
	 */
	public $chiet_khau_theo_order;
	public $ly_do_chiet_khau;

	/*
	 * TV Online
	 */
	public $dh_thanh_tien;
	public $dh_thanh_toan;
	public $tu_van_vien;

	public static function sumTienThanhToan( $from, $to ) {
		$query = ThanhToanModel::find()->where( [ '<>', 'tam_ung', ThanhToanModel::HOAN_COC ] )->andWhere( [
			'between',
			'ngay_tao',
			$from,
			$to
		] );

		return $query->sum( 'tien_thanh_toan' );
	}

	public static function sumTienThanhToanByCoSo( $from, $to, $co_so ) {
		$query = ThanhToanModel::find()->where( [ 'co_so' => $co_so ] )->andWhere( [
			'<>',
			'tam_ung',
			ThanhToanModel::HOAN_COC
		] )->andWhere( [ 'between', 'ngay_tao', $from, $to ] );

		return $query->sum( 'tien_thanh_toan' );
	}

	public function behaviors() {
		return [
			[
				'class'              => BlameableBehavior::class,
				'createdByAttribute' => 'created_by',
				'updatedByAttribute' => 'updated_by',
			],
			'timestamp' => [
				'class'      => 'yii\behaviors\TimestampBehavior',
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at', 'updated_at' ],
					ActiveRecord::EVENT_BEFORE_UPDATE => [ 'updated_at' ],
				],
				'value'      => time(),
			],
			[
				'class'      => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'co_so',
				],
				'value'      => function () {
					return Yii::$app->user->identity->permission_coso;
				},
			],
			[
				'class'      => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_tao',
				],
				'value'      => function () {
					return strtotime( date( 'd-m-Y' ) );
				},
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'customer_id'/*, 'direct_sale_id'*/ ], 'required' ],
			[
				[
					'thanh_tien',
					'total',
					'customer_id',
					'khuyen_mai',
					'id_oder',
					'trang_thai_hoan_thanh',
					'confirm',
					'confirm_at',
					'confirm_by'
				],
				'integer'
			],
			[ [ 'customer_order', 'name', 'clinic_code' ], 'string' ],
			[ [ 'chiet_khau' ], 'match', 'pattern' => '/^[0-9.]+$/' ],
			[ [ 'thanh_toan', 'dh_thanh_tien' ], 'string' ],
			[ [ 'thanh_tien' ], 'validateMoney' ],
			[ [ 'dich_vu', 's_p' ], 'checkOrderDv' ],
			[ [ 'chiet_khau_order' ], 'string', 'max' => 255 ],
			[ [ 'chiet_khau_theo_order' ], 'string', 'max' => 1 ],
			[ [ 'trang_thai_hoan_thanh', 'confirm' ], 'safe' ],
			[ 'confirm', 'default', 'value' => self::CONFIRM_NEW ], // xac nhan doanh thu
		];
	}

	public function scenarios() {
		$scenarios = [
			'checkOrder' => [ 'dich_vu', 's_p', 't_T_t' ],
		];

		return array_merge( parent::scenarios(), $scenarios );
	}

	public function checkOrderDv() {
		if ( $this->dich_vu == 0 ) {
			return $this->addErrors( [ 'dich_vu' => 'Bạn chưa chọn dịch vụ.' ] );
		}
		if ( $this->s_p == 0 ) {
			return $this->addErrors( [ 'dich_vu' => 'Bạn chưa chọn sản phẩm.' ] );
		}
	}

	//    public function getDhThanhTien($order)
	//    {
	//        $order = json_decode($order);
	////        var_dump($order);die;
	//        $thanhTien = 0;
	//        foreach ($order as $key => $item) {
	//            $money = str_replace('.', '', $item->thanh_tien);
	//            $thanhTien += $money;
	//        }
	//        return $thanhTien;
	//    }

	public function validateMoney() {
		if ( $this->thanh_tien < 0 ) {
			return $this->addErrors( [ 'thanh_tien' => 'Hãy thối lại tiền dư cho khách hàng.' ] );
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'                    => Yii::t( 'backend', 'ID' ),
			'customer_id'           => Yii::t( 'backend', 'Id khách hàng' ),
			'chiet_khau'            => Yii::t( 'backend', 'Chiết khấu' ),
			'thanh_tien'            => 'Thành tiền',
			'tien_thanh_toan'       => 'Số tiền thanh toán',
			'created_at'            => Yii::t( 'backend', 'Created At' ),
			'updated_at'            => Yii::t( 'backend', 'Updated At' ),
			'created_by'            => Yii::t( 'backend', 'Created By' ),
			'updated_by'            => Yii::t( 'backend', 'Updated By' ),
			'direct_sale_id'        => 'Direct sale',
			'name'                  => 'Khách hàng',
			'order_code'            => 'Mã hóa đơn',
			'clinic_code'           => 'Mã khách hàng',
			'co_so'                 => 'Cơ sở',
			'phone_number'          => 'Số điện thoại',
			'dh_thanh_tien'         => 'Thành tiền',
			'dh_thanh_toan'         => 'Đã thanh toán',
			'con_no'                => 'Nợ',
			'dat_coc'               => 'Đặt cọc',
			'total'                 => 'TOTAL',
			'thanh_toan'            => 'Thanh toán',
			'customer_order'        => 'Chi tiết đơn hàng',
			'tien_thu'              => 'Thực thu',
			'trang_thai_hoan_thanh' => 'Hoàn thành thanh toán',
			'confirm'               => 'Xác nhận doanh thu chỉnh nha',
			'trang_thai_dich_vu'    => 'Trạng thái dịch vụ',
		];
	}

	public function getDhThanhToan( $thanhToan ) {
		$thanhToan = json_decode( $thanhToan );
		$thanhTien = 0;
		foreach ( $thanhToan as $key => $item ) {
			$money     = str_replace( '.', '', $item->tien_thanh_toan );
			$thanhTien += $money;
		}

		return $thanhTien;
	}

	public function getCoSoHasOne() {
		return $this->hasOne( Dep365CoSo::class, [ 'id' => 'co_so' ] );
	}

	public function getClinicHasOne() {
		return $this->hasOne( Clinic::class, [ 'id' => 'customer_id' ] );
	}

	public function getUserCreatedByHasOne() {
		return $this->hasOne( UserProfile::class, [ 'user_id' => 'created_by' ] );
	}

	public function getCustomerOnlineHasOne() {
		return $this->hasOne( CustomerModel::class, [ 'id' => 'customer_id' ] );
	}

	public function getKhuyenMaiHasOne() {
		return $this->hasOne( PhongKhamKhuyenMai::class, [ 'id' => 'khuyen_mai' ] );
	}

	public function getPhongKhamDonHangWOrderHasMany() {
		return $this->hasMany( PhongKhamDonHangWOrder::class, [ 'phong_kham_don_hang_id' => 'id' ] );
	}

	// lay thong tin chi tiet chiet khau

	public function getPhongKhamDonHangWThanhToanHasMany() {
		return $this->hasMany( PhongKhamDonHangWThanhToan::class, [ 'phong_kham_don_hang_id' => 'id' ] );
	}

	public function getPhongKhamLichDieuTriHasMany() {
		return $this->hasMany( PhongKhamLichDieuTri::class, [ 'order_code' => 'order_code' ] );
	}

	// nghia

	public function getLoaiThanhToanHasOne() {
		return $this->hasOne( PhongKhamLoaiThanhToan::class, [ 'id' => 'loai_thanh_toan' ] );
	}

	public function getUserConfirmHasOne() {
		return $this->hasOne( UserProfile::class, [ 'user_id' => 'confirm_by' ] );
	}


	/*coupon */

	public function getCouponHistoryHasOne() {
		return $this->hasOne( CouponUsedHistoryModel::class, [ 'order_id' => 'id' ] );
	}

	/*end coupon */


	public function getConfirmTime() {
		return date( 'd-m-Y h:i', $this->confirm_at );
	}

	public function viewInfoConfirm() {
		$result = "";
		if ( $this->confirm === self::CONFIRM_DONE ) {
			$result = '<span class="badge badge-success badge-pill ">Đã tính doanh số chỉnh nha</span>';
		}

		return $result;
	}

	// tam nguyen

	public function getChiTietChietKhau( $is_excel = false ) {
		$list = $this->phongKhamDonHangWOrderHasMany;

		$result = "";
		if ( ! $is_excel ) {
			foreach ( $list as $item ) {
				if ( $item->chiet_khau_order != 0 ) {
					if ( $item->chiet_khau_theo_order == PhongKhamDonHangWOrder::CHIET_KHAU_TIEN ) {
						$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
						$result      .= $nameSanPham . " : <span style='font-weight: 600'>" . $item->so_luong . "</span> TT : " . number_format( $item->thanh_tien, 0, '', '.' ) . "  - CK: " . $item->ly_do_chiet_khau . " : <span style='font-weight: 600'>" . number_format( $item->chiet_khau_order, 0, '', '.' ) . "</span><br>";
					} else {
						$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
						$result      .= $nameSanPham . " : <span style='font-weight: 600'>" . $item->so_luong . "</span> TT : " . number_format( $item->thanh_tien, 0, '', '.' ) . "  - CK: " . $item->ly_do_chiet_khau . " : <span style='font-weight: 600'>" . number_format( $item->chiet_khau_order, 0, '', '.' ) . " %</span><br>";
					}
				}
			}

			if ( ! empty( $this->khuyen_mai ) ) {
				$khuyenmai = PhongKhamKhuyenMai::findOne( $this->khuyen_mai );
				$result    .= ( $khuyenmai ) ? $khuyenmai->name . " : <span style='font-weight: 600'>" . number_format( $khuyenmai->price, 0, '', '.' ) . " </span><br>" : "";
			}
		} else {
			foreach ( $list as $item ) {
				if ( $item->chiet_khau_order != 0 ) {
					if ( $item->chiet_khau_theo_order == PhongKhamDonHangWOrder::CHIET_KHAU_TIEN ) {
						$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
						$result      .= $nameSanPham . " : " . $item->so_luong . " TT : " . number_format( $item->thanh_tien, 0, '', '.' ) . "  - CK: " . $item->ly_do_chiet_khau . " : " . number_format( $item->chiet_khau_order, 0, '', '.' ) . "\n";
					} else {
						$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
						$result      .= $nameSanPham . " : " . $item->so_luong . " TT : " . number_format( $item->thanh_tien, 0, '', '.' ) . "  - CK: " . $item->ly_do_chiet_khau . " : " . number_format( $item->chiet_khau_order, 0, '', '.' ) . " % \n";
					}
				}
			}

			if ( ! empty( $this->khuyen_mai ) ) {
				$khuyenmai = PhongKhamKhuyenMai::findOne( $this->khuyen_mai );
				if ( $khuyenmai ) {
					$result .= $khuyenmai->name . " : " . number_format( $khuyenmai->price, 0, '', '.' ) . "\n";
				}
			}
		}

		return $result;
	}

	public function getChiTietChietKhauN() {
		$list        = $this->phongKhamDonHangWOrderHasMany;
		$nameSanPham = '';
		$result      = '';
		foreach ( $list as $item ) {
			if ( $item->chiet_khau_order != 0 ) {
				if ( $item->chiet_khau_theo_order == PhongKhamDonHangWOrder::CHIET_KHAU_TIEN ) {
					if ( $item->ly_do_chiet_khau == null || $item->ly_do_chiet_khau == '' ) {
						$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
					}
					$result .= $nameSanPham . $item->ly_do_chiet_khau . " : <span style='font-weight: 600'>" . number_format( $item->chiet_khau_order, 0, '', '.' ) . "</span><br>";
				} else {
					$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
					$result      .= $nameSanPham . ' ' . $item->ly_do_chiet_khau . " : <span style='font-weight: 600'>" . number_format( $item->chiet_khau_order, 0, '', '.' ) . "% (" . number_format( $item->thanh_tien * $item->chiet_khau_order / 100, 0, '', '.' ) . ")</span><br>";
				}
			}
		}
		if ( ! empty( $this->khuyen_mai ) ) {
			$khuyenmai = PhongKhamKhuyenMai::findOne( $this->khuyen_mai );
			$result    .= ( $khuyenmai ) ? $khuyenmai->name . " : <span style='font-weight: 600'>" . number_format( $khuyenmai->price, 0, '', '.' ) . " </span><br>" : "";
		}

		return trim( $result );
	}

	public function getThongTinGoiDichVu( $is_excel = false ) {
		$list   = $this->phongKhamDonHangWOrderHasMany;
		$result = "";
		if ( ! $is_excel ) {
			foreach ( $list as $item ) {
				$nameDichVu  = ( $item->dichVuHasOne ) ? $item->dichVuHasOne->name : "";
				$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
				$result      .= $nameDichVu . " | " . $nameSanPham . " : <span style='font-weight: 600; font-size: 15px;'>" . $item->so_luong . "</span> <br>";
			}
		} else {
			foreach ( $list as $item ) {
				$nameDichVu  = ( $item->dichVuHasOne ) ? $item->dichVuHasOne->name : "";
				$nameSanPham = ( $item->sanPhamHasOne ) ? $item->sanPhamHasOne->name : "";
				$result      .= $nameDichVu . " - " . $nameSanPham . " : " . $item->so_luong . "\n";
			}
		}

		return $result;
	}

	// 1 la hoan thanh

	public function getChiTietThanhToan( $is_excel = false ) {
		$list            = $this->phongKhamDonHangWThanhToanHasMany;
		$result          = "";
		$aTHANHTOAN_TYPE = ThanhToanModel::THANHTOAN_TYPE;
		$lan_thanh_toan  = 0;
		if ( ! $is_excel ) {
			foreach ( $list as $item ) {
				if ( ThanhToanModel::THANH_TOAN == $item->tam_ung ) {
					$lan_thanh_toan ++;
					$result .= Yii::$app->formatter->format( $item->ngay_tao, 'date' ) . " - " . $aTHANHTOAN_TYPE[ $item->tam_ung ] . " lần " . $lan_thanh_toan . " - " . $item->loaiThanhToanHasOne->name . " - <span style='font-weight: 600'>" . number_format( $item->tien_thanh_toan, 0, '', '.' ) . "</span><br>";
				} else {
					$result .= Yii::$app->formatter->format( $item->ngay_tao, 'date' ) . " - " . $aTHANHTOAN_TYPE[ $item->tam_ung ] . " - " . $item->loaiThanhToanHasOne->name . " - <span style='font-weight: 600'>" . number_format( $item->tien_thanh_toan, 0, '', '.' ) . "</span><br>";
				}
			}
		} else {
			foreach ( $list as $item ) {
				if ( ThanhToanModel::THANH_TOAN == $item->tam_ung ) {
					$lan_thanh_toan ++;
					$result .= Yii::$app->formatter->format( $item->ngay_tao, 'date' ) . " - " . $aTHANHTOAN_TYPE[ $item->tam_ung ] . " lần " . $lan_thanh_toan . " - " . $item->loaiThanhToanHasOne->name . " - " . number_format( $item->tien_thanh_toan, 0, '', '.' ) . "\n";
				} else {
					$result .= Yii::$app->formatter->format( $item->ngay_tao, 'date' ) . " - " . $aTHANHTOAN_TYPE[ $item->tam_ung ] . " - " . $item->loaiThanhToanHasOne->name . " - " . number_format( $item->tien_thanh_toan, 0, '', '.' ) . "\n";
				}
			}
		}

		return $result;
	}

	public function getThongTinBacSiLichDieuTri( $is_excel = false, $thao_tac ) {
		$list   = $this->phongKhamLichDieuTriHasMany;
		$result = "";
		$user   = new \common\models\UserProfile();
		$aExit  = [];
		if ( ! $is_excel ) {
			foreach ( $list as $item ) {
				if ( ! empty( $item->thao_tac ) && in_array( $thao_tac, $item->thao_tac ) && ! isset( $aExit[ $item->ekip ] ) ) {
					$aExit[ $item->ekip ] = $item->ekip;
					$fullname             = $user->getFullNameBacSi( $item->ekip );
					if ( $fullname == false ) {
						$result .= null;
					}
					$result .= $fullname . "<br>";
				}
			}
		} else {
			foreach ( $list as $item ) {
				if ( ! empty( $item->thao_tac ) && in_array( $thao_tac, $item->thao_tac ) && ! isset( $aExit[ $item->ekip ] ) ) {
					$aExit[ $item->ekip ] = $item->ekip;
					$fullname             = $user->getFullNameBacSi( $item->ekip );
					if ( $fullname == false ) {
						$result .= null;
					}
					$result .= $fullname . "\n";
				}
			}
		}

		return $result;
	}

	public function getThongTinTroThuLichDieuTri( $is_excel = false, $thao_tac ) {
		$list   = $this->phongKhamLichDieuTriHasMany;
		$result = "";
		$aExit  = [];
		$user   = new \common\models\UserProfile();
		if ( ! $is_excel ) {
			foreach ( $list as $item ) {
				if ( ! empty( $item->thao_tac ) && in_array( $thao_tac, $item->thao_tac ) ) {
					$tro_thu_lich = $item->tro_thu;
					foreach ( $tro_thu_lich as $tro_thu ) {
						if ( ! isset( $aExit[ $tro_thu ] ) ) {
							$aExit[ $tro_thu ] = $tro_thu;
							$fullname          = $user->getFullNameBacSi( $tro_thu );
							if ( $fullname == false ) {
								$result .= null;
							}
							$result .= $fullname . "<br>";
						}
					}
				}
			}
		} else {
			foreach ( $list as $item ) {
				if ( ! empty( $item->thao_tac ) && in_array( $thao_tac, $item->thao_tac ) ) {
					$tro_thu_lich = $item->tro_thu;
					foreach ( $tro_thu_lich as $tro_thu ) {
						if ( ! isset( $aExit[ $tro_thu ] ) ) {
							$aExit[ $tro_thu ] = $tro_thu;
							$fullname          = $user->getFullNameBacSi( $tro_thu );
							if ( $fullname == false ) {
								$result .= null;
							}
							$result .= $fullname . "\n";
						}
					}
				}
			}
		}

		return $result;
	}

	public function showHoanThanh( $color = false ) {
		if ( ! $color ) {
			$list = $this->getListTrangThaiDonDichVu();

			return $list[ $this->getKiemTraHoanThanh() ] ? $list[ $this->getKiemTraHoanThanh() ] : "";
		} else {
			$list = $this->getListTrangThaiDonDichVu();
			if ( $this->getKiemTraHoanThanh() == self::HOAN_THANH_KHAM ) {
				return '<span class="badge badge-success badge-pill ">' . $list[ self::HOAN_THANH_KHAM ] . '</span>';
			} else {
				return '<span class="badge badge-warning badge-pill ">' . $list[ self::CHUA_HOAN_THANH_KHAM ] . '</span>';
			}
		}
	}

	public static function getListTrangThaiDonDichVu() {
		return [
			self::HOAN_THANH_KHAM      => "Hoàn thành DV",
			self::CHUA_HOAN_THANH_KHAM => "Chưa hoàn thành DV",
		];
	}

	public function getKiemTraHoanThanh() {
		$list = $this->phongKhamLichDieuTriHasMany;
		foreach ( $list as $item ) {
			if ( $item->last_dieu_tri == 1 ) {
				return 1;
			}
		}

		return 0;
	}

	public function showHoanThanhThanhToan() {
		$list = $this->getListTrangThaiDonThanhToan();

		//        return $this->trang_thai_hoan_thanh;
		return isset( $list[ $this->trang_thai_hoan_thanh ] ) ? $list[ $this->trang_thai_hoan_thanh ] : $list[ self::CHUA_HOAN_THANH_THANH_TOAN ];
	}

	public static function getListTrangThaiDonThanhToan() {
		return [
			self::HOAN_THANH_THANH_TOAN      => "Hoàn thành",
			self::CHUA_HOAN_THANH_THANH_TOAN => "Còn nợ",
		];
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			return false;
		}

		$user = UserProfile::getUserCreatedOrUpdateBy( $id );

		return $user;
	}

	public function getUserUpdatedBy( $id ) {
		if ( $id == null ) {
			return false;
		}
		$user = UserProfile::getUserCreatedOrUpdateBy( $id );

		return $user;
	}

	public function getTotalTongTien( $params = null, $customer_id = null ) {
		$query    = self::find()->select( [ self::tableName() . '.thanh_tien' ] )->joinWith( [ 'customerOnlineHasOne' ] );
		$user     = new User();
		$roleUser = $user->getRoleName( \Yii::$app->user->id );
		if ( $roleUser == User::USER_DIRECT_SALE ) {
			$query->andFilterWhere( [ 'dep365_customer_online.directsale' => \Yii::$app->user->id ] );
		}
		if ( $roleUser == User::USER_LE_TAN ) {
			$co_so = $user->getCoso( \Yii::$app->user->id );
			$query->andFilterWhere( [ self::tableName() . '.co_so' => $co_so->permission_coso ] );
		}
		$customer = null;
		if ( $customer_id != null ) {
			$customer = Clinic::find()->where( [ 'id' => $customer_id ] )->one();
		}
		$searchModel = new PhongKhamDonHangSearch();
		$searchModel->load( $params );
		if ( $searchModel->button == '' && $customer == null ) {
			$searchModel->type_search_create = 'range';
			$searchModel->creation_time_from = date( '01-m-Y' );
			$searchModel->creation_time_to   = date( 'd-m-Y' );
		}
		if ( $searchModel->button == 2 ) {
			$searchModel->creation_time_from = date( 'd-m-Y', strtotime( date( 'd-m-Y' ) . ' -1 days' ) );
			$searchModel->type_search_create = 'date';
		}
		if ( $searchModel->button == 3 ) {
			$searchModel->creation_time_from = date( 'd-m-Y', strtotime( date( 'd-m-Y' ) ) );
			$searchModel->type_search_create = 'date';
		}
		if ( $customer != null ) {
			$query->andWhere( [ self::tableName() . '.customer_id' => $customer_id ] );
			$searchModel->keyword        = $customer->customer_code;
			$searchModel->direct_sale_id = $customer->directsale;
			$searchModel->tu_van_vien    = $customer->permission_user;
		}
		/* Search ngày tạo */
		if ( isset( $searchModel->type_search_create ) ) {
			if ( $searchModel->type_search_create == 'date' ) {
				if ( isset( $searchModel->creation_time_from ) && $searchModel->creation_time_from != null ) {
					$from = strtotime( $searchModel->creation_time_from );
					$to   = strtotime( $searchModel->creation_time_from ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.ngay_tao', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.ngay_tao', $to ] );
				}
			} else {
				if (
					isset( $searchModel->creation_time_from ) && isset( $searchModel->creation_time_to ) &&
					$searchModel->creation_time_from != null && $searchModel->creation_time_to != null
				) {
					$from = strtotime( $searchModel->creation_time_from );
					$to   = strtotime( $searchModel->creation_time_to ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.ngay_tao', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.ngay_tao', $to ] );
				}
			}
		}

		/* Search ngày thanh toán */
		if ( isset( $searchModel->type_search_payment ) ) {
			if ( $searchModel->type_search_payment == 'date' ) {
				if ( isset( $searchModel->payment_time_from ) && $searchModel->payment_time_from != null ) {
					$from = strtotime( $searchModel->payment_time_from );
					$to   = strtotime( $searchModel->payment_time_from ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.created_at', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.created_at', $to ] );
				}
			} else {
				if (
					isset( $searchModel->payment_time_from ) && isset( $searchModel->payment_time_to ) &&
					$searchModel->payment_time_from != null && $searchModel->payment_time_to != null
				) {
					$from = strtotime( $searchModel->payment_time_from );
					$to   = strtotime( $searchModel->payment_time_to ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.created_at', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.created_at', $to ] );
				}
			}
		}

		/* Search name, phone, code */
		if ( isset( $searchModel->keyword ) && $searchModel->keyword != null ) {
			$searchModel->keyword = trim( $searchModel->keyword );
			$searchModel->keyword = preg_replace( '/\s+/', ' ', $searchModel->keyword );
			$query->andFilterWhere( [
				'or',
				[ 'like', CustomerModel::tableName() . '.full_name', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.forename', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.name', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.phone', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.customer_code', $searchModel->keyword ],
				[ 'like', self::tableName() . '.order_code', $searchModel->keyword ],
			] );
		}

		// grid filtering conditions
		if ( isset( $searchModel->direct_sale_id ) && $searchModel->direct_sale_id != null ) {
			$query->andFilterWhere( [ self::tableName() . '.direct_sale_id' => $searchModel->direct_sale_id ] );
		}

		if ( isset( $searchModel->tu_van_vien ) && $searchModel->tu_van_vien != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.permission_user' => $searchModel->tu_van_vien ] );
		}

		if ( isset( $searchModel->co_so ) && $searchModel->co_so != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.co_so' => $searchModel->co_so ] );
		}

		if ( isset( $searchModel->id_dich_vu ) && $searchModel->id_dich_vu != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.id_dich_vu' => $searchModel->id_dich_vu ] );
		}

		return $query->sum( 'thanh_tien' );
	}

	public function getTotalChietKhau( $params = null, $customer_id = null ) {
		$query    = self::find()->select( [ self::tableName() . '.chiet_khau' ] )->joinWith( [ 'customerOnlineHasOne' ] );
		$user     = new User();
		$roleUser = $user->getRoleName( \Yii::$app->user->id );
		if ( $roleUser == User::USER_DIRECT_SALE ) {
			$query->andFilterWhere( [ 'dep365_customer_online.directsale' => \Yii::$app->user->id ] );
		}
		if ( $roleUser == User::USER_LE_TAN ) {
			$co_so = $user->getCoso( \Yii::$app->user->id );
			$query->andFilterWhere( [ self::tableName() . '.co_so' => $co_so->permission_coso ] );
		}
		$customer = null;
		if ( $customer_id != null ) {
			$customer = Clinic::find()->where( [ 'id' => $customer_id ] )->one();
		}
		$searchModel = new PhongKhamDonHangSearch();
		$searchModel->load( $params );
		if ( $searchModel->button == '' && $customer == null ) {
			$searchModel->type_search_create = 'range';
			$searchModel->creation_time_from = date( '01-m-Y' );
			$searchModel->creation_time_to   = date( 'd-m-Y' );
		}
		if ( $searchModel->button == 2 ) {
			$searchModel->creation_time_from = date( 'd-m-Y', strtotime( date( 'd-m-Y' ) . ' -1 days' ) );
			$searchModel->type_search_create = 'date';
		}
		if ( $searchModel->button == 3 ) {
			$searchModel->creation_time_from = date( 'd-m-Y', strtotime( date( 'd-m-Y' ) ) );
			$searchModel->type_search_create = 'date';
		}
		if ( $customer != null ) {
			$query->andWhere( [ self::tableName() . '.customer_id' => $customer_id ] );
			$searchModel->keyword        = $customer->customer_code;
			$searchModel->direct_sale_id = $customer->directsale;
			$searchModel->tu_van_vien    = $customer->permission_user;
		}
		/* Search ngày tạo */
		if ( isset( $searchModel->type_search_create ) ) {
			if ( $searchModel->type_search_create == 'date' ) {
				if ( isset( $searchModel->creation_time_from ) && $searchModel->creation_time_from != null ) {
					$from = strtotime( $searchModel->creation_time_from );
					$to   = strtotime( $searchModel->creation_time_from ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.ngay_tao', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.ngay_tao', $to ] );
				}
			} else {
				if (
					isset( $searchModel->creation_time_from ) && isset( $searchModel->creation_time_to ) &&
					$searchModel->creation_time_from != null && $searchModel->creation_time_to != null
				) {
					$from = strtotime( $searchModel->creation_time_from );
					$to   = strtotime( $searchModel->creation_time_to ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.ngay_tao', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.ngay_tao', $to ] );
				}
			}
		}

		/* Search ngày thanh toán */
		if ( isset( $searchModel->type_search_payment ) ) {
			if ( $searchModel->type_search_payment == 'date' ) {
				if ( isset( $searchModel->payment_time_from ) && $searchModel->payment_time_from != null ) {
					$from = strtotime( $searchModel->payment_time_from );
					$to   = strtotime( $searchModel->payment_time_from ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.created_at', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.created_at', $to ] );
				}
			} else {
				if (
					isset( $searchModel->payment_time_from ) && isset( $searchModel->payment_time_to ) &&
					$searchModel->payment_time_from != null && $searchModel->payment_time_to != null
				) {
					$from = strtotime( $searchModel->payment_time_from );
					$to   = strtotime( $searchModel->payment_time_to ) + 86399;
					$query->andFilterWhere( [ '>=', self::tableName() . '.created_at', $from ] );
					$query->andFilterWhere( [ '<=', self::tableName() . '.created_at', $to ] );
				}
			}
		}

		/* Search name, phone, code */
		if ( isset( $searchModel->keyword ) && $searchModel->keyword != null ) {
			$searchModel->keyword = trim( $searchModel->keyword );
			$searchModel->keyword = preg_replace( '/\s+/', ' ', $searchModel->keyword );
			$query->andFilterWhere( [
				'or',
				[ 'like', CustomerModel::tableName() . '.full_name', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.forename', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.name', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.phone', $searchModel->keyword ],
				[ 'like', CustomerModel::tableName() . '.customer_code', $searchModel->keyword ],
				[ 'like', self::tableName() . '.order_code', $searchModel->keyword ],
			] );
		}

		// grid filtering conditions
		if ( isset( $searchModel->direct_sale_id ) && $searchModel->direct_sale_id != null ) {
			$query->andFilterWhere( [ self::tableName() . '.direct_sale_id' => $searchModel->direct_sale_id ] );
		}

		if ( isset( $searchModel->tu_van_vien ) && $searchModel->tu_van_vien != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.permission_user' => $searchModel->tu_van_vien ] );
		}

		if ( isset( $searchModel->co_so ) && $searchModel->co_so != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.co_so' => $searchModel->co_so ] );
		}

		if ( isset( $searchModel->id_dich_vu ) && $searchModel->id_dich_vu != null ) {
			$query->andFilterWhere( [ CustomerModel::tableName() . '.id_dich_vu' => $searchModel->id_dich_vu ] );
		}

		return $query->sum( 'chiet_khau' );
	}

	public function checkHuyDichVu() {
		$hoan_coc   = self::getThanhToanByType( $this->id, ThanhToanModel::HOAN_COC );
		$thanh_toan = self::getThanhToanByType( $this->id, [ ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC ] );

		if ( empty( $thanh_toan ) && empty( $hoan_coc ) ) {
			return "";
		}
		if ( $thanh_toan - $hoan_coc == 0 ) { // @NGHIA : loi khach hang chiet khau 100%
			return "Hủy";
		}

		return ""; //$wThanhToan->tien_thanh_toan;
	}

	public static function getThanhToanByType( $id = null, $type = ThanhToanModel::THANH_TOAN ) {
		$query = ThanhToanModel::find()->select( [ 'tien_thanh_toan' ] )->where( [ 'phong_kham_don_hang_id' => $id ] );
		if ( is_array( $type ) ) {
			$query->andWhere( [ 'IN', 'tam_ung', $type ] );
		} else {
			$query->andWhere( [ 'tam_ung' => $type ] );
		}

		return $query->sum( 'tien_thanh_toan' );;
	}

	public static function getEdittableCoSo( $coso ) {
		$result               = [];
		$result['type']       = 'select';
		$result['dataChoose'] = (string) $coso;
		$arr                  = ArrayHelper::map( Dep365CoSo::getCoSo(), 'id', 'name' );
		$result['dataSelect'] = $arr;

		return json_encode( $result );
	}
}
