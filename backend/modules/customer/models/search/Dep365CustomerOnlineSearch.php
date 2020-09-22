<?php

namespace backend\modules\customer\models\search;

//use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
//use backend\modules\customer\models\Dep365SendSms;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\RemindCall\CustomerOnlineModel;
use backend\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Dep365CustomerOnlineSearch represents the model behind the search form of `backend\modules\customer\models\Dep365CustomerOnline`.
 */
class Dep365CustomerOnlineSearch extends Dep365CustomerOnline
{
    public $date_from;
    public $date_to;
    public $alert;
    public $button;
    public $keyword;
    public $creation_time_from;
    public $creation_time_to;
    public $appointment_time_from;
    public $appointment_time_to;
    public $type_search_create = 'date';
    public $type_search_appointment = 'date';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'nguon_online', 'district', 'co_so', 'face_fanpage', 'permission_user', 'province', 'status_fail', 'dat_hen', 'id_dich_vu'], 'integer'],
            [['name', 'slug', 'phone', 'sex', 'note', 'birthday', 'tt_kh', 'created_at', 'time_lichhen', 'ngaythang', 'date_from', 'date_to', 'forename'], 'safe'],
            [['button', 'keyword', 'appointment_time_from', 'appointment_time_to', 'creation_time_from', 'creation_time_to', 'type_search_create', 'type_search_appointment', 'alert', 'customer_come_time_to', 'face_fanpage'], 'safe'],
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


    public function search($params)
    {
        $select = [
            self::tableName() . '.id',
            self::tableName() . '.name',
            self::tableName() . '.slug',
            self::tableName() . '.forename',
            self::tableName() . '.phone',
            self::tableName() . '.sex',
            self::tableName() . '.status',
            self::tableName() . '.dat_hen',
            self::tableName() . '.time_lichhen',
            self::tableName() . '.permission_user',
            self::tableName() . '.co_so',
            self::tableName() . '.nguon_online',
            /*self::tableName() . '.province',*/
            self::tableName() . '.face_fanpage',
            self::tableName() . '.created_at',
            self::tableName() . '.customer_come_time_to',
            self::tableName() . '.id_dich_vu',
            self::tableName() . '.directsale',
            self::tableName() . '.status_fail',
            self::tableName() . '.dat_hen_fail',
            self::tableName() . '.note',
            self::tableName() . '.created_by',
            Dep365CustomerOnlineDichVu::tableName() . '.name AS nameDichVu'
        ];
        $query = Dep365CustomerOnline::find()->select($select)->findCustomerOfOnline();

        // add conditions that should always apply here

        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);
        $this->load($params);
        if ($roleUser == User::USER_DATHEN) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['time_lichhen' => SORT_DESC]]
            ]);
            $query = $query->where([CustomerOnlineModel::tableName() . '.status' => Dep365CustomerOnline::STATUS_DH]);
        } elseif ($roleUser == User::USER_MANAGER_ONLINE || $roleUser == User::USER_NHANVIEN_ONLINE) {
            $query->andWhere(['is_customer_who' => Dep365CustomerOnline::IS_CUSTOMER_TV_ONLINE]);
            if ($roleUser == User::USER_NHANVIEN_ONLINE) {
                if ($this->button != 1) {
                    $query->andWhere(['or', [CustomerOnlineModel::tableName() . '.created_by' => Yii::$app->user->id], [CustomerOnlineModel::tableName() . '.permission_user' => Yii::$app->user->id]]);
                }
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
            ]);
        }

        $query->joinWith([/*'provinceHasOne', */
            'directSaleHasOne', 'failStatusCustomerOnlineHasOne', 'failDatHenCustomerOnlineHasOne', 'dichVuOnlineHasOne']);
//        $query->groupBy(CustomerOnlineModel::tableName() . '.id');

//        if (!$this->validate()) {
//            return $dataProvider;
//        }

        if ($this->button == '') {
            $this->creation_time_from = date('d-m-Y');
            $this->creation_time_to = date('d-m-Y');
        }

        if (isset($this->alert) && $this->alert == true) {
            $yesterday = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
            $this->permission_user = Yii::$app->user->id;
            $this->dat_hen = Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
            $this->creation_time_from = $yesterday;
        }

        if (isset($this->keyword) && $this->keyword != null) {
            $this->keyword = trim($this->keyword);
            $this->keyword = preg_replace('/\s+/', ' ', $this->keyword);
            $query->andFilterWhere(['or',
                ['like', CustomerOnlineModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerOnlineModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerOnlineModel::tableName() . '.name', $this->keyword],
                ['like', CustomerOnlineModel::tableName() . '.phone', $this->keyword],
                ['like', CustomerOnlineModel::tableName() . '.customer_code', $this->keyword],
            ]);
        }

        if (isset($this->co_so) && $this->co_so != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.co_so' => $this->co_so]);
        }

        if (isset($this->status) && $this->status != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.status' => $this->status]);
        }

        if (isset($this->dat_hen) && $this->dat_hen != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.dat_hen' => $this->dat_hen]);
        }

        if (isset($this->permission_user) && $this->permission_user != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.permission_user' => $this->permission_user]);
        }

        if (isset($this->id_dich_vu) && $this->id_dich_vu != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.id_dich_vu' => $this->id_dich_vu]);
        }

        if (isset($this->customer_come_time_to) && $this->customer_come_time_to != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.customer_come_time_to' => $this->customer_come_time_to]);
        }

        if (isset($this->face_fanpage) && $this->face_fanpage != null) {
            $query->andFilterWhere([CustomerOnlineModel::tableName() . '.face_fanpage' => $this->face_fanpage]);
        }

        if (isset($this->type_search_create)) {
            if ($this->type_search_create == 'date') {
                if (isset($this->creation_time_from) && $this->creation_time_from != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_from) + 86399;
                    $query->andFilterWhere(['>', CustomerOnlineModel::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<', CustomerOnlineModel::tableName() . '.created_at', $to]);
                }
            } else {
                if (isset($this->creation_time_from) && isset($this->creation_time_to) &&
                    $this->creation_time_from != null && $this->creation_time_to != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_to) + 86399;
                    $query->andFilterWhere(['>', CustomerOnlineModel::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<', CustomerOnlineModel::tableName() . '.created_at', $to]);
                }
            }
        }

        if (isset($this->type_search_appointment)) {
            if ($this->type_search_appointment == 'date') {
                if (isset($this->appointment_time_from) && $this->appointment_time_from != null) {
                    $from = strtotime($this->appointment_time_from);
                    $to = strtotime($this->appointment_time_from) + 86399;
                    $query->andFilterWhere(['>', CustomerOnlineModel::tableName() . '.time_lichhen', $from]);
                    $query->andFilterWhere(['<', CustomerOnlineModel::tableName() . '.time_lichhen', $to]);
                }
            } else {
                if (isset($this->appointment_time_from) && isset($this->appointment_time_to) &&
                    $this->appointment_time_from != null && $this->appointment_time_to != null) {
                    $from = strtotime($this->appointment_time_from);
                    $to = strtotime($this->appointment_time_to) + 86399;
                    $query->andFilterWhere(['>', CustomerOnlineModel::tableName() . '.time_lichhen', $from]);
                    $query->andFilterWhere(['<', CustomerOnlineModel::tableName() . '.time_lichhen', $to]);
                }
            }
        }
//        Yii::warning($query->createCommand()->rawSql);
//        echo $query->createCommand()->rawSql;
//        die;

        return $dataProvider;
    }

    public function searchSms($params)
    {
        $query = Dep365CustomerOnline::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['time_lichhen' => SORT_DESC]]
        ]);
        $query = $query->where([CustomerOnlineModel::tableName() . '.status' => Dep365CustomerOnline::STATUS_DH]);


        $this->load($params);

//        $query->leftJoin('dep365_send_sms', CustomerOnlineModel::tableName() . '.id = dep365_send_sms.customer_id');

        if (!$this->validate()) {
            return $dataProvider;
        }

        $datetime = new \DateTime();
        $from = strtotime($datetime->format('d-m-Y'));
        $to = $from + 86400;
        $datetime->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'));
        $query->andWhere('(`' . CustomerOnlineModel::tableName() . '`.`time_lichhen` between ' . $from . ' and ' . $to . ' and `' . CustomerOnlineModel::tableName() . '`.`updated_at` between ' . $from . ' and ' . $to . ')
        or (`' . CustomerOnlineModel::tableName() . '`.`time_lichhen` between ' . ($from + 86400) . ' and ' . ($to + 86400) . ')
        or (`' . CustomerOnlineModel::tableName() . '`.`time_lichhen` between ' . ($from + 3 * 86400) . ' and ' . ($to + 3 * 86400) . ')
        or (`' . CustomerOnlineModel::tableName() . '`.`time_lichhen` between ' . ($from + 7 * 86400) . ' and ' . ($to + 7 * 86400) . ')');

//        echo $query->createCommand()->rawSql;
//        die;

        return $dataProvider;
    }
}
