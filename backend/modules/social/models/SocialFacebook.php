<?php


namespace backend\modules\social\models;

use backend\models\CustomerModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SocialFacebook extends CustomerModel
{
    public $from;
    public $to;
    public $status = null;
    public $khach_cu = null;
    public $dat_hen = null;
    public $da_den = null;
    public $type_search_date = 'date';
    public $button = '';

    public function rules()
    {
        return ([
            [['status','khach_cu','dat_hen','da_den','button'],'integer'],
            [['from','to','type_search_date'],'string']
        ]);
    }
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerModel::find()->joinWith(['statusCustomerHasOne', 'statusDatHenHasOne', 'statusCustomerGotoAurisHasOne', 'provinceHasOne']);
        if ($this->button == '') {
            $this->from = date('d-m-Y');
            $this->to = date('d-m-Y');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]]
        ]);
        $this->load($params);
        if (isset($this->type_search_date)) {
            if ($this->type_search_date == 'date') {
                if (isset($this->from) && $this->from != null) {
                    $from = strtotime($this->from);
                    $to = strtotime($this->from) + 86399;
                    $query->andFilterWhere(['>', CustomerModel::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<', CustomerModel::tableName() . '.created_at', $to]);
                }
            } else {
                if (isset($this->from) && isset($this->to) && $this->from != null || $this->to != null) {
                    $from = strtotime($this->from);
                    $to = strtotime($this->to) + 86399;
                    if ($this->from != '') {
                        $query->andFilterWhere(['>', CustomerModel::tableName() . '.created_at', $from]);
                    }
                    if ($this->to != '') {
                        $query->andFilterWhere(['<', CustomerModel::tableName() . '.created_at', $to]);
                    }
                }
            }
        }
        if (isset($this->status) && $this->status != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.status' => $this->status]);
        }
        if (isset($this->khach_cu) && $this->khach_cu != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.customer_old' => $this->khach_cu]);
        }
        if (isset($this->dat_hen) && $this->dat_hen != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.dat_hen' => $this->dat_hen]);
        }
        if (isset($this->da_den) && $this->da_den != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.customer_come_time_to' => $this->da_den]);
        }
        return $dataProvider;
    }
}
