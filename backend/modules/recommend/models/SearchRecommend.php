<?php

namespace backend\modules\recommend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchRecommend represents the model behind the search form of `backend\modules\recommend\models\Recommend`.
 */
class SearchRecommend extends Recommend
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['gioi_tinh', 'nhom_tuoi', 'bo_cuc', 'tinh_trang_rang', 'mong_muon', 'phong_cach', 'giai_phap', 'san_pham', 'phan_loai', 'tieu_de'], 'safe'],
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
//        Yii::warning($params);
        $query = Recommend::find();

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
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gioi_tinh', $this->gioi_tinh])
            ->andFilterWhere(['like', 'nhom_tuoi', $this->nhom_tuoi])
            ->andFilterWhere(['like', 'bo_cuc', $this->bo_cuc])
            ->andFilterWhere(['like', 'tinh_trang_rang', $this->tinh_trang_rang])
            ->andFilterWhere(['like', 'mong_muon', $this->mong_muon])
            ->andFilterWhere(['like', 'phong_cach', $this->phong_cach])
            ->andFilterWhere(['like', 'giai_phap', $this->giai_phap])
            ->andFilterWhere(['like', 'san_pham', $this->san_pham]);

        if (!empty($this->nhom_tuoi)) {
            $query->andwhere("JSON_SEARCH(nhom_tuoi, 'all', " . $this->nhom_tuoi . ") is not null");
        }
        if (!empty($this->bo_cuc)) {
            $query->andwhere("JSON_SEARCH(bo_cuc, 'all', " . $this->bo_cuc . ") is not null");
        }
        if (!empty($this->tinh_trang_rang)) {
            $query->andwhere("JSON_SEARCH(tinh_trang_rang, 'all', " . $this->tinh_trang_rang . ") is not null");
        }
        if (!empty($this->mong_muon)) {
            $query->andwhere("JSON_SEARCH(mong_muon, 'all', " . $this->mong_muon . ") is not null");
        }
        if (!empty($this->phuong_phap)) {
            $query->andwhere("JSON_SEARCH(phuong_phap, 'all', " . $this->phuong_phap . ") is not null");
        }
        if (!empty($this->phong_cach)) {
            $query->andwhere("JSON_SEARCH(phong_cach, 'all', " . $this->phong_cach . ") is not null");
        }
        if (!empty($this->phan_loai)) {
            $query->andwhere("JSON_SEARCH(phan_loai, 'all', " . $this->phan_loai . ") is not null");
        }
        if (!empty($this->tieu_de)) {
            $query->andFilterWhere(['like', 'tieu_de', $this->tieu_de]);
        }
        return $dataProvider;
    }
}
