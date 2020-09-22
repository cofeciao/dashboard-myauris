<?php

namespace backend\modules\baocao\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baocao\models\BaocaoChayAdsAdswords;

/**
 * BaocaoChayAdwordsSearch represents the model behind the search form of `backend\modules\baocao\models\BaocaoChayAdwords`.
 */
class BaocaoChayAdsAdswordsSearch extends BaocaoChayAdsAdswords {
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[
				[
					'id',
					'status',
					'created_by',
					'updated_by',
					'created_at',
					'updated_at'
				],
				'integer'
			],
			[ 'ngay_tao', 'safe' ],
			[ 'product', 'safe' ],
			[ [ 'amount_money', ], 'number' ],
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
		$query = BaocaoChayAdsAdswords::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider( [
			'query' => $query,
			'sort'  => [ 'defaultOrder' => [ 'ngay_tao' => SORT_DESC ] ]
		] );

		$this->load( $params );

		if ( ! $this->validate() ) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere( [
			'id'           => $this->id,
			'amount_money' => $this->amount_money,

			'status'     => $this->status,
			'product'    => $this->product,
			'created_by' => $this->created_by,
			'updated_by' => $this->updated_by,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		] );
		if ( $this->ngay_tao != null ) {
			$query->andFilterWhere( [
				'ngay_tao' => strtotime( $this->ngay_tao )
			] );
		}


		return $dataProvider;
	}
}
