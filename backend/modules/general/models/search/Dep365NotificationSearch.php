<?php

namespace backend\modules\general\models\search;

use backend\modules\user\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\general\models\Dep365Notification;

/**
 * Dep365NotificationSearch represents the model behind the search form of `backend\modules\general\models\Dep365Notification`.
 */
class Dep365NotificationSearch extends Dep365Notification
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'is_new', 'status', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'slug', 'icon', 'description', 'content'], 'safe'],
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
        $query = Dep365Notification::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $userInfo = User::getUserInfo(\Yii::$app->user->id);

        $for_who = [Dep365Notification::FOR_EVERYONE];
        if ($userInfo->item_name != null) {
            $for_who[] = $userInfo->item_name;
        }
        if ($userInfo->subroleHasOne != null && $userInfo->subroleHasOne->role != null) {
            $for_who[] = $userInfo->subroleHasOne->role;
        }

        /* for manager online - remove later */
        if ($userInfo->item_name == User::USER_MANAGER_ONLINE) {
            $for_who[] = 'online';
        }

        $query->andWhere(['IN', 'for_who', $for_who]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_new' => $this->is_new,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
