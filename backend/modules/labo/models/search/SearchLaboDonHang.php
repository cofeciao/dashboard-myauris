<?php

namespace backend\modules\labo\models\search;

use backend\modules\labo\models\LaboDonHang;
use backend\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchLaboDonHang represents the model behind the search form of `backend\modules\labo\models\LaboDonHang`.
 */
class SearchLaboDonHang extends LaboDonHang
{
    /**
     * @inheritdoc
     */
    public $keyword;

    public function rules()
    {
        return [
            [['id', 'bac_si_id', 'phong_kham_don_hang_id', 'ngay_nhan', 'ngay_giao', 'loai_su', 'trang_thai', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['loai_phuc_hinh', 'yeu_cau', 'keyword'], 'safe'],
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
        $query = LaboDonHang::find();

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
        $user = new User();
        $roleName = $user->getRoleName(Yii::$app->user->id);

        if (in_array($roleName, [
            User::USER_KY_THUAT_LABO
        ])) {
            $query->andFilterWhere(['user_labo' => Yii::$app->user->id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'bac_si_id' => $this->bac_si_id,
            'phong_kham_don_hang_id' => $this->phong_kham_don_hang_id,
            'ngay_nhan' => $this->ngay_nhan,
            'ngay_giao' => $this->ngay_giao,
            'loai_su' => $this->loai_su,
            'trang_thai' => $this->trang_thai,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'loai_phuc_hinh', $this->loai_phuc_hinh])
            ->andFilterWhere(['like', 'yeu_cau', $this->yeu_cau]);

        return $dataProvider;
    }
}
