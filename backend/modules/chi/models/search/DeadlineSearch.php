<?php

namespace backend\modules\chi\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\chi\models\Deadline;

/**
 * DeadlineSearch represents the model behind the search form of `backend\modules\chi\models\Deadline`.
 */
class DeadlineSearch extends Deadline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_de_xuat_chi', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'created_at', 'created_by'], 'integer'],
            [['danh_gia'], 'safe'],
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
        $query = Deadline::find();

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
            'id_de_xuat_chi' => $this->id_de_xuat_chi,
            'thoi_gian_bat_dau' => $this->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $this->thoi_gian_ket_thuc,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'danh_gia', $this->danh_gia]);

        return $dataProvider;
    }
}
