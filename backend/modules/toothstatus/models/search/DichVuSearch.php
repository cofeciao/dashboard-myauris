<?php

namespace backend\modules\toothstatus\models\search;

use backend\modules\toothstatus\models\DichVu;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LuaChonLoaiDichVuSearch represents the model behind the search form of `backend\modules\toothstatus\models\LuaChonLoaiDichVu`.
 */
class DichVuSearch extends DichVu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price'], 'integer'],
            [['name', 'slug', 'description', 'status'], 'safe'],
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
        $query = self::find()->select([
            self::tableName().'.id',
            self::tableName().'.name',
            self::tableName().'.price',
            self::tableName().'.description',
            self::tableName().'.status',
            self::tableName().'.created_by'
        ])->joinWith(['tinhTrangRangHasMany', 'doTuoiHasMany', 'luaChonHasMany'])
        ->groupBy([
            self::tableName().'.id',
            self::tableName().'.name',
            self::tableName().'.price',
            self::tableName().'.description',
            self::tableName().'.status',
            self::tableName().'.created_by'
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', self::tableName().'.name', $this->name])
            ->andFilterWhere(['like', self::tableName().'.slug', $this->slug])
            ->andFilterWhere(['like', self::tableName().'.description', $this->description])
            ->andFilterWhere(['like', self::tableName().'.status', $this->status]);

        return $dataProvider;
    }
}
