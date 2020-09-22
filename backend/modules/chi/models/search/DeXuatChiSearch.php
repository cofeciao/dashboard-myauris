<?php

namespace backend\modules\chi\models\search;

use backend\modules\chi\models\query\DeXuatChiQuery;
use backend\modules\user\models\UserSubRole;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\chi\models\DeXuatChi;

/**
 * DeXuatChiSearch represents the model behind the search form of `backend\modules\chi\models\DeXuatChi`.
 */
class DeXuatChiSearch extends DeXuatChi {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'id',
					'created_by',
//                    'thoi_han_thanh_toan',
					'khoan_chi',
					'leader_accept',
					'leader_accept_at',
					'accountant_accept',
					'accountant_accept_at',
					'created_at',
					'updated_by',
					'updated_at',
					'status',
					'inspectioner',
				],
				'integer'
			],
			[
				[
					'so_tien_chi',
					'title',
					'nguoi_trien_khai',
				],
				'safe'
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search( $params ) {
		$this->load( $params );

		$query = DeXuatChi::find();

		// add conditions that should always apply here

		$query = $this->subfilter( $query );
		$query->orderBy( [ 'status' => SORT_ASC, 'leader_accept_at' => SORT_DESC, ] );


		$dataProvider = new ActiveDataProvider( [
			'query' => $query,
//			'sort'  => [ 'defaultOrder' => [ 'status' => SORT_ASC ] ]
		] );


		if ( ! $this->validate() ) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}


		$query->andFilterWhere( [ 'or', [ 'like', 'title', $this->title ] ] );
		$query->andFilterWhere( [ 'created_by' => $this->created_by ] );
		$query->andFilterWhere( [ 'nguoi_trien_khai' => $this->nguoi_trien_khai ] );
		$query->andFilterWhere( [ 'leader_accept' => $this->leader_accept ] );
		$query->andFilterWhere( [ 'accountant_accept' => $this->accountant_accept ] );
		$query->andFilterWhere( [ 'status' => $this->status ] );
		$query->andFilterWhere( [ 'khoan_chi' => $this->khoan_chi ] );
		$query->andFilterWhere( [ 'inspectioner' => $this->inspectioner] );


		if ( ! empty( $this->so_tien_chi ) ) {
			$so_tien_chi = explode( ':', $this->so_tien_chi );
			if ( is_array( $so_tien_chi ) ) {
				if ( isset( $so_tien_chi[1] ) && isset( $so_tien_chi[0] ) ) {
					$condition_arr = [ 'between', 'so_tien_chi', (int) $so_tien_chi[0], (int) $so_tien_chi[1] ];
					$query->andFilterWhere( $condition_arr );

				}
			}
		}

		return $dataProvider;
	}

	public function subfilter( DeXuatChiQuery $query ) {
		if ( ! empty( $query ) ) {
			$curr_id       = Yii::$app->user->id;
			$curr_user_obj = User::findOne( $curr_id );

			switch ( $curr_user_obj->subroleHasOne->role ) {
				case UserSubRole::ROLE_TEAM_LEAD:
					$query->andFilterWhere( [ 'in', 'status', DeXuatChi::TL_CAN_VIEW_STT ] )
					      ->andWhere( [ 'created_by' => $curr_id ] )->orFilterWhere( [ 'nguoi_trien_khai' => $curr_id ] );
					break;
				case UserSubRole::ROLE_TRUONG_PHONG:
//                        $query->andFilterWhere(['in', 'status', DeXuatChi::TP_CAN_VIEW_STT]);
					break;
				case UserSubRole::ROLE_KE_TOAN:
					$query->andFilterWhere( [
						'in',
						'status',
						DeXuatChi::KT_CAN_VIEW_STT
					] )->orFilterWhere( [ DeXuatChi::tableName() . '.created_by' => Yii::$app->user->id ] );
					break;
				default:
					$query->andFilterWhere( [ 'in', 'status', DeXuatChi::TL_CAN_VIEW_STT ] )
					      ->andWhere( [ 'created_by' => $curr_id ] )->orFilterWhere( [ 'nguoi_trien_khai' => $curr_id ] )->orFilterWhere( [ 'inspectioner' => $curr_id ] );
					break;
			}
		}

		return $query;
	}
}
