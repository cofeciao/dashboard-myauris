<?php

namespace backend\modules\appmyauris\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\appmyauris\models\TableTemp;

/**
 * TableTempSearch represents the model behind the search form of `backend\modules\appmyauris\models\TableTemp`.
 */
class TableTempSearch extends TableTemp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_tinh_trang_rang', 'id_do_tuoi', 'status'], 'integer'],
            [['image_before', 'image_after'], 'safe'],
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
        $query = TableTemp::find();

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
            'id_tinh_trang_rang' => $this->id_tinh_trang_rang,
            'id_do_tuoi' => $this->id_do_tuoi,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'image_before', $this->image_before])
            ->andFilterWhere(['like', 'image_after', $this->image_after]);

        return $dataProvider;
    }
}
