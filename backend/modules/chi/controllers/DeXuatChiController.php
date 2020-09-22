<?php

namespace backend\modules\chi\controllers;

use app\backend\models\PhongKhamThongBao;
use backend\helpers\BackendHelpers;
use backend\modules\chi\models\Comment;
use backend\modules\chi\models\Deadline;
use backend\modules\chi\models\DeXuatChiModel;
use backend\modules\chi\models\form\FormHoSo;
use backend\modules\chi\models\HoSo;
use backend\modules\chi\models\ThuchiTieuChi;
use backend\modules\general\models\Dep365Notification;
use backend\modules\user\models\User;
use backend\modules\user\models\UserSubRole;
use common\helpers\MyHelper;
use common\models\UserProfile;
use GuzzleHttp\Client;
use Yii;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\search\DeXuatChiSearch;
use backend\components\MyController;
use yii\db\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use backend\components\MyComponent;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * DeXuatChiController implements the CRUD actions for DeXuatChi model.
 */
class DeXuatChiController extends MyController {
	public $params = [];


	public function actionIndex() {
		$searchModel  = new DeXuatChiSearch();
		$dataProvider = $searchModel->search( Yii::$app->request->queryParams );

		if ( MyComponent::hasCookies( 'pageSize' ) ) {
			$dataProvider->pagination->pageSize = MyComponent::getCookies( 'pageSize' );
		} else {
			$dataProvider->pagination->pageSize = 10;
		}

		$pageSize = $dataProvider->pagination->pageSize;

		$totalCount = $dataProvider->totalCount;

		$totalPage = ( ( $totalCount + $pageSize - 1 ) / $pageSize );

		return $this->render( 'index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
			'totalPage'    => $totalPage,
		] );
	}

	public function renderTieuChiView( array $arr, $style = 'style_1' ) {
		if ( $style == 'style_2' ) {
			return $this->renderPartial( 'view/tieuchi_template/tieuchi_style_2', [ 'data' => $arr ] );
		}

		return $this->renderPartial( 'view/tieuchi_template/tieuchi', [ 'data' => $arr ] );
	}

	public function actionPerpage( $perpage ) {
		MyComponent::setCookies( 'pageSize', $perpage );
	}

	public function actionView( $id ) {
		$model = $this->findModel( $id );
		if ( $model ) {
			$formComment = new Comment();
			$listComment = Comment::getListCommentByDeXuatChi( $id );
			$listHoSo    = HoSo::find()->where( [ 'id_de_xuat_chi' => $model->primaryKey ] )->published()->all();

			return $this->render( 'view', [
				'model'       => $model,
				'formComment' => $formComment,
				'listComment' => $listComment,
				'listHoSo'    => $listHoSo
			] );
		}

		return $this->redirect( [ 'index' ] );
	}

	public function actionCreate() {
		$model    = new DeXuatChi();
		$userInfo = User::getUserInfo( Yii::$app->user->id );
		if ( in_array( $userInfo->item_name, [
				User::USER_MANAGER_KE_TOAN,
				User::USER_KE_TOAN
			] ) &&
		     $userInfo->subroleHasOne != null &&
		     $userInfo->subroleHasOne->role == UserSubRole::ROLE_KE_TOAN
		) {
			$model->scenario = DeXuatChi::SCENARIO_KE_TOAN;
		}
		$modelDeadline = new Deadline();
		$formHoSo      = new FormHoSo();
		$tieuchi       = new ThuchiTieuChi();
		$listHoSo      = [];
		$listDeadline  = null;
		$formComment   = null;
		$listComment   = [];

		return $this->render( 'create', [
			'model'         => $model,
			'modelDeadline' => $modelDeadline,
			'formHoSo'      => $formHoSo,
			'modelTieuchi'  => $tieuchi,
			'listHoSo'      => $listHoSo,
			'listDeadline'  => $listDeadline,
			'formComment'   => $formComment,
			'listComment'   => $listComment
		] );
	}

	/**
	 * Updates an existing DeXuatChi model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate( $id ) {
		//TODO: Tạo scenarios khi status đề xuất !=0 thì không được update
		$model = $this->findModel( $id );
		if ( $model->status == 6 || $model->status == 4 ) {
			Yii::$app->session->setFlash( 'alert', [
				'body'  => 'Không thể update khi đề xuất đã ' . DeXuatChi::STATUS[ $model->status ],
				'class' => 'bg-danger',
			] );

			return $this->redirect( [ 'index' ] );
		}
		$userInfo = User::getUserInfo( Yii::$app->user->id );
		if ( in_array( $userInfo->item_name, [
				User::USER_MANAGER_KE_TOAN,
				User::USER_KE_TOAN
			] ) &&
		     $userInfo->subroleHasOne != null &&
		     $userInfo->subroleHasOne->role == UserSubRole::ROLE_KE_TOAN
		) {
			$model->scenario = DeXuatChi::SCENARIO_KE_TOAN;
		}
		$modelDeadline = Deadline::find()->andFilterWhere( [ 'id_tieu_chi' => $id ] )->one();
		if ( $modelDeadline == null ) {
			$modelDeadline = new Deadline();
		} else {
			$model->setAttributes( [
				'thoi_gian_bat_dau'  => $modelDeadline->thoi_gian_bat_dau,
				'thoi_gian_ket_thuc' => $modelDeadline->thoi_gian_ket_thuc,
			] );
		}
		$formHoSo = new FormHoSo();
		$listHoSo = HoSo::find()->where( [ 'id_de_xuat_chi' => $model->primaryKey ] )->published()->all();

		$formComment = new Comment();
		$listComment = Comment::getListCommentByDeXuatChi( $id );

		return $this->render( 'update', [
			'model'         => $model,
			'modelDeadline' => $modelDeadline,
			'formHoSo'      => $formHoSo,
			'listHoSo'      => $listHoSo,
			'formComment'   => $formComment,
			'listComment'   => $listComment
		] );
	}

	public function actionValidateDeXuatChi( $id = null ) {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
			$model                      = new DeXuatChi();
			$formHoSo                   = new FormHoSo();
			if ( $id != null ) {
				$model = DeXuatChi::find()->where( [ 'id' => $id ] )->one();
			}
			$userInfo = User::getUserInfo( Yii::$app->user->id );
			if ( in_array( $userInfo->item_name, [
					User::USER_MANAGER_KE_TOAN,
					User::USER_KE_TOAN
				] ) &&
			     $userInfo->subroleHasOne != null &&
			     $userInfo->subroleHasOne->role == UserSubRole::ROLE_KE_TOAN
			) {
				$model->scenario = DeXuatChi::SCENARIO_KE_TOAN;
			}
			if ( $model->load( Yii::$app->request->post() ) &&
			     $formHoSo->load( Yii::$app->request->post() ) ) {
				$validateModel     = ActiveForm::validate( $model );
				$validateModelHoSo = ActiveForm::validate( $formHoSo );

				return array_merge( $validateModel, $validateModelHoSo );
			}
		}
	}

	public function actionSubmitDeXuatChi( $id = null ) {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
			$model                      = new DeXuatChi();
			$formHoSo                   = new FormHoSo();

			$post = Yii::$app->request->post();

			if ( $id != null ) {
				$model = DeXuatChi::find()->where( [ 'id' => $id ] )->one();
			}
			$userInfo = User::getUserInfo( Yii::$app->user->id );

			if ( in_array( $userInfo->item_name, [
					User::USER_MANAGER_KE_TOAN,
					User::USER_KE_TOAN
				] ) &&
			     $userInfo->subroleHasOne != null &&
			     $userInfo->subroleHasOne->role == UserSubRole::ROLE_KE_TOAN
			) {
				$model->scenario = DeXuatChi::SCENARIO_KE_TOAN;
			}


			if ( ! $model->load( $post ) ||
			     ! $formHoSo->load( $post ) ||
			     ! $model->validate() ||
			     ! $formHoSo->validate() ) {
				$errors     = $model->getErrors( 'tieu_chi_group' );
				$text_error = '';

				if ( ! empty( $errors ) ) {
					foreach ( $errors[0] as $key => $value ) {
						$text_error .= $key . $value . '<br>';
					}
				}

				return [
					'code'  => 400,
					'msg'   => 'Không kiểm tra được dữ liệu gửi lên<br>' . $text_error,
					'error' => array_merge( $model->getErrors(), $formHoSo->getErrors() ),
				];
			}
			$transaction = Yii::$app->db->beginTransaction( Transaction::SERIALIZABLE );
			if ( ! $model->save() ) {
				$transaction->rollBack();

				return [
					'code'  => 400,
					'msg'   => 'Lưu đề xuất thất bại',
					'error' => $model->getErrors()
				];
			}
			$this->tp_status_notify( $model );

			$post['id_de_xuat_chi'] = $model->id;
			$this->thuchi_tieuchi( $post );

			$formHoSo->saveFiles( Yii::getAlias( '@backend/web' ) . '/uploads/ho-so/' );
			if ( count( $formHoSo->fileUploadFail ) > 0 ) {
				$transaction->rollBack();
				$formHoSo->deleteFiles();

				return [
					'code' => 400,
					'msg'  => 'Lưu hồ sơ thất bại',
				];
			}
			if ( isset( $post['image_base64'] ) && ! empty( $post['image_base64'] ) ) {
				$formHoSo->image_base64 = $post['image_base64'];
				$formHoSo->savePasteImage( Yii::getAlias( '@backend/web' ) . '/uploads/ho-so/' );
			}
			if ( count( $formHoSo->fileUploadSuccess ) > 0 ) {
				foreach ( $formHoSo->fileUploadSuccess as $file ) {
					$modelHoSo = new HoSo();
//                    $fileHoSo = $this->createImage('@backend/web', Yii::$app->basePath . '/web/uploads/tmp/' . $file, null, null, '/uploads/ho-so/', null);
					$modelHoSo->setAttributes( [
						'id_de_xuat_chi' => $model->primaryKey,
						'file'           => $file
					] );
					$modelHoSo->save();
				}
				$formHoSo->deleteFiles();
			}
			$url_redirect = [];
			if ( $id == null ) {
				/* TẠO MỚI ĐỀ XUẤT CHI => TẠO NOTIFICATION CHO TRƯỞNG PHÒNG */
				$notif = Dep365Notification::quickCreate( [
					'name'        => 'Có đề xuất chi mới cần bạn duyệt',
					'icon'        => 'ft-alert-circle',
					'description' => 'Nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa tạo đề xuất chi!' . Html::a( 'đề xuất ' . $model->title,
							Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
					'is_new'      => 1,
					'is_bg'       => 3,
					'status'      => 1,
					'for_who'     => 'user-' . $model->chosen_one
				] );
				try {
					\Yii::$app->commandBus->handle( new \common\commands\SendEmailCommand( [
						'subject' => '[Đề xuất chi] Có đề xuất chi mới',
						'view'    => 'dexuatchi/noti_new_dexuat',
						'to'      => $model->chosenHasOne->email,
						'cc'      => [ 'dev.thang@myauris.vn' ],
						'params'  => [
							'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
						]
					] ) );
				} catch ( \Exception $exception ) {
					Yii::error( 'Không thể gửi mail' );
				}
				if ( $notif !== false ) {
					try {
						$client = new Client( [ 'verify' => false ] );
						$client->request( 'POST', SOCKET_URL, [
							'form_params' => [
								'handle' => 'dep365-notification',
								'data'   => json_encode( [
									'key'  => 'notification-' . User::USER_MANAGER,
									'data' => [
										'urlView'     => Url::toRoute( [
											'/general/notification/view',
											'id' => $notif
										] ),
										'icon'        => 'ft-alert-circle',
										'bg'          => 'bg-red',
										'tit'         => 'red',
										'name'        => 'Có đề xuất chi mới',
										'description' => 'Nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa tạo đề xuất chi!',
										'created_at'  => MyHelper::TimeBefore( time() )
									]
								] )
							]
						] );
					} catch ( \Exception $e ) {

					}
				}

				//Báo cho người triển khai
				if ( ! empty( $model->nguoi_trien_khai ) && $model->nguoi_trien_khai != $model->created_by ) {
					$notif = Dep365Notification::quickCreate( [
						'name'        => 'Có đề xuất chi được giao cho bạn',
						'icon'        => 'ft-alert-circle',
						'description' => 'Nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa tạo ' . Html::a( 'Đề xuất ' . $model->id,
								Url::to( [
									'/chi/de-xuat-chi/view',
									'id' => $model->id
								] ) ) . ' chi!' . Html::a( 'đề xuất ' . $model->id,
								Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
						'is_new'      => 1,
						'is_bg'       => 3,
						'status'      => 1,
						'for_who'     => 'user-' . $model->nguoi_trien_khai
					] );
					if ( $notif !== false ) {
						try {
							$client = new Client( [ 'verify' => false ] );
							$client->request( 'POST', SOCKET_URL, [
								'form_params' => [
									'handle' => 'dep365-notification',
									'data'   => json_encode( [
										'key'  => 'notification-user-' . $model->nguoi_trien_khai,
										'data' => [
											'urlView'     => Url::toRoute( [
												'/general/notification/view',
												'id' => $notif
											] ),
											'icon'        => 'ft-alert-circle',
											'bg'          => 'bg-red',
											'tit'         => 'red',
											'name'        => 'Có đề xuất chi được giao cho bạn',
											'description' => 'Nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa tạo ' . Html::a( 'Đề xuất ' . $model->id,
													Url::to( [
														'/chi/de-xuat-chi/view',
														'id' => $model->id
													] ) ) . ' chi!',
											'created_at'  => MyHelper::TimeBefore( time() )
										]
									] )
								]
							] );
						} catch ( \Exception $e ) {

						}
					}
				}
				$url_redirect = [ 'url_redirect' => Url::to( [ 'index' ] ) ];
			} else {
				/* UPDATE ĐỀ XUẤT CHI */
			}
			$transaction->commit();

			return array_merge( [
				'code' => 200,
				'msg'  => 'Lưu đề xuất thành công',
				'data' => $model->getAttributes(),
			], $url_redirect );
		}
	}

	/**
	 * Print De Xuat Chi
	 */
	public function actionPrintDexuat() {
		$get = Yii::$app->request->get();

		if ( isset( $get['id'] ) ) {
			$model                         = $this->findModel( $get['id'] );
			$this->layout                  = '@backend/views/layouts/print-template';
			$this->view->params['noprint'] = 'ok';

			return $this->render( 'view/_print_dexuat_temp', [
				'model' => $model,
			] );
		} else {
			Yii::error( ( 'No id' ) );
		}

	}

	/**
	 * Print Phieu thu
	 */
	public function actionPrintPhieuthu() {
		$get          = Yii::$app->request->get();
		$this->layout = '@backend/views/layouts/print-template';

		return $this->render( 'view/_print_phieuthu_temp', [
//                'model' => $model,
		] );

	}


	public function thuchi_tieuchi( array $tieuchiData = [], $action = '' ) {
		if ( ! empty( $tieuchiData['DeXuatChi']['tieu_chi_group'] ) ) {

			foreach ( $tieuchiData['DeXuatChi']['tieu_chi_group'] as $value ) {
				if ( empty( $value['id'] ) ) {
					$tieuchi = new ThuchiTieuChi();
				} else {
					$tieuchi = ThuchiTieuChi::findOne( $value['id'] );
				}
				switch ( $action ) {
					case 'delete':
						return $res = $this->deleteTieuchi( $tieuchi );
						break;
					default:
						$tieuchi->scenario       = ThuchiTieuChi::SCENARIO_SAVE;
						$value['nd_hoan_thanh']  = isset( $value['nd_hoan_thanh'] ) ? $value['nd_hoan_thanh'] : '';
						$value['id_de_xuat_chi'] = ! empty( $tieuchiData['id_de_xuat_chi'] ) ? $tieuchiData['id_de_xuat_chi'] : $value['id_de_xuat_chi'];
						$tieuchi->setAttributes( $value );
						if ( $tieuchi->save() ) {
							$value['id'] = $tieuchi->id;
//                            $this->thuchi_deadline($value);
						} else {
							return [
								'code'  => 400,
								'msg'   => 'Không thể lưu tiêu chí',
								'error' => json_encode( $tieuchi->getErrors() )
							];
						}
						break;
				}
			}
		} else {
			return [ 'code' => 502, 'msg' => 'Không chạy hàm thuchi_tieu_chi' ];
		}
	}

	//Todo:: Tạo backup thuchi tieu chi

	public function tp_status_notify( DeXuatChi $model ) {

		if ( ! empty( $model ) && isset( $model->tp_status ) ) {

			switch ( $model->tp_status ) {
				case DeXuatChiModel::TP_DUYET:
					$users_tp = UserProfile::find()->where( [ 'user_id' => $model->leader_accept ] );
					$users_tp = $users_tp->one();
					$notif    = Dep365Notification::quickCreate( [
						'name'        => Html::a( 'Đề xuất đã hoàn thành' . $model->id,
							Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
						'icon'        => 'ft-alert-circle',
						'description' => Html::a( 'Trưởng phòng ' . $users_tp->fullname . ' đã duyệt hoàn thành đề xuất: Đề xuất ' . $model->id,
							Url::to( [ '/chi/de-xuat-chi/update', 'id' => $model->id ] ) ),
						'is_new'      => 1,
						'is_bg'       => 3,
						'status'      => 1,
						'for_who'     => UserSubRole::ROLE_KE_TOAN
					] );
					/*try {
						\Yii::$app->commandBus->handle(new \common\commands\SendEmailCommand([
							'subject' => '[Đề xuất chi] Đề xuất được trưởng phòng nghiệm thu',
							'view' => 'dexuatchi/noti_deadline',
							'to' => $model->accountant_accept->email,
							'cc' => ['dev.thang@myauris.vn'],
							'params' => [
								'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
							]
						]));
					} catch (\Exception $exception) {
						throw(new \Exception('Không thể gửi mail'));
					}*/
					break;
				case DeXuatChiModel::TP_HUY:
					$users_tp = UserProfile::find()->where( [ 'user_id' => $model->leader_accept ] );
					$users_tp = $users_tp->one();
					$notif    = Dep365Notification::quickCreate( [
						'name'        => Html::a( 'Đề xuất đã bị trưởng phòng hủy' . $model->id,
								Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ) . '',
						'icon'        => 'ft-alert-circle',
						'description' => 'Trưởng phòng ' . $users_tp->fullname . ' đã hủy đề xuất: ' . Html::a( 'Đề xuất ' . $model->id,
								Url::to( [ '/chi/de-xuat-chi/update', 'id' => $model->id ] ) ),
						'is_new'      => 1,
						'is_bg'       => 3,
						'status'      => 1,
						'for_who'     => UserSubRole::ROLE_KE_TOAN
					] );
					/*try {
						\Yii::$app->commandBus->handle(new \common\commands\SendEmailCommand([
							'subject' => '[Đề xuất chi] Đề xuất được trưởng phòng nghiệm thu',
							'view' => 'dexuatchi/noti_deadline',
							'to' => $model->accountant_accept->email,
							'cc' => ['dev.thang@myauris.vn'],
							'params' => [
								'dexuat' => $model,
//                                'tieuchi' => $tieuchi,
							]
						]));
					} catch (\Exception $exception) {
						throw(new \Exception('Không thể gửi mail'));
					}*/
					break;
				default:
					return;
					break;
			}
		}
	}

	public function thuchi_deadline( $tieuchi, $action = '' ) {
		$tieuchi_default = [
			'id'                 => '',
			'thoi_gian_bat_dau'  => '',
			'thoi_gian_ket_thuc' => '',
			'id_tieu_chi'        => '',
			'created_at'         => '',
			'created_by'         => ''
		];
		$tieuchi         = array_merge( $tieuchi_default, $tieuchi );

		$deadline        = Deadline::getOneTimeDeadline( $tieuchi['id'] );
		$strtotime_start = strtotime( $tieuchi['thoi_gian_bat_dau'] );
		$strtotime_end   = strtotime( $tieuchi['thoi_gian_ket_thuc'] );


		if ( empty( $deadline ) ) {
			$deadline = new Deadline();
		} else {
			if ( ( $strtotime_start != $deadline->oldAttributes['thoi_gian_bat_dau'] || $strtotime_end != $deadline->oldAttributes['thoi_gian_ket_thuc'] ) ) {
				$deadline = new Deadline();
			}
		}
		$deadline->thoi_gian_bat_dau  = $tieuchi['thoi_gian_bat_dau'];
		$deadline->thoi_gian_ket_thuc = $tieuchi['thoi_gian_ket_thuc'];

		$deadline->id_tieu_chi = $tieuchi['id'];
		$deadline->created_at  = time();
		$deadline->created_by  = Yii::$app->user->identity->getId();

		switch ( $action ) {
			case 'delete':
				if ( Deadline::deleteAll( [ 'id_tieu_chi' => $tieuchi['id'] ] ) ) {
					return [ 'code' => 200, 'msg' => 'Delete ok' ];
				} else {
					return [ 'code' => 400, 'msg' => 'Lỗi delete deadline' ];
				}
				break;
			default:
				if ( $deadline->save() ) {
					return [
						'code'  => 200,
						'msg'   => 'Save ok',
						'error' => json_encode( $deadline->getErrors() )
					];
				} else {
					return [
						'code'  => 400,
						'msg'   => 'Không thể xóa thời gian',
						'error' => json_encode( $deadline->getErrors() )
					];
				}
				break;
		}
	}

	public function deleteTieuChi( ThuchiTieuChi $tieuchi ) {
		if ( ( $tieuchi->status == 1 ) && ( ! UserSubRole::is_current_user_is_ketoan() ) ) {
			return [
				'code'  => 400,
				'msg'   => 'Không có quyền xóa tiêu chí',
				'error' => json_encode( $tieuchi->getErrors() )
			];
		} else {
			if ( $tieuchi->delete() ) {
				return [
					'code' => 200,
					'msg'  => 'Delete ok',
				];
			} else {
				return [
					'code'  => 400,
					'msg'   => 'Không thể xóa tiêu chí',
					'error' => json_encode( $tieuchi->getErrors() )
				];
			}
		}
	}

	public function actionDeleteTieuChi() {
		Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
		$res                        = $this->thuchi_tieuchi( Yii::$app->request->post(), 'delete' );

		return $res;
	}

	public function actionDelete() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
			$id                         = Yii::$app->request->post( 'id' );
			try {
				if ( $this->findModel( $id )->delete() ) {
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

		return $this->redirect( [ 'index' ] );
	}

	//tạm bỏ không dùng tới. thay thế bằng hàm actionLeaderAccept
	public function actionShowHide() {
		if ( Yii::$app->request->isAjax ) {
			$id = Yii::$app->request->post( 'id' );

			$model      = $this->findModel( $id );
			$notif      = false;
			$data_notif = null;
			try {
				$transaction = Yii::$app->db->beginTransaction( Transaction::SERIALIZABLE );
				$arr_update  = [];
				$user        = new User();
				$userInfo    = $user->getCoso( Yii::$app->user->id );
				switch ( $model->oldAttributes['status'] ) {
					case 0:
						if ( UserSubRole::is_current_user_is_truongphong() ) {
							$inspectioner = ! empty( Yii::$app->request->post( 'inspectioner' ) ) ? Yii::$app->request->post( 'inspectioner' ) : Yii::$app->user->id;
							$arr_update   = [
								'status'           => DeXuatChi::STATUS_TRUONG_PHONG_DUYET,
								'inspectioner'     => $inspectioner,
								'leader_accept'    => Yii::$app->user->id,
								'leader_accept_at' => time()
							];
							/* TRƯỞNG PHÒNG DUYỆT ĐỀ XUẤT CHI => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT VÀ KẾ TOÁN */
							/* NOTIFICATION CHO NGƯỜI ĐỀ XUẤT*/
							$notif_user = Dep365Notification::quickCreate( [
								'name'        => 'Đề xuất đã được duyệt',
								'icon'        => 'ft-alert-circle',
								'description' => 'Trưởng phòng ' . $userInfo->fullname . ' vừa duyệt đề xuất chi của bạn' . Html::a( 'đề xuất ' . $model->id,
										Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
								'is_new'      => 1,
								'is_bg'       => 1,
								'status'      => 1,
								'for_who'     => 'user-' . $model->created_by
							] );
							$data_array = [];
							if ( $notif_user !== false ) {
								$data_array[ 'notification-user-' . $model->created_by ] = [
									'urlView'     => Url::toRoute( [
										'/general/notification/view',
										'id' => $notif_user
									] ),
									'icon'        => 'ft-alert-circle',
									'bg'          => 'bg-teal',
									'tit'         => '',
									'name'        => 'Đề xuất đã được duyệt',
									'description' => 'Trưởng phòng ' . $userInfo->fullname . ' vừa duyệt đề xuất chi của bạn',
									'created_at'  => MyHelper::TimeBefore( time() )
								];
							}
							$notif_ketoan = Dep365Notification::quickCreate( [
								'name'        => 'Có đề xuất chi mới' . Html::a( $model->title,
										Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
								'icon'        => 'ft-alert-circle',
								'description' => 'Đề xuất chi của nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa được trưởng phòng ' . $userInfo->fullname . ' duyệt',
								'is_new'      => 1,
								'is_bg'       => 1,
								'status'      => 1,
								'for_who'     => UserSubRole::ROLE_KE_TOAN
							] );
							if ( $notif_ketoan !== false ) {
								$data_array[ 'notification-' . User::USER_KE_TOAN ] = [
									'urlView'     => Url::toRoute( [
										'/general/notification/view',
										'id' => $notif_ketoan
									] ),
									'icon'        => 'ft-alert-circle',
									'bg'          => 'bg-teal',
									'tit'         => '',
									'name'        => 'Có đề xuất chi mới',
									'description' => 'Đề xuất chi của nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa được trưởng phòng ' . $userInfo->fullname . ' duyệt',
									'created_at'  => MyHelper::TimeBefore( time() )
								];
							}
							if ( is_array( $data_array ) && count( $data_array ) > 0 ) {
								$data_notif         = [
									'handle'   => 'dep365-notification',
									'multiple' => 'multiple'
								];
								$data_notif['data'] = json_encode( $data_array );
								$notif              = $notif_user && $notif_ketoan;
							}
						}
						break;
					case 1:
						if ( UserSubRole::is_current_user_is_truongphong() ) {
//                            $model->status = 0;
//                            $model->leader_accept = null;
							$arr_update = [
								'status'           => DeXuatChi::STATUS_DANG_DOI_DUYET,
								'inspectioner'     => null,
								'leader_accept'    => null,
								'leader_accept_at' => null
							];
							/* TRƯỞNG PHÒNG UNDO DUYỆT ĐỀ XUẤT CHI => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
						} elseif ( UserSubRole::is_current_user_is_ketoan() ) {
							$arr_update = [
								'status'               => DeXuatChi::STATUS_KE_TOAN_DUYET,
								'accountant_accept'    => Yii::$app->user->id,
								'accountant_accept_at' => time()
							];
							/* KẾ TOÁN DUYỆT ĐỀ XUẤT => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
							$notifId = Dep365Notification::quickCreate( [
								'name'        => 'Đề xuất đã được kế toán duyệt',
								'icon'        => 'ft-check',
								'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' duyệt',
								'is_new'      => 1,
								'is_bg'       => 1,
								'status'      => 1,
								'for_who'     => 'user-' . $model->created_by
							] );
							if ( $notifId !== false ) {
								$notif      = true;
								$data_notif = [
									'handle' => 'dep365-notification',
									'data'   => json_encode( [
										'key'  => 'notification-user-' . $model->created_by,
										'data' => [
											'urlView'     => Url::toRoute( [
												'/general/notification/view',
												'id' => $notifId
											] ),
											'icon'        => 'ft-check',
											'bg'          => 'bg-teal',
											'tit'         => '',
											'name'        => 'Đề xuất đã được kế toán duyệt',
											'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' duyệt',
											'created_at'  => MyHelper::TimeBefore( time() )
										]
									] )
								];
							}
						}
						break;
					case 3:
						if ( UserSubRole::is_current_user_is_ketoan() ) {
							$arr_update = [
								'status'               => DeXuatChi::STATUS_TRUONG_PHONG_DUYET,
								'accountant_accept'    => null,
								'accountant_accept_at' => null
							];
							/* KẾ TOÁN HUỶ ĐỀ XUẤT => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
							$notifId = Dep365Notification::quickCreate( [
								'name'        => 'Đề xuất chi đã bị huỷ',
								'icon'        => 'ft-alert-circle',
								'description' => 'Đề xuất chi của bạn đã bị kế toán ' . $userInfo->fullname . ' huỷ',
								'is_new'      => 1,
								'is_bg'       => 3,
								'status'      => 1,
								'for_who'     => 'user-' . $model->created_by
							] );
							if ( $notifId !== false ) {
								$notif      = true;
								$data_notif = [
									'handle' => 'dep365-notification',
									'data'   => json_encode( [
										'key'  => 'notification-user-' . $model->created_by,
										'data' => [
											'urlView'     => Url::toRoute( [
												'/general/notification/view',
												'id' => $notifId
											] ),
											'icon'        => 'ft-alert-circle',
											'bg'          => 'bg-red',
											'tit'         => 'red',
											'name'        => 'Đề xuất chi đã bị huỷ',
											'description' => 'Đề xuất chi của bạn đã bị kế toán ' . $userInfo->fullname . ' huỷ',
											'created_at'  => MyHelper::TimeBefore( time() )
										]
									] )
								];
							}
						}
						break;
				}
//                if ($model->save()) {
				$model->updateAttributes( $arr_update );
				$transaction->commit();
				if ( $notif !== false ) {
					try {
						$client = new Client( [ 'verify' => false ] );
						$client->request( 'POST', SOCKET_URL, [
							'form_params' => $data_notif
						] );
					} catch ( \Exception $e ) {

					}
				}
				echo 1;
			} catch ( \yii\db\Exception $exception ) {
				echo 0;
			}
		}
	}

	public function actionLeaderAccept() {
		if ( Yii::$app->request->isAjax ) {
			$id = Yii::$app->request->post( 'id' );

			$model      = $this->findModel( $id );
			$notif      = false;
			$data_notif = null;
			try {
				$transaction = Yii::$app->db->beginTransaction( Transaction::SERIALIZABLE );
				$arr_update  = [];
				$user        = new User();
				$userInfo    = $user->getCoso( Yii::$app->user->id );
				if ( UserSubRole::is_current_user_is_truongphong() ) {
					$arr_update = $this->updateStatusTruongPhong( $model, $userInfo );
				}
				if ( UserSubRole::is_current_user_is_ketoan() ) {
					$arr_update = $this->updateStatusKeToan( $model, $userInfo );
				}

//                if ($model->save()) {
				$model->updateAttributes( $arr_update );
				$transaction->commit();
				if ( $notif !== false ) {
					try {
						$client = new Client( [ 'verify' => false ] );
						$client->request( 'POST', SOCKET_URL, [
							'form_params' => $data_notif
						] );
					} catch ( \Exception $e ) {

					}
				}
				echo 1;
			} catch ( \yii\db\Exception $exception ) {
				echo 0;
			}
		}
	}


	public function updateStatusTruongPhong( $model, $userInfo ) {
		$arr_update = [];
		switch ( Yii::$app->request->post( 'check' ) ) {
			case 'check':
				$inspectioner = ! empty( Yii::$app->request->post( 'inspectioner' ) ) ? Yii::$app->request->post( 'inspectioner' ) : Yii::$app->user->id;
				$arr_update   = [
					'status'           => DeXuatChi::STATUS_TRUONG_PHONG_DUYET,
					'inspectioner'     => $inspectioner,
					'leader_accept'    => Yii::$app->user->id,
					'leader_accept_at' => time()
				];
				/* TRƯỞNG PHÒNG DUYỆT ĐỀ XUẤT CHI => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT VÀ KẾ TOÁN */
				/* NOTIFICATION CHO NGƯỜI ĐỀ XUẤT*/
				$notif_user = Dep365Notification::quickCreate( [
					'name'        => 'Đề xuất đã được duyệt',
					'icon'        => 'ft-alert-circle',
					'description' => 'Trưởng phòng ' . $userInfo->fullname . ' vừa duyệt đề xuất chi của bạn' . Html::a( 'đề xuất ' . $model->id,
							Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
					'is_new'      => 1,
					'is_bg'       => 1,
					'status'      => 1,
					'for_who'     => 'user-' . $model->created_by
				] );
				$data_array = [];
				if ( $notif_user !== false ) {
					$data_array[ 'notification-user-' . $model->created_by ] = [
						'urlView'     => Url::toRoute( [
							'/general/notification/view',
							'id' => $notif_user
						] ),
						'icon'        => 'ft-alert-circle',
						'bg'          => 'bg-teal',
						'tit'         => '',
						'name'        => 'Đề xuất đã được duyệt',
						'description' => 'Trưởng phòng ' . $userInfo->fullname . ' vừa duyệt đề xuất chi của bạn',
						'created_at'  => MyHelper::TimeBefore( time() )
					];
				}
				$notif_ketoan = Dep365Notification::quickCreate( [
					'name'        => 'Có đề xuất chi mới' . Html::a( $model->title,
							Url::to( [ '/chi/de-xuat-chi/view', 'id' => $model->id ] ) ),
					'icon'        => 'ft-alert-circle',
					'description' => 'Đề xuất chi của nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa được trưởng phòng ' . $userInfo->fullname . ' duyệt',
					'is_new'      => 1,
					'is_bg'       => 1,
					'status'      => 1,
					'for_who'     => UserSubRole::ROLE_KE_TOAN
				] );
				if ( $notif_ketoan !== false ) {
					$data_array[ 'notification-' . User::USER_KE_TOAN ] = [
						'urlView'     => Url::toRoute( [
							'/general/notification/view',
							'id' => $notif_ketoan
						] ),
						'icon'        => 'ft-alert-circle',
						'bg'          => 'bg-teal',
						'tit'         => '',
						'name'        => 'Có đề xuất chi mới',
						'description' => 'Đề xuất chi của nhân viên ' . ( $model->nguoidexuatHasOne != null && $model->nguoidexuatHasOne->fullname != null ? $model->nguoidexuatHasOne->fullname : '' ) . ' vừa được trưởng phòng ' . $userInfo->fullname . ' duyệt',
						'created_at'  => MyHelper::TimeBefore( time() )
					];
				}
				if ( is_array( $data_array ) && count( $data_array ) > 0 ) {
					$data_notif         = [
						'handle'   => 'dep365-notification',
						'multiple' => 'multiple'
					];
					$data_notif['data'] = json_encode( $data_array );
					$notif              = $notif_user && $notif_ketoan;
				}
				break;
			case 'uncheck':
				if ( UserSubRole::is_current_user_is_truongphong() ) {
//                            $model->status = 0;
//                            $model->leader_accept = null;
					$arr_update = [
						'status'           => DeXuatChi::STATUS_DANG_DOI_DUYET,
						'inspectioner'     => null,
						'leader_accept'    => null,
						'leader_accept_at' => null
					];
					/* TRƯỞNG PHÒNG UNDO DUYỆT ĐỀ XUẤT CHI => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
				}
				break;
		}

		return $arr_update;
	}

	public function updateStatusKeToan( $model, $userInfo ) {
		$arr_update = [];
		switch ( $model->oldAttributes['status'] ) {
			case 1:
				$arr_update = [
					'status'               => DeXuatChi::STATUS_KE_TOAN_DUYET,
					'accountant_accept'    => Yii::$app->user->id,
					'accountant_accept_at' => time()
				];
				/* KẾ TOÁN DUYỆT ĐỀ XUẤT => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
				$notifId = Dep365Notification::quickCreate( [
					'name'        => 'Đề xuất đã được kế toán duyệt',
					'icon'        => 'ft-check',
					'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' duyệt',
					'is_new'      => 1,
					'is_bg'       => 1,
					'status'      => 1,
					'for_who'     => 'user-' . $model->created_by
				] );
				if ( $notifId !== false ) {
					$notif      = true;
					$data_notif = [
						'handle' => 'dep365-notification',
						'data'   => json_encode( [
							'key'  => 'notification-user-' . $model->created_by,
							'data' => [
								'urlView'     => Url::toRoute( [
									'/general/notification/view',
									'id' => $notifId
								] ),
								'icon'        => 'ft-check',
								'bg'          => 'bg-teal',
								'tit'         => '',
								'name'        => 'Đề xuất đã được kế toán duyệt',
								'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' duyệt',
								'created_at'  => MyHelper::TimeBefore( time() )
							]
						] )
					];
				}
				break;
			case 3:
				$arr_update = [
					'status'               => DeXuatChi::STATUS_TRUONG_PHONG_DUYET,
					'accountant_accept'    => null,
					'accountant_accept_at' => null
				];
				/* KẾ TOÁN HUỶ ĐỀ XUẤT => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
				$notifId = Dep365Notification::quickCreate( [
					'name'        => 'Đề xuất chi đã bị huỷ',
					'icon'        => 'ft-alert-circle',
					'description' => 'Đề xuất chi của bạn đã bị kế toán ' . $userInfo->fullname . ' huỷ',
					'is_new'      => 1,
					'is_bg'       => 3,
					'status'      => 1,
					'for_who'     => 'user-' . $model->created_by
				] );
				if ( $notifId !== false ) {
					$notif      = true;
					$data_notif = [
						'handle' => 'dep365-notification',
						'data'   => json_encode( [
							'key'  => 'notification-user-' . $model->created_by,
							'data' => [
								'urlView'     => Url::toRoute( [
									'/general/notification/view',
									'id' => $notifId
								] ),
								'icon'        => 'ft-alert-circle',
								'bg'          => 'bg-red',
								'tit'         => 'red',
								'name'        => 'Đề xuất chi đã bị huỷ',
								'description' => 'Đề xuất chi của bạn đã bị kế toán ' . $userInfo->fullname . ' huỷ',
								'created_at'  => MyHelper::TimeBefore( time() )
							]
						] )
					];
				}
				break;
		}

		return $arr_update;

	}


	public function actionStatusChange() {
		if ( Yii::$app->request->isAjax ) {
			$id         = Yii::$app->request->post( 'id' );
			$model      = $this->findModel( $id );
			$notif      = false;
			$data_notif = null;
			try {
				$transaction = Yii::$app->db->beginTransaction( Transaction::SERIALIZABLE );
				$arr_update  = [];
				$user        = new User();
				$userInfo    = $user->getCoso( Yii::$app->user->id );
				switch ( Yii::$app->request->post( 'status' ) ) {
					case 'success':
						$arr_update = [
							'status'     => DeXuatChi::STATUS_HOAN_THANH,
							'updated_by' => Yii::$app->user->id,
							'updated_at' => time()
						];
						/* ĐỀ XUẤT CHI HOÀN THÀNH => TẠO NOTIFICATION CHO NGƯỜI ĐỀ XUẤT */
						$notifId = Dep365Notification::quickCreate( [
							'name'        => 'Đề xuất chi đã hoàn thành',
							'icon'        => 'ft-check',
							'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' cập nhật trạng thái hoàn thành',
							'is_new'      => 1,
							'is_bg'       => 1,
							'status'      => 1,
							'for_who'     => 'user-' . $model->created_by
						] );
						try {
							if ( $notifId !== false ) {
								$notif      = true;
								$data_notif = [
									'handle' => 'dep365-notification',
									'data'   => json_encode( [
										'key'  => 'notification-user-' . $model->created_by,
										'data' => [
											'urlView'     => Url::toRoute( [
												'/general/notification/view',
												'id' => $notifId
											] ),
											'icon'        => 'ft-check',
											'bg'          => 'bg-teal',
											'tit'         => '',
											'name'        => 'Đề xuất chi đã hoàn thành',
											'description' => 'Đề xuất chi của bạn đã được kế toán ' . $userInfo->fullname . ' cập nhật trạng thái hoàn thành',
											'created_at'  => MyHelper::TimeBefore( time() )
										]
									] )
								];
							}
						} catch ( \Exception $e ) {

						}
						break;
					case 'deny':
						$arr_update = [
							'status'     => DeXuatChi::STATUS_HUY_DE_XUAT,
							'updated_by' => Yii::$app->user->id,
							'updated_at' => time()
						];
						/*HUỶ ĐỀ XUẤT */
						$notifId = Dep365Notification::quickCreate( [
							'name'        => 'Đề xuất chi đã bị hủy',
							'icon'        => 'ft-check',
							'description' => 'Đề xuất chi của bạn đã bị ' . $userInfo->fullname . ' hủy',
							'is_new'      => 1,
							'is_bg'       => 1,
							'status'      => 1,
							'for_who'     => 'user-' . $model->created_by
						] );
						try {
							if ( $notifId !== false ) {
								$notif      = true;
								$data_notif = [
									'handle' => 'dep365-notification',
									'data'   => json_encode( [
										'key'  => 'notification-user-' . $model->created_by,
										'data' => [
											'urlView'     => Url::toRoute( [
												'/general/notification/view',
												'id' => $notifId
											] ),
											'icon'        => 'ft-check',
											'bg'          => 'bg-teal',
											'tit'         => '',
											'name'        => 'Đề xuất chi đã hủy',
											'description' => 'Đề xuất chi của bạn đã bị ' . $userInfo->fullname . ' hủy',
											'created_at'  => MyHelper::TimeBefore( time() )
										]
									] )
								];
							}
						} catch ( \Exception $e ) {

						}
						break;
				}
				$model->updateAttributes( $arr_update );
				$transaction->commit();
				if ( $notif !== false ) {
					$client = new Client( [ 'verify' => false ] );
					$client->request( 'POST', SOCKET_URL, [
						'form_params' => $data_notif
					] );
				}
				echo 1;
			} catch ( \yii\db\Exception $exception ) {
				echo 0;
			}
		}
	}

	public function actionDeleteMultiple() {
		try {
			$action         = Yii::$app->request->post( 'action' );
			$selectCheckbox = Yii::$app->request->post( 'selection' );
			if ( $action === 'c' ) {
				if ( $selectCheckbox ) {
					foreach ( $selectCheckbox as $id ) {
						$this->findModel( $id )->delete();
					}
					\Yii::$app->session->setFlash( 'indexFlash', 'Bạn đã xóa thành công.' );
				}
			}
		} catch ( \yii\db\Exception $e ) {
			if ( $e->errorInfo[1] == 1451 ) {
				throw new \yii\web\HttpException( 400, 'Failed to delete the object.' );
			} else {
				throw $e;
			}
		}

		return $this->redirect( [ 'index' ] );
	}

	/**
	 * Tạm bỏ không dùng.
	 */
	public function actionListUserIdProfile() {

		$selected = '';
		$res      = [];
		$users    = User::getListUserIdProfile();
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			if ( ! empty( $users ) ) {
				$users = ArrayHelper::map( $users, 'id', 'fullname' );
				if ( isset( $_GET['nguoi_trien_khai'] ) ) {
					$selected = $_GET['nguoi_trien_khai'];
				}
				$arr_tmp = [];
				foreach ( $users as $id => $user ) {
					$arr_tmp[] = [ 'id' => $id, 'text' => $user, 'selected' => $id == $selected ];
				}
				$res['results'] = ( $arr_tmp );
			}

			return $res;
		}

		return '';
	}

	protected function findModel( $id ) {
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


	public function actionUpdateKhoanChi() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$res         = [ 'code' => 200, 'message' => 'Đã cập nhật khoản chi' ];
			$dexuat_id   = Yii::$app->request->get( 'dexuat_id' );
			$khoanchi_id = Yii::$app->request->get( 'khoanchi_id' );
			if ( ! empty( $dexuat_id ) ) {
				$dexuat = DeXuatChi::findOne( [ 'id' => $dexuat_id ] );
				$dexuat->setScenario( DeXuatChi::SCENARIO_KE_TOAN );
				$dexuat->setAttribute( 'khoan_chi', $khoanchi_id );

				if ( $dexuat->validate() ) {
					$dexuat->save();
				} else {
					$res['code']    = 302;
					$res['message'] = 'Khoản chi không hợp lệ';
				}
			}

			return $res;
		}
	}

	public function actionUpdateInpsectioner() {
		if ( Yii::$app->request->isAjax ) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$res             = [ 'code' => 200, 'message' => 'Đã cập nhật người nghiệm thu' ];
			$dexuat_id       = Yii::$app->request->get( 'dexuat_id' );
			$inspectioner_id = Yii::$app->request->get( 'inspectioner_id' );
			if ( ! empty( $dexuat_id ) ) {
				$dexuat = DeXuatChi::findOne( [ 'id' => $dexuat_id ] );
//				$dexuat->setScenario( DeXuatChi::SCENARIO_TRUONG_PHONG );
				$dexuat->setAttribute( 'inspectioner', $inspectioner_id );

				if ( $dexuat->validate() ) {
					$dexuat->save();
				} else {
					$res['code']    = 302;
					$res['message'] = 'Người nghiệm thu không hợp lệ';
				}
			}

			return $res;
		}
	}

	public function actionGetInspectionerList() {

		if ( \Yii::$app->request->isAjax ) {
			\Yii::$app->response->format = Response::FORMAT_JSON;

			$res      = [];
			$temp_arr = ( User::getListUserIdProfile() );
			$res      = array_map( function ( $arr ) {
				if ( count( $arr->phongbanHasMany ) > 0 ) {
					$name = $arr->phongbanHasMany[0]->name;
					$name = BackendHelpers::acronysm_string( $name );
					$name = '[' . $name . '] ';
				} else {
					$name = null;
				}

				return [ 'id' => $arr->id, 'text' => $name . $arr->fullname ];
			}, $temp_arr );
			$get      = Yii::$app->request->get();
			if ( isset( $get['q'] ) ) {
				$res = array_filter( $res, function ( $value ) use ( $get ) {
					return preg_match( '#' . $get['q'] . '#u', $value['text'] );
//					$get['q'] == $value;
				} );
			}
			//q=Đạ
			/**
			 * 28: {id: 161, text: "Đạt Vũ"}
			 * 124: {id: 330, text: "Nguyễn Tấn Đạt"}
			 * Select2 data do not update if number order not begin from 1;
			 * Use sort to rearrange array
			 */
			sort( $res );

			/*if ( ! empty( $temp_arr ) ) {
				foreach ( $temp_arr as $id => $val ) {
					$res[] = [ 'id' => $id, 'text' => $val, 'bo_phan' => '' ];
				}
			}*/

			return $res;
		}
		die();


	}

	public static function getCoSo() {
		$coso = \backend\modules\setting\models\Dep365CoSo::getCoSoArray();
		array_unshift( $coso, "Head Office" );

		return $coso;
	}
}
