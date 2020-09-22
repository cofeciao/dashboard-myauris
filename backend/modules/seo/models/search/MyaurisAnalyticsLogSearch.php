<?php

namespace backend\modules\seo\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\seo\models\MyaurisAnalyticsLog;

/**
 * MyaurisAnalyticsLogSearch represents the model behind the search form of `backend\modules\seo\models\MyaurisAnalyticsLog`.
 */
class MyaurisAnalyticsLogSearch extends MyaurisAnalyticsLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time', 'created_at'], 'integer'],
            [['from_url', 'first_url', 'event_url', 'cookie_user_id', 'device_info', 'phone', 'max_time'], 'safe'],
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
        Yii::$app->db->createCommand('SET SESSION group_concat_max_len=1000000')->execute();
        $query = MyaurisAnalyticsLog::find()
            ->from(MyaurisAnalyticsLog::tableName() . ' AS t')
            ->select([
                'cookie_user_id',
                "MAX(time) AS max_time",
                "GROUP_CONCAT(CONCAT('{\"from_url\":\"', REPLACE(from_url, '/', '\\\/'), '\",\"referer_url\":\"', REPLACE(referer_url, '/', '\\\/'),'\",\"first_url\":\"', REPLACE(first_url, '/', '\\\/'),'\",\"event_url\":\"', REPLACE(event_url, '/', '\\\/'),'\",\"event_name\":\"', REPLACE(event_name, '/', '\\\/'),'\",\"time\":\"', time, '\"}')) AS logs"
            ])
            ->groupBy(['cookie_user_id'])
            ->orderBy("MAX(time) DESC");

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

        if ($this->max_time != null) {
            $from = strtotime($this->max_time);
            $to = $from + 86399;
            $query->where("(SELECT MAX(time) FROM " . MyaurisAnalyticsLog::tableName() . " t1 WHERE t1.id=t.id) BETWEEN " . $from . " AND " . $to);
        }
//        echo $query->createCommand()->rawSql;
//        die;
        return $dataProvider;
    }
}
