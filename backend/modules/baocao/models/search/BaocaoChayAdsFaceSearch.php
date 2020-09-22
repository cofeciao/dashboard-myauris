<?php

namespace backend\modules\baocao\models\search;

use backend\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\baocao\models\BaocaoChayAdsFace;
use yii\db\Expression;
use common\models\User as CommonUser;

class BaocaoChayAdsFaceSearch extends BaocaoChayAdsFace
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['location_id', 'id', 'don_vi', 'page_chay', 'status', 'created_at', 'updated_at', 'updated_by', 'san_pham'], 'integer'],
            [['so_tien_chay', 'ngay_chay'], 'safe'],
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
        $query = BaocaoChayAdsFace::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ngay_chay' => SORT_DESC],
            ]
        ]);

        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);

        if (!in_array($roleUser, [
            CommonUser::USER_DEVELOP,
            CommonUser::USER_ADMINISTRATOR,
            CommonUser::USER_MANAGER_CHAY_ADS,
            CommonUser::USER_COVAN
        ])) {
            $query->andWhere(['don_vi' => Yii::$app->user->id]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['sanPhamHasOne']);

        // grid filtering conditions
        $query->andFilterWhere([
            'don_vi' => $this->don_vi,
            'location_id' => $this->location_id,
            'page_chay' => $this->page_chay,
            'san_pham' => $this->san_pham,
            'so_tien_chay' => $this->so_tien_chay,
        ]);

        if ($this->ngay_chay != null) {
            $query->andFilterWhere([
                'ngay_chay' => strtotime($this->ngay_chay)
            ]);
        }

        return $dataProvider;
    }
}
