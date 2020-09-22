<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 3/20/2020
 * Time: 1:59 PM
 */

namespace console\controllers;

use backend\modules\chi\models\Deadline;
use backend\modules\chi\models\ThuchiTieuChi;
use backend\modules\general\models\Dep365Notification;
use backend\modules\user\models\UserSubRole;
use common\commands\SendEmailCommand;
use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\modules\user\models\User;

class ThuChiCronjobController extends \yii\console\Controller {

	/*
	 *
	 * select d.id,d.id_tieu_chi, d.thoi_gian_ket_thuc  from thuchi_deadline as d join
(select id_tieu_chi, MAX(thoi_gian_ket_thuc) from thuchi_deadline group by id_tieu_chi ) as t on t.id_tieu_chi = d.id_tieu_chi group by d.id_tieu_chi, d.id,d.thoi_gian_ket_thuc
	lấy thời gian kết thúc lớn nhất. distinct id_tieu_chi
	*/


	/*
	 * select * from thuchi_deadline where thoi_gian_ket_thuc between UNIX_TIMESTAMP(NOW()) and UNIX_TIMESTAMP(date_add(CURDATE(), interval 24*60*60 - 1 second))
	 * Lấy thời gian từ lúc gọi tới cuối ngày.
	 * select DISTINCT c.id, c.title from thuchi_deadline d, thuchi_tieu_chi t JOIN thuchi_de_xuat_chi c on c.id = t.id_de_xuat_chi where c.tp_status=0 and c.status in (0,1,3) and d.thoi_gian_ket_thuc between UNIX_TIMESTAMP(DATE_ADD(CURDATE() , INTERVAL -60 DAY)) and UNIX_TIMESTAMP(date_add(CURDATE(), interval 24*60*60 - 1 second))
	 * */
	//TODO:: Sửa cronjob thêm status vào deadline. Check status của dexuat. Đề xuất nào chưa hoàn thành mới lấy deadline ra.
	public function actionSendMailDeadline() {
		$leader_accept = ( new \yii\db\Query() )->select( 'inspectioner as id' )->distinct()->from( 'thuchi_de_xuat_chi' )->where( 'inspectioner is not null' )->all();
		if ( ! empty( $leader_accept ) ) {
			foreach ( $leader_accept as $value ) {
				$models = ( new \yii\db\Query() )
					->select( 'c.id' )->distinct()->from( 'thuchi_deadline d, thuchi_tieu_chi t' )
					->innerJoin( 'thuchi_de_xuat_chi c', 'c.id = t.id_de_xuat_chi' )
					->where( 'd.thoi_gian_ket_thuc between UNIX_TIMESTAMP(DATE_ADD(CURDATE() , INTERVAL -600 DAY)) and UNIX_TIMESTAMP(date_add(CURDATE(), interval 24*60*60 - 1 second))' )
					->andWhere( [ 'c.tp_status' => 0 ] )->andWhere( [ 'c.inspectioner' => $value['id'] ] )
					->andWhere( [ 'in', 'c.status', [ 0, 1, 3 ] ] );
				$models = $models->all();
				if ( ! empty( $models ) ) {
					$user = User::getUserInfo( $value['id'] );
					if ( CONSOLE_HOST == 1 ) {
						echo $this->renderPartial( 'noti_deadline',
							[
								'user'   => $user,
								'models' => $models
							] );
					} else {
						try {
							\Yii::$app->commandBus->handle( new \common\commands\SendEmailCommand( [
								'subject' => '[Đề xuất chi] Có tiêu chí tới hạn deadline',
								'view'    => 'dexuatchi/noti_deadline',
								'to'      => $user->email,
								'cc'      => [ 'dev.thang@myauris.vn' ],
								'params'  => [
									'user'   => $user,
									'models' => $models,
								]
							] ) );
						} catch ( \Exception $exception ) {
//							throw( new \Exception( 'Không thể gửi mail' ) );
						}
					}
				}
			}
		}
	}

	public function renderTieuChiView( array $arr, $style = 'style_1' ) {
		if ( $style == 'style_1' ) {
			return $this->renderPartial( 'view/tieuchi_template/tieuchi', [ 'data' => $arr ] );
		} elseif ( $style == 'style_2' ) {
			return $this->renderPartial( 'view/tieuchi_template/tieuchi_style_2', [ 'data' => $arr ] );
		}
	}
}
