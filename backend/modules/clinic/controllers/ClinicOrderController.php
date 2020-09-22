<?php

namespace backend\modules\clinic\controllers;

use app\backend\models\PhongKhamThongBao;
use backend\components\MyComponent;
use backend\models\coupon\CouponUsedHistoryModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\DatHen;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamLichDieuTriTree;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\setting\models\Setting;
use backend\modules\user\models\UserTimelineModel;
use common\models\UserProfile;
use GuzzleHttp\Client;
use Yii;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\search\PhongKhamDonHangSearch;
use backend\components\MyController;
use backend\modules\clinic\models\CheckcodeBaoHanh;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\db\Transaction;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\modules\user\models\User;

/**
 * ClinicOrderController implements the CRUD actions for PhongKhamDonHang model.
 */
class ClinicOrderController extends MyController
{
	public function actionIndex($customer_id = null)
	{
		$searchModel = new PhongKhamDonHangSearch();
		$customer    = null;
		if ($customer_id != null) {
			$customer = Clinic::find()->where(['id' => $customer_id])->one();
			if ($customer == null) {
				return $this->redirect(['/clinic/clinic-order']);
			}
		}
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $customer_id);

		$thanhtoan = new PhongKhamDonHangWThanhToan();

		/* Lấy dữ liệu theo search */
		$dh_tong_tien  = $searchModel->getTotalTongTien(Yii::$app->request->queryParams);
		$dh_thanh_toan = $thanhtoan->getTotalThanhToan(Yii::$app->request->queryParams);
		$dh_chiet_khau = $searchModel->getTotalChietKhau(Yii::$app->request->queryParams);
		$dh_dat_coc    = $thanhtoan->getTotalDatCoc(Yii::$app->request->queryParams);

		if (MyComponent::hasCookies('pageSize')) {
			$dataProvider->pagination->pageSize = MyComponent::getCookies('pageSize');
		} else {
			$dataProvider->pagination->pageSize = 10;
		}
		$pageSize   = $dataProvider->pagination->pageSize;
		$totalCount = $dataProvider->totalCount;
		$totalPage  = (($totalCount + $pageSize - 1) / $pageSize);

		return $this->render('index', [
			'customer'      => $customer,
			'searchModel'   => $searchModel,
			'dataProvider'  => $dataProvider,
			'totalPage'     => $totalPage,
			'dh_tong_tien'  => $dh_tong_tien ?: 0,
			'dh_thanh_toan' => $dh_thanh_toan ?: 0,
			'dh_chiet_khau' => $dh_chiet_khau ?: 0,
			'dh_dat_coc'    => $dh_dat_coc ?: 0,
		]);
	}

	public function actionPerpage($perpage)
	{
		MyComponent::setCookies('pageSize', $perpage);
	}

	public function actionView($id)
	{
		if (Yii::$app->request->isAjax) {
			$user     = new User();
			$roleUser = $user->getRoleName(\Yii::$app->user->id);
			$query    = PhongKhamDonHang::find()
				->select([
					"phong_kham_don_hang.*",
					"(SELECT SUM(" . PhongKhamDonHangWOrder::tableName() . ".thanh_tien) FROM " . PhongKhamDonHangWOrder::tableName() . " WHERE " . PhongKhamDonHangWOrder::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id) AS dh_thanh_tien",
					"(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::DAT_COC . "') AS dat_coc",
					"(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::THANH_TOAN . "') AS thanh_toan"
				])
				->leftJoin(PhongKhamDonHangWOrder::tableName(), PhongKhamDonHangWOrder::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
				->where([PhongKhamDonHang::tableName() . '.id' => $id])
				->groupBy(PhongKhamDonHang::tableName() . '.id');
			if ($roleUser == User::USER_DIRECT_SALE) {
				$query->joinWith(['clinicHasOne']);
				$query->andWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
			}
			$model = $query->one();
			if ($model != null) {
				return $this->renderAjax('view', [
					'model' => $model,
				]);
			}
		}
	}

	public function actionCreate()
	{
		//        $model = new PhongKhamDonHang();
		//
		//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
		//            try {
		//                $model->save();
		//                Yii::$app->session->setFlash('alert', [
		//                    'body' => Yii::$app->params['create-success'],
		//                    'class' => 'bg-success',
		//                ]);
		//            } catch (\yii\db\Exception $exception) {
		Yii::$app->session->setFlash('alert', [
			'body'  => Yii::$app->params['create-danger'],
			'class' => 'bg-danger',
		]);

		//            }
		return $this->redirect(['index']);
		//        }

		//        return $this->render('create', [
		//            'model' => $model,
		//        ]);
	}

	public function actionUpdate($id)
	{
		if (Yii::$app->request->isAjax) {
			$model = PhongKhamDonHang::find()->where(['id' => $id])->one();
			if ($model == false) {
				return 'Không tìm thấy dữ liệu khách hàng.';
			};
			$customer  = $this->findClinic($model->customer_id);
			$orderData = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
			//            var_dump($orderData[0]->dich_vu);
			if ($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;

				/*return [
					'status' => 400,
					'msg' => 'abc',
					'model' => $model
				];*/
				$dataOder              = $model->customer_order;
				$model->customer_order = json_encode($model->customer_order);
				$model->chiet_khau     = str_replace('.', '', $model->chiet_khau);
				try {
					$transaction = Yii::$app->db->beginTransaction(
						Transaction::SERIALIZABLE
					);
					if ($model->save()) {
						$donhangTree     = new PhongKhamDonHangTree();
						$arr             = $model->getAttributes();
						$arr['id_order'] = $model->getAttribute('id');
						unset($arr['id']);
						unset($arr['customer_order']);
						unset($arr['thanh_toan']);
						$user_timeline              = new UserTimelineModel();
						$user_timeline->action      = [
							UserTimelineModel::ACTION_CAP_NHAT,
							UserTimelineModel::ACTION_DON_HANG
						];
						$user_timeline->customer_id = $model->customer_id;
						if (!$user_timeline->save()) {
							$transaction->rollBack();
						}
						foreach ($arr as $key => $item) {
							$donhangTree->$key = $item;
						}

						if ($donhangTree->save()) {
							//Thêm order
							$arrID = [];
							$total = 0;
							foreach ($dataOder as $value) {
								$order = PhongKhamDonHangWOrder::find()->where(['id' => $value['id']])->one();
								if ($order === null) {
									$order = new PhongKhamDonHangWOrder();
								}
								$order->customer_id            = $model->customer_id;
								$order->phong_kham_don_hang_id = $model->getPrimaryKey();

								foreach ($value as $keys => $item) {
									if ($keys == 'id' || $keys == 'dich_vu') {
										continue;
									}
									if ($keys == 'san_pham') {
										$sanpham        = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $item])->published()->one();
										$order->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : (string) $sanpham->dichVuHasOne->id;
									}
									if ($keys == 'thanh_tien') {
										$item  = str_replace('.', '', $item);
										$total += $item;
									}
									if ($keys == 'chiet_khau_order') {
										$order->chiet_khau_order = str_replace('.', '', $item);
										continue;
									}
									$order->$keys = $item;
								}

								if (!$order->save()) {
									$transaction->rollBack();
								} else {
									$arrID[] = $order->getPrimaryKey();
								}
							}
							$model->updateAttributes([
								'thanh_tien' => $total
							]);

							$arrIdNotIn = PhongKhamDonHangWOrder::find()->where([
								'not in',
								'id',
								$arrID
							])->andWhere(['in', 'phong_kham_don_hang_id', $model->id])->all();
							foreach ($arrIdNotIn as $key => $val) {
								$orderDel = PhongKhamDonHangWOrder::findOne($val->id);
								if (!$orderDel->delete()) {
									$transaction->rollBack();
								}
							}

							$transaction->commit();

							return [
								'status' => 200,
								'mess'   => Yii::$app->params['update-success'],
							];
						} else {
							$transaction->rollBack();

							return [
								'status' => 403,
								'mess'   => Yii::$app->params['update-danger'],
							];
						}
					} else {
						$transaction->rollBack();

						return [
							'status' => 400,
							'mess'   => Yii::$app->params['update-danger'],
							'error'  => $model->getErrors()
						];
					}
				} catch (\yii\db\Exception $exception) {
					return [
						'status' => 400,
						'mess'   => $exception->getMessage(),
						'error'  => $exception,
					];
				}
			}
			$listKhuyenMai = PhongKhamKhuyenMai::getListKhuyenMai();

			return $this->renderAjax('update', [
				'model'         => $model,
				'customer'      => $customer,
				'orderData'     => $orderData,
				'listKhuyenMai' => $listKhuyenMai
			]);
		}
	}

	public function actionValidateOrder()
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$idGet                      = Yii::$app->request->get('id');
			$model                      = $this->findModel($idGet);
			if ($model === false) {
				if ($model->load(Yii::$app->request->post())) {
					$check                 = $model->customer_order;
					$model->customer_order = json_encode($model->customer_order);
					$model->thanh_tien     = str_replace('.', '', $model->thanh_tien);

					foreach ($check as $key => $item) {
						/*if ($item['dich_vu'] == 0) {
							$model->dich_vu = 0;
							$model->scenario = 'checkOrder';
						} else {
							$model->dich_vu = $item['dich_vu'];
						}*/
						if ($item['san_pham'] == 0) {
							$model->s_p      = 0;
							$model->dich_vu  = null;
							$model->scenario = 'checkOrder';
						} else {
							$sanpham        = PhongKhamSanPham::find()->joinWith(['dichVuHasOne'])->where([PhongKhamSanPham::tableName() . '.id' => $item['san_pham']])->published()->one();
							$model->dich_vu = $sanpham == null || $sanpham->dichVuHasOne == null ? null : $sanpham->dichVuHasOne->id;
							$model->s_p     = $item['san_pham'];
						}
					}

					return \yii\widgets\ActiveForm::validate($model);
				}
			}

			return [];
		}
	}

	/*
	 * Tao moi lich dieu tri
	 */
	public function actionDieuTri()
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_HTML;
			$id                         = Yii::$app->request->get('id');
			$order                      = PhongKhamDonHang::find()->where(['id' => $id])->one();
			if ($order == null) {
				$error = 'Xin hãy tạo đơn hàng trước khi thực hiện hành động này. Xin cảm ơn!';

				return $this->renderAjax('_error', [
					'error' => $error,
				]);
			}

			$hoanCoc = PhongKhamDonHangWThanhToan::find()->where([
				'phong_kham_don_hang_id' => $order->primaryKey,
				'tam_ung'                => ThanhToanModel::HOAN_COC
			])->one();
			if ($hoanCoc != null) {
				return $this->renderAjax('_error', [
					'error' => 'Đơn hàng đã hoàn cọc, bạn không thể chỉnh sửa thanh toán này!'
				]);
			}

			$customer = Clinic::find()->where(['id' => $order->customer_id])->one();
			if ($customer == null) {
				$error = 'Khách hàng không tồn tại!';

				return $this->renderAjax('_error', [
					'error' => $error,
				]);
			}
			//            $model = $this->findDieuTri($ids);
			//            if ($model === false)
			$model    = new PhongKhamLichDieuTri();
			$modelOld = $model->getOldAttributes();
			$room_old = $model->getOldAttribute('room_id');
			if ($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;
				$model->time_dieu_tri       = strtotime($model->time_dieu_tri);
				$model->time_start          = strtotime($model->time_start) != 0 ? strtotime($model->time_start) : null;
				$model->time_end            = strtotime($model->time_end) != 0 ? strtotime($model->time_end) : null;
				$model->danh_gia            = 0;
				$model->co_so               = Yii::$app->user->identity->permission_coso == null ? 1 : Yii::$app->user->identity->permission_coso;
				if ($model->time_end != null) {
					$model->danh_gia = 1;
				}

				$transaction = Yii::$app->db->beginTransaction(
					Transaction::SERIALIZABLE
				);

				if ($model->validate() && $model->save()) {
					$modelNews = $model->getAttributes();
					unset($modelOld['updated_at']);
					unset($modelNews['updated_at']);
					$user_timeline              = new UserTimelineModel();
					$user_timeline->action      = [
						UserTimelineModel::ACTION_THEM,
						UserTimelineModel::ACTION_LICH_DIEU_TRI
					];
					$user_timeline->customer_id = $order->customer_id;
					if (!$user_timeline->save()) {
						$transaction->rollBack();
					}

					$room_new = $model->getAttribute('room_id');
					if ($room_new != $room_old && YII2_ENV_DEV === false) {
						$setting = Setting::find()->where(['key_value' => 'co_lich_dieu_tri_moi'])->one();
						if ($setting != null) {
							if (CONSOLE_HOST != 3/* \Yii::$app->request->getUserIP() == '127.0.0.1'*/) {
								$client = new Client([
									'verify' => Url::to('@backend/modules/clinic/token/cacert.pem')
								]);
							} else {
								$client = new Client();
							}
							if ($model->clinicHasOne->full_name != null) {
								$customer = $model->clinicHasOne->full_name;
							} elseif ($model->clinicHasOne->forename != null) {
								$customer = $model->clinicHasOne->forename;
							} else {
								$customer = $model->clinicHasOne->name;
							}
							$tro_thu = '';
							if ($model->tro_thu != null) {
								foreach ($model->tro_thu as $id_tro_thu) {
									$profile_tro_thu = UserProfile::find()->where(['user_id' => $id_tro_thu])->one();
									if ($profile_tro_thu != null && !in_array($profile_tro_thu->fullname, [
										null,
										''
									])) {
										if ($tro_thu != '') {
											$tro_thu .= ', ';
										}
										$tro_thu .= $profile_tro_thu->fullname;
									}
								}
							}
							$content = str_replace('{$customer}', $customer, $setting->value);
							$content = str_replace('{$room}', $model->roomHasOne->fullname, $content);
							$content = str_replace('{$docter}', $model->ekipInfoHasOne->fullname, $content);
							$content = str_replace('{$loaidieutri}', ($model->listChupHinhHasOne->name), $content);
							$content = str_replace('{$trothu}', $tro_thu, $content);
							$client->request('POST', 'https://api.myauris.vn/api/CreateNoti', [
								'verify'      => false,
								'form_params' => [
									'name'        => $setting->param,
									'content'     => $content,
									'description' => $content,
									'user_id'     => $room_new,
									'customer_id' => $model->clinicHasOne->primaryKey,
									'type'        => 2
								]
							]);
						}
					}
					if ($modelNews != $modelOld) {
						$dieuTriTree        = new PhongKhamLichDieuTriTree();
						$arr                = $model->getAttributes();
						$arr['dieu_tri_id'] = $model->getPrimaryKey();
						unset($arr['id']);

						foreach ($arr as $key => $item) {
							$dieuTriTree->$key = $item;
						}

						if ($dieuTriTree->save()) {
							$transaction->commit();

							return [
								'status' => 1,
								'result' => Yii::$app->params['create-success'],
							];
						} else {
							$transaction->rollBack();

							return [
								'status' => 0,
								'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.', //$model->getErrors(),
							];
						}
					} else {
						$transaction->commit();

						return [
							'status' => 1,
							'result' => Yii::$app->params['create-success'],
						];
					}
				} else {
					$transaction->rollBack();

					return [
						'status' => 0,
						'result' => 'Thất bại, vui lòng liên hệ bộ phận kỹ thuật gấp.', //$model->getErrors(),
					];
				}
			}

			return $this->renderAjax('_dieutri', [
				'customer' => $customer,
				'order'    => $order,
				'model'    => $model,
			]);
		}
	}


	public function actionValidateDieuTri()
	{
		//        $idGet = Yii::$app->request->get('id');
		//        $model = $this->findDieuTri($idGet);
		//        if ($model === false)
		$model = new PhongKhamLichDieuTri();

		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			$model->time_dieu_tri = strtotime($model->time_dieu_tri);
			$model->time_start    = strtotime($model->time_start);
			$model->time_end      = strtotime($model->time_end);
			$model->scenario      = PhongKhamLichDieuTri::SCENARIO_TIMEEND;

			return Json::encode(\yii\widgets\ActiveForm::validate($model));
		}
	}

	public function actionAddPayment($id = null)
	{
		if (Yii::$app->request->isAjax) {
			$order = PhongKhamDonHang::find()->where(['id' => $id])->one();
			if ($order == null) {
				return $this->renderAjax('_error', [
					'error' => 'Chưa tồn tại đơn hàng!'
				]);
			}
			$hoanCoc = PhongKhamDonHangWThanhToan::find()->where([
				'phong_kham_don_hang_id' => $order->primaryKey,
				'tam_ung'                => ThanhToanModel::HOAN_COC
			])->one();
			if ($hoanCoc != null) {
				return $this->renderAjax('_error', [
					'error' => 'Đơn hàng đã hoàn cọc, bạn không thể chỉnh sửa thanh toán này!'
				]);
			}
			$model                         = new PhongKhamDonHangWThanhToan();
			$model->ngay_tao               = date('d-m-Y');
			$customer                      = Clinic::find()->where(['id' => $order->customer_id])->one();
			$model->loai_thanh_toan        = 1;
			$model->phong_kham_don_hang_id = $order->primaryKey;
			$model->customer_id            = $customer->primaryKey;
			$thanhToanType                 = ThanhToanModel::THANHTOAN_TYPE;
			/* Tổng tiền của đơn hàng */
			$totalPrice     = (new Query())->from(PhongKhamDonHangWOrder::tableName())->where(["phong_kham_don_hang_id" => $order->primaryKey])->sum('thanh_tien');
			$datCoc         = PhongKhamDonHangWThanhToan::find()->where([
				'phong_kham_don_hang_id' => $order->primaryKey,
				'tam_ung'                => ThanhToanModel::DAT_COC
			])->one();
			$thanhToan      = PhongKhamDonHangWThanhToan::find()->where([
				'phong_kham_don_hang_id' => $order->primaryKey,
				'tam_ung'                => ThanhToanModel::THANH_TOAN
			])->all();
			$totalThanhToan = 0;
			$listOrder      = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $order->primaryKey])->joinWith([
				'dichVuHasOne',
				'sanPhamHasOne'
			])->all();
			$price          = $totalPrice;
			if ($datCoc != null) {
				unset($thanhToanType[ThanhToanModel::DAT_COC]);
				$price -= $datCoc->tien_thanh_toan;
			}
			if ($order->chiet_khau != null) {
				$price -= $order->chiet_khau;
			}
			if ($thanhToan != null) {
				foreach ($thanhToan as $tt) {
					$price          -= $tt->tien_thanh_toan;
					$totalThanhToan += $tt->tien_thanh_toan;
				}
			}

			return $this->renderAjax('_payment', [
				'model'          => $model,
				'order'          => $order,
				'customer'       => $customer,
				'listOrder'      => $listOrder,
				'totalPrice'     => $totalPrice,
				'totalThanhToan' => $totalThanhToan,
				'datCoc'         => $datCoc,
				'price'          => $price,
				'thanhToanType'  => $thanhToanType,
			]);
		}
	}

	public function actionValidatePayment()
	{
		if (Yii::$app->request->isAjax) {
			$model = new PhongKhamDonHangWThanhToan();
			if ($model->load(Yii::$app->request->post())) {
				Yii::$app->response->format = Response::FORMAT_JSON;

				return ActiveForm::validate($model);
			}
		}
	}

	public function actionSubmitAddPayment()
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			$model                      = new PhongKhamDonHangWThanhToan();
			$code                       = 200;
			$msg                        = Yii::$app->params['create-success'];
			$data                       = null;
			if ($model->load(Yii::$app->request->post())) {
				if ($model->validate()) {
					$model->tien_thanh_toan = str_replace('.', '', $model->tien_thanh_toan);
					if ($model->ngay_tao == null) {
						$model->ngay_tao = time();
					}
					$model->ngay_tao            = strtotime($model->ngay_tao);
					$user_timeline              = new UserTimelineModel();
					$user_timeline->action      = [
						UserTimelineModel::ACTION_TAO,
						UserTimelineModel::ACTION_THANH_TOAN
					];
					$user_timeline->customer_id = $model->customer_id;
					if (!$user_timeline->save()) {
						// $transaction->rollBack();
					}
					if (!$model->save()) {
						$code = 403;
						$msg  = Yii::$app->params['create-danger'];
					}
				} else {
					$code = 400;
					$msg  = 'Lỗi kiểm tra dữ liệu!';
					$data = $model->getErrors();
				}
			} else {
				$code = 400;
				$msg  = 'Lỗi load dữ liệu!';
			}

			return [
				'code'                   => $code,
				'msg'                    => $msg,
				'data'                   => $data,
				'order_id'               => $model->id,
				'phong_kham_don_hang_id' => $model->phong_kham_don_hang_id
			];
		}
	}

	public function actionGetPriceSanPham()
	{
		if (Yii::$app->request->isAjax) {
			$id                         = Yii::$app->request->post('id');
			$sl                         = Yii::$app->request->post('sl');
			$sanpham                    = new PhongKhamSanPham();
			$data                       = $sanpham->getSanPhamOne($id);
			$result                     = $data->don_gia;
			$data                       = $sl * $result;
			Yii::$app->response->format = Response::FORMAT_JSON;

			return [
				'status' => true,
				'result' => number_format($data, 0, ',', '.'),
			];
		}
	}

	public function actionPrintOrder($id)
	{
		$model   = $this->findModelOrder($id);
		$order   = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
		$payment = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

		$this->layout = '@backend/views/layouts/print-template';

		return $this->render('_print_order_temp', [
			'model'     => $model,
			'order'     => $order,
			'payment'   => $payment,
			'array_pop' => array_pop($payment) //lấy phần tử cuối cùng của mảng
		]);
	}

	public function actionValidatePrintOrder()
	{
		if (Yii::$app->request->isAjax) {
			$id                         = Yii::$app->request->get('id');
			$model                      = $this->findModelOrder($id);
			Yii::$app->response->format = Response::FORMAT_JSON;
			if ($model != null) {
				$order = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
				if ($order == null) {
					return [
						'status' => 400,
						'mess'   => 'Không tìm thấy dữ liệu đơn hàng.'
					];
				}
				$payment = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
				if ($payment == null) {
					return [
						'status' => 400,
						'mess'   => 'Không tìm thấy dữ liệu thanh toán.'
					];
				}
			} else {
				return [
					'status' => 400,
					'mess'   => 'Không tìm thấy dữ liệu khách hàng.'
				];
			}
		}
	}

	public function actionPrintPayment($id)
	{
		$model   = $this->findModelOrder($id);
		$payment = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();

		$this->layout = '@backend/views/layouts/print-template';

		return $this->render('_print_list_payment_temp', [
			'model'   => $model,
			'payment' => $payment,
		]);
	}

	public function actionValidatePrintPayment()
	{
		if (Yii::$app->request->isAjax) {
			$id                         = Yii::$app->request->get('id');
			$model                      = $this->findModelOrder($id);
			Yii::$app->response->format = Response::FORMAT_JSON;
			if ($model != null) {
				$payment = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
				if ($payment == null) {
					return [
						'status' => 400,
						'mess'   => 'Không tìm thấy dữ liệu thanh toán.'
					];
				}
			} else {
				return [
					'status' => 400,
					'mess'   => 'Không tìm thấy dữ liệu khách hàng.'
				];
			}
		}
	}

	public function actionDelete()
	{
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
			$id                         = Yii::$app->request->post('id');
			if ($id == null) {
				$id = Yii::$app->request->get('id');
			}
			$donhang = $this->findModelOrder($id);
			if ($donhang == false) {
				return [
					'status' => 'failure'
				];
			}

			$transaction = Yii::$app->db->beginTransaction(
				Transaction::SERIALIZABLE
			);

			$arr = ['tree', 'w_order', 'w_thanhtoan', 'lich_dieu_tri', 'lich_dieu_tri_tree'];

			$donhang_tree               = PhongKhamDonHangTree::find()->where(['id_order' => $id])->all();
			$donhang_w_order            = PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $id])->all();
			$donhang_w_thanhtoan        = PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $id])->all();
			$donhang_lich_dieu_tri      = PhongKhamLichDieuTri::find()->where([PhongKhamDonHang::tableName() . '.id' => $id])->joinWith(['orderHasOne'])->all();
			$donhang_lich_dieu_tri_tree = PhongKhamLichDieuTriTree::find()->where([PhongKhamDonHang::tableName() . '.id' => $id])->joinWith(['orderHasOne'])->all();

			try {
				foreach ($arr as $v) {
					$var = 'donhang_' . $v;
					if (is_array($$var)) {
						foreach ($$var as $row) {
							if (!$row->delete()) {
								$transaction->rollBack();

								return [
									'status' => 'failure'
								];
							}
						}
					}
				}
				if (!$donhang->delete()) {
					$transaction->rollBack();

					return [
						"status" => "failure"
					];
				}
				$transaction->commit();

				return [
					"status" => "success"
				];
			} catch (\yii\db\Exception $e) {
				var_dump($e);
				$transaction->rollBack();

				return [
					"status" => "exception"
				];
			}
		}

		return $this->redirect(['index']);
	}

	public function actionShowHide()
	{
		if (Yii::$app->request->isAjax) {
			$id = Yii::$app->request->post('id');

			$model = $this->findModel($id);
			try {
				if ($model->status == 1) {
					$model->status = 0;
				} else {
					$model->status = 1;
				}
				if ($model->save()) {
					echo 1;
				}
			} catch (\yii\db\Exception $exception) {
				echo 0;
			}
		}
	}

	public function actionDeleteMultiple()
	{
		try {
			$action         = Yii::$app->request->post('action');
			$selectCheckbox = Yii::$app->request->post('selection');
			if ($action === 'c') {
				if ($selectCheckbox) {
					foreach ($selectCheckbox as $id) {
						$this->findModel($id)->delete();
					}
					\Yii::$app->session->setFlash('indexFlash', 'Bạn đã xóa thành công.');
				}
			}
		} catch (\yii\db\Exception $e) {
			if ($e->errorInfo[1] == 1451) {
				throw new \yii\web\HttpException(400, 'Failed to delete the object.');
			} else {
				throw $e;
			}
		}

		return $this->redirect(['index']);
	}

	protected function findModel($id)
	{
		$user     = new User();
		$roleUser = $user->getRoleName(\Yii::$app->user->id);
		$model    = PhongKhamDonHang::find()->where(['customer_id' => $id]);

		if ($roleUser == User::USER_DIRECT_SALE) {
			$model->joinWith(['clinicHasOne']);
			$model->andWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
		}

		$order = $model->one();
		if ($order !== null) {
			return $order;
		}

		return false;
	}

	protected function findModelOrder($id)
	{
		return PhongKhamDonHang::find()
			->select([
				"phong_kham_don_hang.*",
				"(SELECT SUM(" . PhongKhamDonHangWOrder::tableName() . ".thanh_tien) FROM " . PhongKhamDonHangWOrder::tableName() . " WHERE " . PhongKhamDonHangWOrder::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id) AS dh_thanh_tien",
				"(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::DAT_COC . "') AS dat_coc",
				"(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::THANH_TOAN . "') AS thanh_toan"
			])
			->leftJoin(PhongKhamDonHangWOrder::tableName(), PhongKhamDonHangWOrder::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
			->where([PhongKhamDonHang::tableName() . '.id' => $id])
			->groupBy(PhongKhamDonHang::tableName() . '.id')->one();
	}

	protected function findClinic($id)
	{
		$model = Clinic::findOne($id);
		if (($model !== null)) {
			return $model;
		}

		return false;
	}

	public function actionCheckCoso()
	{
		if (Yii::$app->request->isAjax) {

			$dataOption        = Yii::$app->request->post('dataOption');
			$id                = Yii::$app->request->post('id');
			$mPhongKhamDonHang = PhongKhamDonHang::findOne($id);
			//            return Json::encode($mPhongKhamDonHang->attributes);
			$mPhongKhamDonHang->co_so = $dataOption;
			$transaction              = Yii::$app->db->beginTransaction(Transaction::SERIALIZABLE);
			if ($mPhongKhamDonHang && $mPhongKhamDonHang->save()) {
				$transaction->commit();
				$status = '200';

				return Json::encode(['status' => $status, 'data' => 'success']);
			} else {
				$transaction->rollBack();
				$status = '400';

				return Json::encode(['status' => $status, 'data' => 'fail']);
			}
		}
	}

	public function actionCheckCouponCode()
	{

		Yii::$app->response->format = Response::FORMAT_JSON;
		if (!empty(Yii::$app->request->get('id'))) {
			$res = CouponUsedHistoryModel::getCodeCoupon(Yii::$app->request->get('id'));
		}

		return $res;
	}

	/**
	 * Tao code bao hành
	 * PHAM THANH NGHIA
	 */
	public function actionCreateCodeBaoHanh($don_hang_id = null, $worder_id = null)
	{
		if(Yii::$app->request->isPost){
			$model = new CheckcodeBaoHanh();
			if($model->load(Yii::$app->request->post())){
				//@NGHIA FIX TIEP
			}
			Yii::$app->response->format = Response::FORMAT_JSON;
			return [
				'status' => 1,
				'result' => Yii::$app->params['update-success'],
			];
		}
		if (Yii::$app->request->isAjax) {
			
			$user     = new User();
			$roleUser = $user->getRoleName(\Yii::$app->user->id);
			$mDonHang = PhongKhamDonHang::findOne($don_hang_id);
			$mWOrder = PhongKhamDonHangWOrder::findOne($worder_id);
			$queryCheckcode = CheckcodeBaoHanh::find()->where(['phong_kham_don_hang_w_order_id' => $worder_id]);
			if ($queryCheckcode->exists()) {
				$model = $queryCheckcode->one();
			} else {
				$model = new CheckcodeBaoHanh();
				$model->customer_id = $mDonHang->customer_id;
				$model->co_so = $mDonHang->co_so;
				$model->phong_kham_don_hang_id = $mDonHang->id;
				$model->phong_kham_don_hang_w_order_id = $worder_id;
				$model->product_id = $mWOrder->san_pham;
				$model->product_name = ($mWOrder->sanPhamHasOne !== null) ? $mWOrder->sanPhamHasOne->name : "";
				$model->co_so = $mDonHang->co_so;
				$model->co_so_name = ($mDonHang->coSoHasOne !== null) ? $mDonHang->coSoHasOne->name : "";
				$model->date_buy = $mDonHang->created_at;
				if ($mDonHang->clinicHasOne == null) {
					$model->customer_name = "";
				} else {
					$customer_name = $mDonHang->clinicHasOne->full_name == null ? $mDonHang->clinicHasOne->forename : $mDonHang->clinicHasOne->full_name;
					$model->customer_name = $customer_name;
				}
				$model->save(false);

				$count = CheckcodeBaoHanh::find()->where(['customer_id' => $mDonHang->customer_id])->count();
				$model->warranty_code =  $this->convertIntegerToString($mDonHang->co_so, 3) . ' ' . $this->convertIntegerToString($mDonHang->customer_id, 6) . ' ' . $this->convertIntegerToString($count, 3);
				$model->update(false);
			}
			if ($model != null) {
				return $this->renderAjax('_create_code_bao_hanh', [
					'model' => $model,
				]);
			}
		}
	}

	public function convertIntegerToString($value, $number)
	{
		$result = 10 ** $number + $value;
		return substr($result . '', 1, $number + 1);
	}
}
