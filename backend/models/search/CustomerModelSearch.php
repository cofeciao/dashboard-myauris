<?php

namespace backend\models\search;

use backend\models\CustomerModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchVideoReview represents the model behind the search form of `backend\models\VideoReview`.
 */
class CustomerModelSearch extends CustomerModel
{
    public $from;
    public $to;
    public $keyword;
    public $type_search_lichhen = 'date';
    public $type_search_customer_come = 'date';
    public $type_search_code = 'phone';
    public $button;
    public $time_lichhen_from;
    public $time_lichhen_to;
    public $time_customer_come_from;
    public $time_customer_come_to;

    public function rules()
    {
        return [
            [['id', 'button', 'status', 'dat_hen', 'co_so', 'customer_come_time_to', 'permission_user', 'id_dich_vu'], 'integer'],
            [['name', 'customer_code', 'full_name', 'forename', 'avatar', 'phone', 'sex'], 'safe'],
            [['from', 'to', 'type_search_lichhen', 'type_search_customer_come', 'type_search_code'], 'safe'],
            [['keyword', 'time_lichhen_from', 'time_lichhen_to', 'time_customer_come_from', 'time_customer_come_to', 'directsale'], 'safe']
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 10
            ],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($this->button == '') {
            $this->time_lichhen_from = date('d-m-Y');
            $this->time_lichhen_to = date('d-m-Y');
        }

        if ($this->button == 2) {
            $this->keyword = '';
            $this->time_lichhen_from = '';
            $this->time_lichhen_to = '';
            $this->time_customer_come_from = '';
            $this->time_customer_come_to = '';
            $this->status = '';
            $this->dat_hen = '';
            $this->co_so = '';
            $this->customer_come_time_to = '';
            $this->permission_user = '';
            $this->id_dich_vu = '';
            $this->directsale = '';
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // search theo lich hen
        if (isset($this->type_search_lichhen)) {
            if ($this->type_search_lichhen == 'date') {
                if (isset($this->time_lichhen_from) && $this->time_lichhen_from != null) {
                    $time_lichhen_from = strtotime($this->time_lichhen_from);
                    $time_lichhen_to = strtotime($this->time_lichhen_from) + 86399;
                    $query->andFilterWhere(['>', 'time_lichhen', $time_lichhen_from]);
                    $query->andFilterWhere(['<', 'time_lichhen', $time_lichhen_to]);
                }
            } else {
                if (isset($this->time_lichhen_from) && isset($this->time_lichhen_to) && $this->time_lichhen_from != null & $this->time_lichhen_to != null) {
                    $time_lichhen_from = strtotime($this->time_lichhen_from);
                    $time_lichhen_to = strtotime($this->time_lichhen_to) + 86399;
                    $query->andFilterWhere(['>', 'time_lichhen', $time_lichhen_from]);
                    $query->andFilterWhere(['<', 'time_lichhen', $time_lichhen_to]);
                }
            }

//
        }

        // search theo thoi gian khach den
        if (isset($this->type_search_customer_come)) {
            if ($this->type_search_customer_come == 'date') {
                if (isset($this->time_customer_come_from) && $this->time_customer_come_from != null) {
                    $time_customer_come_from = strtotime($this->time_customer_come_from);
                    $time_customer_come_to = strtotime($this->time_customer_come_from) + 86399;
                    $query->andFilterWhere(['>', 'customer_come', $time_customer_come_from]);
                    $query->andFilterWhere(['<', 'customer_come', $time_customer_come_to]);
                }
            } else {
                if (isset($this->time_customer_come_from) && isset($this->time_customer_come_to) && $this->time_customer_come_from != null & $this->time_customer_come_to != null) {
                    $time_customer_come_from = strtotime($this->time_customer_come_from);
                    $time_customer_come_to = strtotime($this->time_customer_come_to) + 86399;
                    $query->andFilterWhere(['>', 'customer_come', $time_customer_come_from]);
                    $query->andFilterWhere(['<', 'customer_come', $time_customer_come_to]);
                }
            }

//
        }

        // search theo keyword: ho ten, sdt, ma kh
        if (isset($this->keyword) && $this->keyword != null) {
            $this->keyword = trim($this->keyword);
            $this->keyword = preg_replace('/\s+/', ' ', $this->keyword);
            $query->andFilterWhere(['or',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.phone', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
            ]);
        }

        if (isset($this->status) && $this->status != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.status' => $this->status]);
        }

        if (isset($this->dat_hen) && $this->dat_hen != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.dat_hen' => $this->dat_hen]);
        }

        if (isset($this->co_so) && $this->co_so != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $this->co_so]);
        }

        if (isset($this->customer_come_time_to) && $this->customer_come_time_to != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.customer_come_time_to' => $this->customer_come_time_to]);
        }

        if (isset($this->permission_user) && $this->permission_user != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.permission_user' => $this->permission_user]);
        }

        if (isset($this->id_dich_vu) && $this->id_dich_vu != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.id_dich_vu' => $this->id_dich_vu]);
        }

        if (isset($this->directsale) && $this->directsale != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.directsale' => $this->directsale]);
        }

//        echo $query->createCommand()->rawSql;
//        die;

        return $dataProvider;
    }
}
