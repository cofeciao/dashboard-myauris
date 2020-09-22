<?php

namespace backend\modules\clinic\models\search;

use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\Customer;
use backend\modules\user\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CustomerSearch extends Customer
{
    public $button = '';
    public $from;
    public $to;
    public $type_search_date = 'date';
    public $type_search_code = 'full_name';

    public function rules()
    {
        return [
            [['co_so', 'permission_user', 'directsale', 'province', 'dat_hen'], 'integer'],
            [['full_name', 'phone', 'customer_code', 'sex', 'note', 'note_direct',], 'safe'],
            [['button', 'from', 'to', 'type_search_date', 'type_search_code'], 'string'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }


    public function search($params)
    {
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);

        $query = Clinic::find()->where(['dep365_customer_online.status' => Clinic::STATUS_DH]);
        $query->andWhere(['dat_hen' => Customer::DA_DEN]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['customer_come' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($this->button == '') {
            $this->from = date('d-m-Y');
            $this->to = date('d-m-Y');
        }

        $query->joinWith(['provinceHasOne', 'directSaleHasOne']);

//        if (!$this->validate()) {
//            return $dataProvider;
//        }
        if($this->button == null && $this->co_so == null && \Yii::$app->user->identity->permission_coso != null) $this->co_so = \Yii::$app->user->identity->permission_coso;

        if($this->co_so != null){
            $query->andFilterWhere(['co_so' => $this->co_so]);
        }

        if (isset($this->full_name) && $this->full_name != null ||
            isset($this->customer_code) && $this->customer_code != null) {
            $this->full_name = trim($this->full_name);
            $this->full_name = preg_replace('/\s+/', ' ', $this->full_name);
            $this->customer_code = trim($this->customer_code);
            $this->customer_code = preg_replace('/\s+/', '', $this->customer_code);
            $query->andFilterWhere(['or',
                ['like', 'dep365_customer_online.full_name', $this->full_name],
                ['like', 'dep365_customer_online.name', $this->full_name],
                ['like', 'dep365_customer_online.customer_code', $this->customer_code],
            ]);
        }

        if (isset($this->type_search_date)) {
            if ($this->type_search_date == 'date') {
                if (isset($this->from) && $this->from != null) {
                    $from = strtotime($this->from);
                    $to = strtotime($this->from) + 86399;
                    $query->andFilterWhere(['>', 'time_lichhen', $from]);
                    $query->andFilterWhere(['<', 'time_lichhen', $to]);
                }
            } else {
                if (isset($this->from) && isset($this->to) && $this->from != null & $this->to != null) {
                    $from = strtotime($this->from);
                    $to = strtotime($this->to) + 86399;
                    $query->andFilterWhere(['>', 'time_lichhen', $from]);
                    $query->andFilterWhere(['<', 'time_lichhen', $to]);
                }
            }
        }

//        echo $query->createCommand()->getRawSql();

        return $dataProvider;
    }
}
