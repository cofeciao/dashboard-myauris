<?php

namespace backend\modules\customer\models\search;

use backend\modules\customer\models\CustomerOnlineRemindCall;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\customer\models\Dep365CustomerOnlineRemindCall;

/**
 * CustomerOnlineRemindCallSearch represents the model behind the search form of `backend\modules\customer\models\Dep365CustomerOnlineRemindCall`.
 */
class CustomerOnlineRemindCallSearch extends CustomerOnlineRemindCall
{
    public $button;
    public $full_name;
    public $type_search_date = 'range';
    public $from;
    public $to;
    public $alert;
    public $status_search;

    public function rules()
    {
        return [
            [['button'], 'integer'],
            [['full_name', 'type_search_date', 'from', 'to', 'alert', 'status_search'], 'safe'],
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
        $query = CustomerOnlineRemindCall::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['customerHasOne', 'customerOnlineDatHenStatus', 'customerOnlineFailStatus', 'customerOnlineFailDatHen'])
            ->orderBy([new \yii\db\Expression('FIELD (remind_call_date, ' . strtotime(date('d-m-Y')) . ') DESC'), 'remind_call_time' => SORT_DESC])
            ->published()
            ->andWhere(['type' => CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE]);

        if ($this->button == '' || (isset($this->alert) && $this->alert == true)) {
            $this->from = date('d-m-Y', strtotime(date('d-m-Y') . ' -7days'));
            $this->to = date('d-m-Y', strtotime(date('d-m-Y') . ' +1days') - 1);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->status_search != null) {
            $query->andFilterWhere([self::tableName() . '.status' => $this->status_search]);
            if ($this->status_search == 1) {
                $query->andFilterWhere([self::tableName() . '.dat_hen' => Dep365CustomerOnline::DAT_HEN_KHONG_DEN]);
            }
        }

        // nếu link được lấy từ trang chủ, lấy ra remind call của chính nhận viên đang đăng nhập
        if (isset($this->alert) && $this->alert == true) {
            $query->andFilterWhere(['!=', 'dep365_customer_online_remind_call.remind_call_time', 'null']);
        }
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);
        if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR, User::USER_MANAGER_ONLINE])) {
            $query->andFilterWhere(['dep365_customer_online_remind_call.permission_user' => Yii::$app->user->id]);
        }
        // grid filtering conditions
        if ($this->full_name != null) {
            $this->full_name = trim($this->full_name);
            $this->full_name = preg_replace('/\s+/', ' ', $this->full_name);
            $query->andFilterWhere(['or',
                ['like', 'dep365_customer_online.name', $this->full_name],
                ['like', 'dep365_customer_online.forename', $this->full_name],
                ['like', 'dep365_customer_online.full_name', $this->full_name],
                ['like', 'dep365_customer_online.phone', $this->full_name],
            ]);
        }
        if ($this->from != null && $this->from != '') {
            $from = strtotime($this->from);
            $to = ($this->to != null && $this->to != '' ? strtotime($this->to) : $from) + 86399;
            $query->andFilterWhere(['between', 'remind_call_time', $from, $to]);
        }

//        echo $query->createCommand()->rawSql;die;
        return $dataProvider;
    }
}
