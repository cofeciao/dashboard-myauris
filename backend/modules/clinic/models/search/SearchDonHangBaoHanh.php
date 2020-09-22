<?php

namespace backend\modules\clinic\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\DonHangBaoHanh;

/**
 * SearchDonHangBaoHanh represents the model behind the search form of `backend\modules\clinic\models\DonHangBaoHanh`.
 */
class SearchDonHangBaoHanh extends DonHangBaoHanh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phong_kham_don_hang_id', 'so_luong_rang', 'ngay_thuc_hien', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['ly_do'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = DonHangBaoHanh::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'phong_kham_don_hang_id' => $this->phong_kham_don_hang_id,
            'so_luong_rang' => $this->so_luong_rang,
            'ngay_thuc_hien' => $this->ngay_thuc_hien,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'ly_do', $this->ly_do]);

        return $dataProvider;
    }
}
