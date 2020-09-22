<?php

namespace backend\modules\clinic\controllers;

use backend\components\MyComponent;
use backend\controllers\CustomerController;
use backend\models\coupon\CouponUsedHistoryModel;
use backend\models\CustomerModel;
use backend\models\Dep365CustomerOnlineRemindCall;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\DatHen;
use backend\modules\clinic\models\DonHangBaoHanh;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\search\ClinicSearch;
use backend\modules\clinic\models\search\DonHangBaoHanhSearch;
use backend\modules\cskh\models\CskhChamSoc;
use backend\modules\cskh\models\CskhQuanLy;
use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\CustomerToken;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\directsale\models\DirectSaleRemindCall;
use backend\modules\setting\models\Setting;
use backend\modules\user\models\User;
use backend\modules\user\models\UserTimelineModel;
use GuzzleHttp\Client;
use Yii;
use yii\db\Exception;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;


/**
 * Default controller for the `clinic` module
 */
class ClinicController extends CustomerController {
	public function init() {
		parent::init();
	}

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex() {
		$searchModel  = new ClinicSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		if ( MyComponent::hasCookies( 'pageSize' ) ) {
			$dataProvider->pagination->pageSize = MyComponent::getCookies( 'pageSize' );
		} else {
			$dataProvider->pagination->pageSize = 10;
		}
		$pageSize   = $dataProvider->pagination->pageSize;
		$totalCount = $dataProvider->totalCount;
		$totalPage  = ( ( $totalCount + $pageSize - 1 ) / $pageSize );

		return $this->render( 'index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'totalPage'    => $totalPage,
		] );
	}

	public function actionCheckLetan() {
		if ( Yii::$app->request->isAjax ) {
			$dataOption            = Yii::$app->request->post( 'dataOption' );
			$id                    = Yii::$app->request->post( 'id' );
			$option                = Yii::$app->request->post( 'option' );
			$clinic                = DatHen::find()->where( [ 'id' => $id ] )->one();
			$user_timeline         = new UserTimelineModel();
			$user_timeline->action = UserTimelineModel::ACTION_CAP_NHAT;

			Yii::$app->response->format = Response::FORMAT_JSON;
			$transaction                = Yii::$app->db->beginTransaction( Transaction::SERIALIZABLE );
			if ( $clinic !== null ) {
				$user_timeline->customer_id = $clinic->primaryKey;
				switch ( $option ) {
					case 'dathen':
						$clinic->dat_hen = $dataOption;
						if ( $clinic->customer_come == null ) {
							$clinic->customer_come      = time();
							$clinic->customer_come_date = strtotime( date( 'd-m-Y', $clinic->customer_come ) );
						}
						$user_timeline->action = [
							UserTimelineModel::ACTION_CAP_NHAT,
							UserTimelineModel::ACTION_TRANG_THAI,
							UserTimelineModel::ACTION_DAT_HEN
						];
						break;
					case 'direct':
						$clinic->directsale    = $dataOption;
						$user_timeline->action = [
							UserTimelineModel::ACTION_CAP_NHAT,
							UserTimelineModel::ACTION_DIRECT_SALE
						];
						break;
					case 'co_so':
						$user     = new User();
						$roleName = $user->getRoleName( Yii::$app->user->id );
						if ( ! in_array( $roleName, [
							User::USER_DEVELOP,
							User::USER_ADMINISTRATOR,
							User::USER_QUANLY_PHONGKHAM
						] ) ) {
							return [
								'status' => 403
							];
						}
						$clinic->co_so = $dataOption;
						break;
				}
				if ( $clinic->save() ) {
					if ( ! $user_timeline->save() ) {
						$status = '403';
						$transaction->rollBack();
					}
					$status = '200';
				} else {
					$status = '403';
				}
				$transaction->commit();

				return [ 'status' => $status, 'data' => $clinic->getAttributes() ];
			}
		}
	}

	public function actionPerpage( $perpage ) {
		MyComponent::setCookies( 'pageSize', $perpage );
	}

	public function actionView( $id ) {
		if ( Yii::$app->request->isAjax && $this->findModel( $id ) ) {
			return $this->renderAjax( '@backend/views/layouts/customer_view', [
				'model' => $this->findModel( $id ),
			] );
		}
	}

	protected function findModel( $id ) {
		$model = Clinic::findOne( $id );
		if ( ( $model !== null ) ) {
			return $model;
		}

		return false;
	}

	public function actionOrderCustomer() {
		if ( Yii::$app->request->isAjax ) {
			$id    = Yii::$app->request->post( 'id' );
			$idGet = Yii::$app->request->get( 'id' );

			Yii::$app->response->format = Response::FORMAT_HTML;

			$ids      = $id != null ? $id : $idGet;
			$customer = $this->findModel( $ids );
			if ( ! $customer || $customer->customer_code == null ) {
				$error = 'Vui lòng cập nhật thông tin khách hàng trước. Xin cảm ơn!';

				return $this->renderAjax( '_error', [
					'error' => $error,
				] );
			}
			$listAccept = ArrayHelper::map( Dep365CustomerOnlineCome::find()->where( [ 'accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT ] )->all(), 'id', 'name' );
			if ( ! array_key_exists( $customer->customer_come_time_to, $listAccept ) ) {
				$error = 'Khách không đồng ý làm, không thể tạo đơn hàng!';

				return $this->renderAjax( '_error', [
					'error' => $error
				] );
			}

			$model = new PhongKhamDonHang();

			$orderData = PhongKhamDonHangWOrder::find()->where( [ 'phong_kham_don_hang_id' => $model->id ] )->all();
			//            if ($orderData == null) $orderData = new PhongKhamDonHangWOrder();
			if ( $model->load( Yii::$app->request->post() ) ) {

				//                var_dump($model->getAttributes());die;

				Yii::$app->response->format = Response::FORMAT_JSON;
				$dataOder                   = $model->customer_order;
				$model->customer_order      = json_encode( $model->customer_order );
				$model->chiet_khau          = str_replace( '.', '', $model->chiet_khau );
				$model->direct_sale_id      = $customer->directsale;

				$transaction = Yii::$app->db->beginTransaction(
					Transaction::SERIALIZABLE
				);
				/*coupon*/
				$coupon = new CouponUsedHistoryModel();

				if ( $coupon->load( Yii::$app->request->post() ) ) {
					if ( $coupon->loadAttributesApi() ) {
						$model->chiet_khau = ( intval( $model->chiet_khau ) + intval( $coupon->giaban ) );
					}
				}
				/*end coupon*/
				if ( $model->save() ) {
					$coso = Yii::$app->user->identity->permission_coso;
					if ( strlen( $coso ) < 0 ) {
						$coso = '0' . $coso;
					}
					$model->updateAttributes( [
						'order_code' => 'AUR' . $coso . '-HD' . $model->primaryKey
					] );
					$donhangTree     = new PhongKhamDonHangTree();
					$arr             = $model->getAttributes();
					$arr['id_order'] = $model->getPrimaryKey();
					unset( $arr['id'] );
					unset( $arr['customer_order'] );
					foreach ( $arr as $key => $item ) {
						$donhangTree->$key = $item;
					}

					if ( $donhangTree->save() ) {
						//Thêm order
						$arrID = [];
						$total = 0;
						foreach ( $dataOder as $value ) {
							$order = PhongKhamDonHangWOrder::find()->where( [ 'id' => $value['id'] ] )->one();
							if ( $order === null ) {
								$order = new PhongKhamDonHangWOrder();
							}
							$order->customer_id         = $model->customer_id;
							$user_timeline              = new UserTimelineModel();
							$user_timeline->action      = [
								UserTimelineModel::ACTION_TAO,
								UserTimelineModel::ACTION_DON_HANG
							];
							$user_timeline->customer_id = $model->customer_id;
							if ( ! $user_timeline->save() ) {
								$transaction->rollBack();

								return [
									'status' => true,
									'result' => 'Lưu thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.' . __LINE__
								];
							}
							//                            var_dump($order);
							foreach ( $value as $keys => $item ) {
								if ( $keys == 'id' || $keys == 'dich_vu' ) {
									continue;
								}
								if ( $keys == 'san_pham' ) {
									$sanpham        = PhongKhamSanPham::find()->joinWith( [ 'dichVuHasOne' ] )->where( [ PhongKhamSanPham::tableName() . '.id' => $item ] )->published()->one();
									$order->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : (string) $sanpham->dichVuHasOne->id;
								}
								if ( $keys == 'thanh_tien' ) {
									$order->thanh_tien = str_replace( '.', '', $item );
									$total             += $order->thanh_tien;
									continue;
								}
								if ( $keys == 'chiet_khau_order' ) {
									$order->chiet_khau_order = str_replace( '.', '', $item );
									continue;
								}
								$order->phong_kham_don_hang_id = $model->getPrimaryKey();
								$order->$keys                  = $item;
							}


							if ( ! $order->save() ) {
								$transaction->rollBack();

								return [
									'status' => true,
									'result' => 'Lưu thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.' . __LINE__
								];
							} else {
								$arrID[] = $order->getPrimaryKey();
							}
						}


						$model->updateAttributes( [
							'thanh_tien' => $total
						] );

						$arrIdNotIn = PhongKhamDonHangWOrder::find()->where( [
							'not in',
							'id',
							$arrID
						] )->andWhere( [ 'phong_kham_don_hang_id' => $model->primaryKey ] )->all();
						foreach ( $arrIdNotIn as $key => $val ) {
							$orderDel = PhongKhamDonHangWOrder::findOne( $val->id );
							if ( ! $orderDel->delete() ) {
								$transaction->rollBack();

								return [
									'status' => true,
									'result' => 'PhongKhamDonHangWOrder Lưu thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.' . __LINE__
								];
							}
						}

						if ( $coupon->coupon_code != null ) {
							/*coupon */
							if ( $coupon->validate() ) {
								$coupon->order_id = $model->id;
								$coupon->save();
								$transaction->commit();
							} else {
								Yii::error( json_encode( $coupon->getErrors() ) );
								$transaction->rollBack();

								return [
									'status' => true,
									'result' => 'Sử dụng coupon thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
								];
							}
							/*end coupon*/
						} else {
							$transaction->commit();
						}

						return [
							'status' => true,
							'result' => Yii::$app->params['create-success'],
						];
					} else {
						$transaction->rollBack();

						return [
							'status' => true,
							'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
						];
					}
				} else {
					$transaction->rollBack();

					return [
						'status' => true,
						'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.'
					];
				}
			}

			$listKhuyenMai = PhongKhamKhuyenMai::getListKhuyenMai();
			$couponModel   = new CouponUsedHistoryModel();

			return $this->renderAjax( '_order', [
				'model'         => $model,
				'customer'      => $customer,
				'orderData'     => $orderData,
				'listKhuyenMai' => $listKhuyenMai,
				'couponModel'   => $couponModel
			] );
		}
	}

	public function actionValidateOrder() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$idGet                      = Yii::$app->request->get( 'id' );
			$model                      = $this->findOrder( $idGet );
			if ( $model === false ) {
				$model = new PhongKhamDonHang();
			}


			if ( $model->load( Yii::$app->request->post() ) ) {
				$check                 = $model->customer_order;
				$model->customer_order = json_encode( $model->customer_order );
				$model->thanh_tien     = str_replace( '.', '', $model->thanh_tien );

				foreach ( $check as $key => $item ) {
					/*if ($item['dich_vu'] == 0) {
						$model->dich_vu = 0;
						$model->scenario = 'checkOrder';
					} else {
						$model->dich_vu = $item['dich_vu'];
					}*/
					if ( $item['san_pham'] == 0 ) {
						$model->s_p      = 0;
						$model->dich_vu  = null;
						$model->scenario = 'checkOrder';
					} else {
						$sanpham        = PhongKhamSanPham::find()->joinWith( [ 'dichVuHasOne' ] )->where( [ PhongKhamSanPham::tableName() . '.id' => $item['san_pham'] ] )->published()->one();
						$model->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : $sanpham->dichVuHasOne->id;
						$model->s_p     = $item['san_pham'];
					}
				}

				$coupon = new CouponUsedHistoryModel();
				if ( $coupon->load( Yii::$app->request->post() ) && isset( $coupon->coupon_code ) && ! empty( $coupon->coupon_code ) ) {
					return \yii\widgets\ActiveForm::validate( $model, $coupon );
				} else {
					return \yii\widgets\ActiveForm::validate( $model );
				}

			}

			return [];
		}
	}

	protected function findOrder( $id ) {
		$order = PhongKhamDonHang::find()->where( [ 'customer_id' => $id ] )->one();
		if ( $order !== null ) {
			return $order;
		}

		return false;
	}

	public function actionGetPriceSanPham() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$id                         = Yii::$app->request->post( 'id' );
			$sl                         = Yii::$app->request->post( 'sl' );
			$price                      = 0;
			$sanpham                    = new PhongKhamSanPham();
			$data                       = $sanpham->getSanPhamOne( $id );
			if ( $data != null && $data->don_gia != null ) {
				$price = $sl * $data->don_gia;
			}

			return [
				'status' => true,
				'result' => number_format( $price, 0, ',', '.' ),
			];
		}
	}

	public function actionRenderAndUpdate( $id ) {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_HTML;
			$idAjax                     = Yii::$app->request->post( 'id' );
			$ids                        = $idAjax == null ? $id : $idAjax;
			$model                      = $this->findModel( $ids );
			$model->dathen_time         = date( 'd-m-Y H:i' );
			$user                       = new User();
			$roleName                   = $user->getRoleName( Yii::$app->user->id );
			if ( in_array( $roleName, [ User::USER_DEVELOP, User::USER_ADMINISTRATOR ] ) ) {
				$model->scenario = Clinic::SCENARIO_ADMIN;
			} else {
				$model->scenario = Clinic::SCENARIO_UPDATE;
			}
			if ( $model->ngay_dong_y_lam != null ) {
				$model->ngay_dong_y_lam = date( 'd-m-Y', $model->ngay_dong_y_lam );
			}
			$user_timeline              = new UserTimelineModel();
			$user_timeline->action      = UserTimelineModel::ACTION_CAP_NHAT;
			$user_timeline->customer_id = $model->primaryKey;

			$modelRemindCustomer = CustomerOnlineRemindCall::find()
			                                               ->where( [
				                                               'type'        => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE,
				                                               'customer_id' => $model->primaryKey
			                                               ] )
			                                               ->published()
			                                               ->orderBy( [ 'id' => SORT_DESC ] )
			                                               ->one();
			if ( $modelRemindCustomer == null ) {
				$modelRemindCustomer = new CustomerOnlineRemindCall();
			}
			$modelRemindDirectSale = DirectSaleRemindCall::find()
			                                             ->where( [
				                                             'type'        => DirectSaleRemindCall::TYPE_DIRECT_SALE,
				                                             'customer_id' => $model->primaryKey
			                                             ] )
			                                             ->published()
			                                             ->orderBy( [ 'id' => SORT_DESC ] )
			                                             ->one();
			if ( $modelRemindDirectSale == null ) {
				$modelRemindDirectSale = new DirectSaleRemindCall();
			}

			$listAccept     = ArrayHelper::map( Dep365CustomerOnlineCome::find()->published()->andWhere( [ 'accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT ] )->all(), 'id', 'name' );
			$directsale_old = $model->getAttribute( 'directsale' );
			if ( $model->load( Yii::$app->request->post() ) ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				if ( $model->validate() ) {
					$transaction = Yii::$app->db->beginTransaction(
						Transaction::SERIALIZABLE
					);

					$create_new_dathen_time  = false;
					$data_create_dathen_time = [];

					if ( $roleName === User::USER_DEVELOP && $model->change_permission_for_online == true && $model->new_permission_user != null ) {
						$model->permission_old  = $model->permission_user;
						$model->permission_user = $model->new_permission_user;
						$model->is_customer_who = Dep365CustomerOnline::IS_CUSTOMER_TV_ONLINE;
						$check_dathen_time      = Dep365CustomerOnlineDathenTime::find()->where( [ 'customer_online_id' => $model->primaryKey ] )->count();
						if ( $check_dathen_time <= 0 ) {
							$create_new_dathen_time  = true;
							$data_create_dathen_time = [
								'time_lichhen_new' => strtotime( $model->dathen_time ),
								'user_id'          => $model->permission_user,
								'date_lichhen_new' => strtotime( date( 'd-m-Y', strtotime( $model->dathen_time ) ) ),
								'date_change'      => strtotime( date( 'd-m-Y' ) ),
								'time_change'      => time()
							];
						}
					}

					if ( $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN ) {
						$model->customer_come         = null;
						$model->customer_come_date    = null;
						$model->customer_come_time_to = null;
						$user_timeline->action        = [
							UserTimelineModel::ACTION_CAP_NHAT,
							UserTimelineModel::ACTION_TRANG_THAI,
							UserTimelineModel::ACTION_DAT_HEN
						];
					}
					if ( $model->dat_hen == Dep365CustomerOnline::DAT_HEN_DEN ) {
						$model->customer_come      = strtotime( $model->customer_come );
						$model->customer_come_date = strtotime( date( 'd-m-Y', $model->customer_come ) );
						$user_timeline->action     = [
							UserTimelineModel::ACTION_CAP_NHAT,
							UserTimelineModel::ACTION_TRANG_THAI,
							UserTimelineModel::ACTION_THAM_KHAM
						];
					}
					try {
						$dat_hen_old = $model->getOldAttribute( 'dat_hen' );
						if ( ! $model->save() ) {
							$transaction->rollBack();

							return [
								'status' => $model->getErrors(),
								'result' => Yii::$app->params['update-danger'],
							];
						}

						if ( $create_new_dathen_time == true ) {
							$modelDatHen = new Dep365CustomerOnlineDathenTime();
							foreach (
								array_merge( $data_create_dathen_time, [
									'customer_online_id' => $model->primaryKey
								] ) as $attr => $val
							) {
								if ( $modelDatHen->canSetProperty( $attr ) ) {
									$modelDatHen->$attr = $val;
								}
							}
							if ( ! $modelDatHen->save() ) {
								$transaction->rollBack();

								return [
									'status' => $modelDatHen->getErrors(),
									'result' => Yii::$app->params['update-danger'],
								];
							}
						}
						/*
						 * trạng thái đặt hẹn = ĐẶT HẸN KHÔNG ĐẾN
						 * => lưu remind call cho CUSTOMER ONLINE
						 * => cập nhật remind_call_status = DISABLED cho DIRECT SALE (nếu tồn tại remind call)
						 *
						 * trạng thái đặt hẹn = ĐẶT HẸN ĐẾN:
						 * => cập nhật remind_call_status = DISABLED cho CUSTOMER ONLINE (nếu tồn tại remind call)
						 * + khách làm dịch vụ => cập nhật remind_call_status = DISABLED cho DIRECT SALE (nếu tồn tại remind call)
						 * + khách không làm dịch vụ => lưu lại remind call cho DIRECT SALE
						 */
						if ( $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN ) {
							/* Đặt hẹn không đến */
							if ( $modelRemindCustomer->primaryKey == null ) {
								$modelRemindCustomer->customer_id = $model->primaryKey;
								$modelRemindCustomer->type        = CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE;
								/*
								 * Note 1/10/2019: Chuyển ngày nhắc lịch lại (từ nhắc lịch ngày hôm sau thành nhắc lịch ngày hôm nay)
								 * */
							}
							$modelRemindCustomer->status           = CustomerModel::STATUS_DH;
							$modelRemindCustomer->dat_hen          = Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
							$modelRemindCustomer->permission_user  = $model->permission_user;
							$modelRemindCustomer->created_by       = $model->permission_user;
							$modelRemindCustomer->remind_call_time = strtotime( date( 'd-m-Y' ) ); // strtotime(date('d-m-Y', strtotime('+1day')));
							if ( ! $modelRemindCustomer->save() ) {
								$transaction->rollBack();

								return [
									'status' => $modelRemindCustomer->getErrors(),
									'result' => Yii::$app->params['update-danger']
								];
							}
							if ( $modelRemindDirectSale->primaryKey != null ) {
								$modelRemindDirectSale->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
								if ( ! $modelRemindDirectSale->save() ) {
									$transaction->rollBack();

									return [
										'status' => $modelRemindDirectSale->getErrors(),
										'result' => Yii::$app->params['update-danger']
									];
								}
							}
						} else {
							/* Đặt hẹn đến */
							if ( $modelRemindCustomer->primaryKey != null ) {
								$modelRemindCustomer->remind_call_status = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
								if ( ! $modelRemindCustomer->save() ) {
									$transaction->rollBack();

									return [
										'status' => $modelRemindCustomer->getErrors(),
										'result' => Yii::$app->params['update-danger']
									];
								}
							}
							if ( array_key_exists( $model->customer_come_time_to, $listAccept ) ) {
								/* Khách đồng ý làm dịch vụ */
								if ( $modelRemindDirectSale->primaryKey != null ) {
									$modelRemindDirectSale->customer_come_time_to = $model->customer_come_time_to;
									$modelRemindDirectSale->remind_call_status    = Dep365CustomerOnlineRemindCall::STATUS_DISABLED;
									if ( ! $modelRemindDirectSale->save() ) {
										$transaction->rollBack();

										return [
											'status' => $modelRemindDirectSale->getErrors(),
											'result' => Yii::$app->params['update-danger']
										];
									}
								}
							} else {
								/* Khách không đồng ý làm dịch vụ */
								if ( $modelRemindDirectSale->primaryKey == null ) {
									$modelRemindDirectSale->customer_id = $model->primaryKey;
									$modelRemindDirectSale->status      = CustomerModel::STATUS_DH;
									$modelRemindDirectSale->dat_hen     = Dep365CustomerOnline::DAT_HEN_DEN;
								}
								$modelRemindDirectSale->customer_come_time_to = $model->customer_come_time_to;
								$modelRemindDirectSale->remind_call_time      = $model->remind_call_time != null ? strtotime( $model->remind_call_time ) : strtotime( date( 'd-m-Y' ) );
								if ( ! $modelRemindDirectSale->save() ) {
									$transaction->rollBack();

									return [
										'status' => $modelRemindDirectSale->getErrors(),
										'result' => Yii::$app->params['update-danger']
									];
								}
							}
						}

						$dat_hen_new = $model->getAttribute( 'dat_hen' );
						if ( $dat_hen_new == Dep365CustomerOnline::DAT_HEN_DEN && $dat_hen_old != Dep365CustomerOnline::DAT_HEN_DEN ) {
							$customerToken = CustomerToken::find()->where( [
								'customer_id' => $model->primaryKey,
								'type'        => CustomerToken::TYPE_CUSTOMER_FEEDBACK,
								'status'      => CustomerToken::STATUS_DISABLED
							] )->one();
							/* CÓ TOKEN RỒI THÌ KHÔNG TẠO NỮA */
							if ( $customerToken == null ) {
								if ( ! CustomerToken::quickCreate( $model->primaryKey, null, null, CustomerToken::TYPE_CUSTOMER_FEEDBACK ) ) {
									$transaction->rollBack();

									return [
										'status' => 400,
										'result' => Yii::$app->params['update-danger']
									];
								} else {
									$cache = Yii::$app->cache;
									$key   = 'redis-screen-online';
									$cache->set( $key, [
										'customer_id' => $model->primaryKey,
										'status'      => UserTimelineModel::ACTION_THAM_KHAM
									] );
								}
							}
						} else {
							$cache = Yii::$app->cache;
							$key   = 'redis-screen-online';
							$cache->set( $key, [
								'srcOnlTimeline' => UserTimelineModel::ACTION_CAP_NHAT,
							] );
						}
						$directsale_new = $model->getAttribute( 'directsale' );
						if ( $directsale_old != null && $directsale_new != null && $directsale_new != $directsale_old ) {
							/* THÔNG BÁO TỚI APP DICRECT SALE ĐƯỢC PHÂN CÔNG CHĂM SÓC KHÁCH HÀNG */
							$setting = Setting::find()->where( [ 'key_value' => 'khach_phan_cong_cho_directsale' ] )->one();
							if ( $setting != null ) {
								if ( CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/ ) {
									$client = new Client( [
										'verify' => Url::to( '@backend/modules/clinic/token/cacert.pem' )
									] );
								} else {
									$client = new Client();
								}
								if ( $model->full_name != null ) {
									$customer = $model->full_name;
								} elseif ( $model->forename != null ) {
									$customer = $model->forename;
								} else {
									$customer = $model->name;
								}
								$content = str_replace( '{$customer}', $customer, $setting->value );
								$client->request( 'POST', 'https://api.myauris.vn/api/CreateNoti', [
									'verify'      => false,
									'form_params' => [
										'name'        => $setting->param,
										'content'     => $content,
										'description' => $content,
										'user_id'     => $directsale_new,
										'customer_id' => $model->primaryKey,
										'type'        => 3
									]
								] );
							}
						}

						if ( ! $user_timeline->save() ) {
							$transaction->rollBack();

							return [
								'status' => $user_timeline->getErrors(),
								'result' => Yii::$app->params['update-danger']
							];
						}
						$transaction->commit();

						return [
							'status' => 1,
							'result' => Yii::$app->params['update-success'],
						];
					} catch ( Exception $ex ) {
						return [
							'status' => $ex->getMessage(),
							'result' => Yii::$app->params['update-danger'],
						];
					}
				} else {
					return [
						'status' => 0,
						'result' => 'Lỗi dữ liệu',
						'error'  => $model->getErrors()
					];
				}
			}

			return $this->renderAjax( 'create-ajax', [
				'model'      => $model,
				'listAccept' => $listAccept
			] );
		}
	}

	public function actionValidateRenderAndUpdate( $id ) {
		if ( Yii::$app->request->isAjax ) {
			$model           = $this->findModel( $id );
			$model->scenario = Clinic::SCENARIO_UPDATE;
			if ( $model->load( Yii::$app->request->post() ) ) {
				return Json::encode( \yii\widgets\ActiveForm::validate( $model ) );
			}
		}
	}

	public function actionCreate() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_HTML;
			$user_timeline              = new UserTimelineModel();
			$user_timeline->action      = [ UserTimelineModel::ACTION_THEM, UserTimelineModel::ACTION_DAT_HEN ];
			$model                      = new Clinic();
			$model->scenario            = Clinic::PHONE_CREATE;
			$model->dat_hen             = Dep365CustomerOnline::DAT_HEN_DEN;
			$listAccept                 = ArrayHelper::map( Dep365CustomerOnlineCome::find()->published()->andWhere( [ 'accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT ] )->all(), 'id', 'name' );
			if ( $model->load( Yii::$app->request->post() ) ) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				$model->customer_come       = strtotime( $model->customer_come );
				$model->customer_come_date  = strtotime( date( 'd-m-Y', $model->customer_come ) );
				if ( ! $model->validate() ) {
					return [
						'status' => 0,
						'result' => 'Lỗi dữ liệu'
					];
				}
				$transaction = Yii::$app->db->beginTransaction(
					Transaction::SERIALIZABLE
				);
				try {
					if ( ! $model->save() ) {
						$transaction->rollBack();

						return [
							'status' => 0,
							'result' => Yii::$app->params['create-danger'],
							'error'  => $model->getErrors()
						];
					}
					$id = $model->primaryKey;
					if ( strlen( Yii::$app->user->identity->permission_coso ) == 1 ) {
						$coso = '0' . Yii::$app->user->identity->permission_coso;
					} else {
						$coso = Yii::$app->user->identity->permission_coso;
					}

					$model->updateAttributes( [ 'customer_code' => 'AUR' . $coso . '-' . $id ] );
					$user_timeline->customer_id = $model->primaryKey;
					if ( ! $user_timeline->save() ) {
						$transaction->rollBack();

						return [
							'status' => 0,
							'result' => Yii::$app->params['create-danger'],
						];
					}

					if ( $model->getAttribute( 'dat_hen' ) == Dep365CustomerOnline::DAT_HEN_DEN ) {
						$customerToken = CustomerToken::find()->where( [
							'customer_id' => $model->primaryKey,
							'type'        => CustomerToken::TYPE_CUSTOMER_FEEDBACK,
							'status'      => CustomerToken::STATUS_DISABLED
						] )->one();
						/* CÓ TOKEN RỒI THÌ KHÔNG TẠO NỮA */
						if ( $customerToken == null ) {
							if ( ! CustomerToken::quickCreate( $model->primaryKey, null, null, CustomerToken::TYPE_CUSTOMER_FEEDBACK ) ) {
								$transaction->rollBack();

								return [
									'status' => 400,
									'result' => Yii::$app->params['update-danger']
								];
							} else {
								$cache = Yii::$app->cache;
								$key   = 'redis-screen-online';
								$cache->set( $key, [
									'customer_id' => $model->primaryKey,
									'status'      => UserTimelineModel::ACTION_THAM_KHAM
								] );
							}
						}
					}

					$setting = Setting::find()->where( [ 'key_value' => 'khach_phan_cong_cho_directsale' ] )->one();
					if ( $setting != null ) {
						if ( CONSOLE_HOST == false/*\Yii::$app->request->getUserIP() == '127.0.0.1'*/ ) {
							$client = new Client( [
								'verify' => Url::to( '@backend/modules/clinic/token/cacert.pem' )
							] );
						} else {
							$client = new Client();
						}
						if ( $model->full_name != null ) {
							$customer = $model->full_name;
						} elseif ( $model->forename != null ) {
							$customer = $model->forename;
						} else {
							$customer = $model->name;
						}
						$content = str_replace( '{$customer}', $customer, $setting->value );
						$client->request( 'POST', 'https://api.myauris.vn/api/CreateNoti', [
							'verify'      => false,
							'form_params' => [
								'name'        => $setting->param,
								'content'     => $content,
								'description' => $content,
								'user_id'     => $model->getAttribute( 'directsale' ),
								'customer_id' => $model->primaryKey,
								'type'        => 3
							]
						] );
					}
					$transaction->commit();

					return [
						'status' => 1,
						'result' => Yii::$app->params['create-success'],
					];
				} catch ( Exception $ex ) {
					$transaction->rollBack();

					return [
						'status' => 0,
						'result' => Yii::$app->params['create-danger'],
						'error'  => $ex->getMessage()
					];
				}
			}

			return $this->renderAjax( 'create-ajax', [
				'model'      => $model,
				'listAccept' => $listAccept
			] );
		}
	}

	public function actionValidateCreate() {
		if ( Yii::$app->request->isAjax ) {
			$model           = new Clinic();
			$model->scenario = Clinic::PHONE_CREATE;
			if ( $model->load( Yii::$app->request->post() ) ) {
				Yii::$app->response->format = Response::FORMAT_JSON;

				return ActiveForm::validate( $model );
			}
		}
	}

	public function actionBaoHanh( $customer_id ) {
		$allPhongKhamDonHang = PhongKhamDonHang::find()->where( [ 'customer_id' => $customer_id ] );
		$mCustomer           = CustomerModel::findOne( $customer_id );
		$model               = new DonHangBaoHanh();
		if ( $model->load( Yii::$app->request->post() ) ) {
			$model->ngay_thuc_hien = strtotime( $model->ngay_thuc_hien );
			$model->save();
			Yii::$app->session->setFlash( 'alert-bao-hanh', [
				'body'  => 'Lưu bảo hành thành công',
				'class' => 'bg-success',
			] );
		}
		if ( $allPhongKhamDonHang->count() ) {
			$listDonHang  = $listDonHangSearch = [];
			$arrayDonHang = $allPhongKhamDonHang->all();
			foreach ( $arrayDonHang as $element ) {
				$listDonHang[ $element->id ] = $element->order_code . "- Thành tiền : " . number_format( $element->thanh_tien, 0, '.', '.' );
				$listDonHangSearch[]         = $element->id;
			}
			//
			$searchModel  = new DonHangBaoHanhSearch();
			$dataProvider = $searchModel->search( $listDonHangSearch );

			if ( MyComponent::hasCookies( 'pageSize' ) ) {
				$dataProvider->pagination->pageSize = MyComponent::getCookies( 'pageSize' );
			} else {
				$dataProvider->pagination->pageSize = 10;
			}
			$pageSize   = $dataProvider->pagination->pageSize;
			$totalCount = $dataProvider->totalCount;
			$totalPage  = ( ( $totalCount + $pageSize - 1 ) / $pageSize );


			return $this->render( 'index-bao-hanh', [
				'listDonHang'  => $listDonHang,
				'model'        => $model,
				'customer_id'  => $customer_id,
				'dataProvider' => $dataProvider,
				'totalPage'    => $totalPage,
				'mCustomer'    => $mCustomer,
			] );
		}
		Yii::$app->session->setFlash( 'alert-bao-hanh', [
			'body'  => 'Không có đơn hàng',
			'class' => 'bg-danger',
		] );

		return $this->redirect( Yii::$app->request->referrer );
	}

	// Nghia 7/3/2020

	public function actionDeleteBaoHanh() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$id                         = Yii::$app->request->post( 'id' );
			try {
				$mDonHangBaoHanh = DonHangBaoHanh::findOne( $id );
				if ( $mDonHangBaoHanh !== null && $mDonHangBaoHanh->delete() ) {
					return [
						"status" => "success"
					];
				} else {
					return [
						"status" => "failure"
					];
				}
			} catch ( \yii\db\Exception $e ) {
				return [
					"status" => "exception"
				];
			}
		}
	}

	protected function findDieuTri( $id ) {
		$dieuTri = PhongKhamLichDieuTri::find()->where( [ 'customer_id' => $id ] )->one();
		if ( $dieuTri !== null ) {
			return $dieuTri;
		}

		return false;
	}

	// CSKH
	public function actionChamSoc( $id ) {
		if ( Yii::$app->request->isAjax ) {
			$model = CskhQuanLy::find()->where( [ 'customer_id' => $id ] )->one();
			if ( empty( Yii::$app->request->post() ) ) {
				if ( $model !== null ) {

					if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						try {
							$model->save();

							return [
								'status' => 1,
								'result' => Yii::$app->params['update-success'],
							];
						} catch ( Exception $ex ) {
							return [
								'status' => $ex->getMessage(),
								'result' => Yii::$app->params['update-danger'],
							];
						}
					}

					return $this->renderAjax( '_cham_soc_ajax', [
						'model' => $model,
					] );
				} else {
					$model              = new CskhQuanLy();
					$model->customer_id = $id;

					return $this->renderAjax( '_cham_soc_ajax', [
						'model' => $model,
					] );
				}
			} else {
				if ( $model !== null ) {
					if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						try {
							$model->save();

							return [
								'status' => 1,
								'result' => Yii::$app->params['update-success'],
							];
						} catch ( Exception $ex ) {
							return [
								'status' => $ex->getMessage(),
								'result' => Yii::$app->params['update-danger'],
							];
						}
					}
				} else {
					$model = new CskhQuanLy();
					if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
						Yii::$app->response->format = Response::FORMAT_JSON;
						try {
							$model->save();

							return [
								'status' => 1,
								'result' => Yii::$app->params['update-success'],
							];
						} catch ( Exception $ex ) {
							return [
								'status' => $ex->getMessage(),
								'result' => Yii::$app->params['update-danger'],
							];
						}
					}
				}
			}
		}
	}

	public function actionValidateChamSoc() {
		if ( Yii::$app->request->isAjax ) {
			$model = new CskhQuanLy();
			// $model->scenario = Clinic::PHONE_CREATE;
			if ( $model->load( Yii::$app->request->post() ) ) {
				Yii::$app->response->format = Response::FORMAT_JSON;

				return ActiveForm::validate( $model );
			}
		}
	}
}
