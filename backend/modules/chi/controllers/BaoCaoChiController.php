<?php

namespace backend\modules\chi\controllers;

use backend\components\MyController;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use PhpParser\Node\Expr\Cast\Object_;
use yii\db\Query;
use yii\web\Response;

class BaoCaoChiController extends MyController {
	private $data_subfilter;

	public function getGroupColorBarChart( $i = 0 ) {
		$light = 0.5;
		$bold  = 1;
		$color = [
			'rgba(0,255,255,%1$s)',
			'rgba(255,0,255,%1$s)',
			'rgba(192,192,192,%1$s)',
			'rgba(128,128,128,%1$s)',
			'rgba(128,0,0,%1$s)',
			'rgba(128,128,0,%1$s)',
			'rgba(0,128,0,%1$s)',
			'rgba(128,0,128,%1$s)',
			'rgba(0,128,128,%1$s)',
			'rgba(0,0,128,%1$s)',
		];
		$i1    = count( $color ) - 1;
		if ( $i > $i1 ) {
			$i = rand( 0, $i1 );
		}
		$arr_color = [
			'backgroundColor' => sprintf( $color[ $i ], $light ),
			'borderColor'     => sprintf( $color[ $i ], $bold ),
		];

		return $arr_color;
	}

	public function actionIndex() {
		return $this->render( 'index' );
	}

	public function actionGetdata() {
		\Yii::$app->response->format = Response::FORMAT_JSON;

		$start_date_report = isset( $_GET['startDateReport'] ) ?
			( $_GET['startDateReport'] ) :
			date( 'd/m/Y', time() );
		$end_date_report   = isset( $_GET['endDateReport'] ) ?
			( $_GET['endDateReport'] ) :
			date( 'd/m/Y', time() );
		$compare_kind      = isset( $_GET['compare_kind'] ) ? $_GET['compare_kind'] : 0;


		$result = $this->getData( $start_date_report, $end_date_report, $compare_kind );

		return $result;
	}

	public function getData( $start_date_report, $end_date_report, $compare_kind ) {
		// debug tool
//		$start_date_report = "01/5/2020";
//		$end_date_report   = "27/5/2020";

		$data      = [];
		$startDate = $this->formatTimestampToDateTimeObject( $start_date_report );
		$endDate   = $this->formatTimestampToDateTimeObject( $end_date_report );
		//prevent date modify modify all refenrence variable
		$dateRangeTemp = [
			$this->formatTimestampToDateTimeObject( $start_date_report ),
			$this->formatTimestampToDateTimeObject( $end_date_report ),
		];
		$dateRange     = [ $startDate, $endDate ];
		$data['debug'] = $dateRange;

		$data['dataCompareKey']          = $compare_kind;
		$data['dataDate']                = $this->getListDateRange( $startDate, $endDate );
		$data['dataSet']['chi']          = [];
		$data['dataSet']['chi']['first'] = [];


		//--------------------- data doanh thu first
		$data['dataSet']['chi']['first'] = $this->getDataSet(
			$data['dataSet']['chi']['first'],
			$dateRange,
			$this->buidQuery( $dateRange )
		);
//		echo '<pre>';
//		print_r( $data );
//		echo '</pre>';
//		die;
		//----------------------------end
		//---------------------------------- data doanhthu second for compare
		if ( $compare_kind != 0 ) {
			$data['dataSet']['chi']['second'] = [];
			$this->modifyRangeDate( $dateRangeTemp, $compare_kind );
			$data['dataSet']['chi']['second'] = $this->getDataSet(
				$data['dataSet']['chi']['second'],
				$dateRangeTemp,
				$this->buidQuery( $dateRangeTemp )
			);
		}
		//--------------------------------end

		// ----------- query tong tien, thanh toan, no

//        $sql = 'select p.name, sum(ph.thanh_tien-ph.chiet_khau) as thanh_tien, sum(tt.tien_thanh_toan) as tien_thuc_thu from dep365_customer_online d, province p, phong_kham_don_hang ph ,phong_kham_don_hang_w_thanh_toan tt where tt.phong_kham_don_hang_id = ph.id and d.province=p.id and ph.customer_id=d.id and ph.thanh_tien > 0 and tt.tam_ung in (0,1) and tt.ngay_tao between ' . $dateRange[0]->getTimestamp() . ' and ' . $dateRange[1]->getTimestamp() . ' group by p.name order by thanh_tien DESC';
		/*$query = ( new Query() )->select( 'p.name, sum(ph.thanh_tien-ph.chiet_khau) as thanh_tien,
        sum(tt.tien_thanh_toan) as tien_thuc_thu' )
		                        ->from( 'dep365_customer_online d, 
            province p, phong_kham_don_hang ph ,
            phong_kham_don_hang_w_thanh_toan tt' )
		                        ->where( 'tt.phong_kham_don_hang_id = ph.id and d.province = p.id and ph.customer_id = d.id' )
		                        ->andWhere( [ '>', 'ph.thanh_tien', 0 ] )
		                        ->andWhere( [ 'in', 'tt.tam_ung', [ 0, 1 ] ] )
		                        ->andWhere( [
			                        'between',
			                        'tt.ngay_tao',
			                        $dateRange[0]->getTimestamp(),
			                        $dateRange[1]->getTimestamp()
		                        ] )
		                        ->andFilterWhere( [ 'd.co_so' => $this->subfilter_coso ] )
		->
		groupBy( 'p.name' )->orderBy( 'thanh_tien DESC' );*/
//        $query = \Yii::$app->db->createCommand($sql);
		/*$data['debug_table'] = $query->createCommand()->rawSql;
		$query               = $query->all();*/


		//---------------------------------data pie
		/*$i        = 0;
		$sum_khac = 0;
		foreach ( $query as $value ) {
			if ( $i < 4 ) {
				$data['dataPie']['name'][]          = $value['name'];
				$data['dataPie']['tien_thuc_thu'][] = $value['tien_thuc_thu'];
				$i ++;
			} else {
				$sum_khac += $value['tien_thuc_thu'];
			}
		}
		$data['dataPie']['name'][]          = 'Khác';
		$data['dataPie']['tien_thuc_thu'][] = $sum_khac;

		$data['table_html'] = $this->renderPartial( 'table_overview', [
			'query'     => $query,
			'dateRange' => $dateRange,
			'co_so'     => isset( $this->subfilter_coso ) ? $this->subfilter_coso : ''
		] );
*/
		//-----------------end
		$data['get'] = \Yii::$app->request->get();

		return $data;
	}


	/**
	 * Build Query
	 * return $query
	 */
	public function buidQuery( $dateRange ) {
		$query = ( new \yii\db\Query() )->select( [
			'nguoi_trien_khai',
			'title',
			'so_tien_chi',
			'khoan_chi',
			'status',
			'round(UNIX_TIMESTAMP(FROM_UNIXTIME(created_at, \'%Y-%m-%d\')),0) as created_at'
		] )->from( 'thuchi_de_xuat_chi' )
		                                ->where( [
			                                'between',
			                                'created_at',
			                                $dateRange[0]->getTimestamp(),
			                                $dateRange[1]->getTimestamp()
		                                ] );

		if ( isset( $_GET['data_sub_filter'] ) ) {
			$this->data_subfilter = $data_sub_filter = \Yii::$app->request->get( 'data_sub_filter' );
			$data_sub_filter      = \Yii::$app->request->get( 'data_sub_filter' );
			foreach ( $data_sub_filter as $sub_filter ) {
				if ( $sub_filter['value'] != '' ) {
					$filter_key = $sub_filter['name'];
					$subfilter  = $sub_filter['value'];
					switch ( $filter_key ) {
						case 'fanpage':
							$query = $query->andFilterWhere( [ 'd.face_fanpage' => $subfilter ] );
							break;
						case 'coso':
							//Todo::Temp gain subfilter coso for table query; Need Fix.
							$this->subfilter_coso = $subfilter;
							$query                = $query->andFilterWhere( [ 'd.co_so' => $subfilter ] );
							break;
						case 'sanpham':
//                  select o.ngay_tao, sum(o.thanh_tien - o.chiet_khau_order)
//                  as thanh_tien from phong_kham_don_hang_w_order o,
//                  phong_kham_san_pham sp where o.san_pham = sp.id and sp.id=2 group by o.ngay_tao
							$query->join(
								'LEFT JOIN',
								PhongKhamDonHangWOrder::tableName() . ' o',
								'p.phong_kham_don_hang_id=o.phong_kham_don_hang_id'
							)
							      ->andWhere( 'o.san_pham=' . $subfilter );
							break;
						case 'dichvu':
//                  select o.ngay_tao, sum(o.thanh_tien - o.chiet_khau_order)
//                  as thanh_tien from phong_kham_don_hang_w_order o,
//                  phong_kham_san_pham sp where o.san_pham = sp.id and sp.id=2 group by o.ngay_tao
							$query->join(
								'LEFT JOIN',
								PhongKhamDonHangWOrder::tableName() . ' o',
								'p.phong_kham_don_hang_id=o.phong_kham_don_hang_id'
							)
							      ->andWhere( 'o.dich_vu=' . $subfilter );
							break;
						case 'direct_sale':
							$query = $query->andFilterWhere( [ 'd.directsale' => $subfilter ] );

							break;
						case 'online_sale':
							$query = $query->andFilterWhere( [ 'd.permission_user' => $subfilter ] );
							break;
					}
				}
			}
		}


		return $query;
	}

	public function actionGetsubfilter() {
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$results                     = [];
		if ( isset( $_GET['subfilter'] ) ) {
			$key = $_GET['subfilter'];
			switch ( $key ) {
				case 'fanpage':
					$list = Dep365CustomerOnlineFanpage::find()->asArray()->all();
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['name'] ];
					}
					break;
				case 'coso':
					$list = Dep365CoSo::find()->asArray()->all();
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['name'] ];
					}
					break;
				case 'sanpham':
					$list = ( new \yii\db\Query() )
						->select( [ 'id', 'name' ] )
						->from( PhongKhamSanPham::tableName() )
						->all();
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['name'] ];
					}
					break;
				case 'dichvu':
					$list = ( new \yii\db\Query() )
						->select( [ 'id', 'name' ] )
						->from( PhongKhamDichVu::tableName() )
						->all();
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['name'] ];
					}
					break;
				case 'direct_sale':
					$list = User::getNhanVienTuDirectSale();
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['fullname'] ];
					}
					break;
				case 'online_sale':
					$list = User::getNhanVienTuVanOnline( [ User::STATUS_ACTIVE ] );
					foreach ( $list as $item ) {
						$results[] = [ 'id' => $item['id'], 'text' => $item['fullname'] ];
					}
					break;
			}
		}

		$array_subfilter = [ 'results' => $results ];

		return $array_subfilter;
	}


	public function getDataSet( $data, $dateList, Query $query ) {
		$dateList = $this->getListDateRange( $dateList[0], $dateList[1] );
		if ( ! empty( $query->select ) ) {
			$data['sql']   = $query->createCommand()->getRawSql();
			$query         = $query->all();
			$data['value'] = [];
			$data['color'] = [];
			//        $query = \Yii::$app->db->createCommand('select ngay_tao, sum(thanh_tien - chiet_khau) as thanh_tien from ' . PhongKhamDonHang::tableName() . ' where ngay_tao between ' . $startDate->getTimestamp() . ' and ' . $endDate->getTimestamp() . ' group by ngay_tao')->queryAll();
//			echo '<pre>';
//			print_r( $dateList );
//			echo '</pre>';
			//Use date merge with list DateRange
			foreach ( $query as $i => $val ) {
//				$data['value'][ $val['created_at'] ][] = $val['title'];
				/*$data['value'][ $val['created_at'] ][] = array_merge( [
					'title'       => $val['title'],
					'so_tien_chi' => $val['so_tien_chi']
				], $this->getGroupColorBarChart( $i ) );*/
				$data['value'][ $val['created_at'] ][] = [
					'title'       => $val['title'],
					'so_tien_chi' => $val['so_tien_chi']
				];
				$data['color'][]                       = $this->getGroupColorBarChart( $i );
			}
			foreach ( $dateList as $value ) {
				if ( ! isset( $data['value'][ $value ] ) ) {

					$data['value'][ $value ] = '';
				}
			}
//			echo '<pre>';
//			print_r( $data['value'] );
//			echo '</pre>';
//			die;
		}

		return $data;
	}

	public function getListDateRange( $start_date_report, $end_date_report ) {
		//86400 = 1 day
		$date = [];
		for ( $i = (int) $start_date_report->getTimestamp(); $i <= (int) $end_date_report->getTimestamp(); $i = $i + 86400 ) {
			$date[] = $i;
		}

		return $date;
	}

	public function formatTimestampToDateTimeObject( $date ) {
		$date      = explode( '/', $date );
		$datetime1 = date_create( $date[2] . '-' . $date[1] . '-' . $date[0] );

		return $datetime1;
	}

	/**
	 * Get compare range date
	 * */
	public function modifyRangeDate( $dateList, $compare_kind ) {

		// Difference only in months
		switch ( $compare_kind ) {
			case 1:
				$interval = date_diff( $dateList[0], $dateList[1] );
				date_modify( $dateList[0], '-' . ( $interval->d + 1 ) . ' day' );
				date_modify( $dateList[1], '-' . ( $interval->d + 1 ) . ' day' );
				break;
			case 2:
				date_modify( $dateList[0], "-1 month" );
				date_modify( $dateList[1], "-1 month" );
				break;
		}

//        echo $interval->format('%R%a days') . "\n";
//      $interval->d     // day diff number
		return $dateList;
	}
}
