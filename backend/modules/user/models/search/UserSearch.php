<?php

namespace backend\modules\user\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\user\models\User;

/**
 * UserSearch represents the model behind the search form of `backend\modules\user\models\User`.
 */
class UserSearch extends User
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['username', 'email', 'role_name', 'fullname', 'phone', 'team', 'permission_coso'], 'safe'],
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
        $user = new User();
        $roleUser = $user->getRoleName(Yii::$app->user->id);

        $query = User::find()->where(['not in', 'id', [Yii::$app->user->id, 1]]);
        /*
         * Nếu có nhiều admin thì thêm: $query->where(['in', 'created_by', User::getChild(\Yii::$app->user->id)])
         */
//        $query->andWhere(['in', 'created_by', User::getChild(Yii::$app->user->id)]);

        if ($roleUser != User::USER_DEVELOP) {
            $query->andWhere(['user.status' => User::STATUS_ACTIVE]);
        }
        if ($roleUser == User::USER_QUANLY_PHONGKHAM) {
            $query->andWhere("((user.permission_coso='" . Yii::$app->user->identity->permission_coso . "') OR (user.created_by='" . Yii::$app->user->id . "' AND user.permission_coso IS null))");
//            $query->andFilterWhere(['OR', ['user.permission_coso' => Yii::$app->user->identity->permission_coso], ['user.created_by' => Yii::$app->user->id]]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        $query->innerJoinWith(['userProfile']);

        $query->join('LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id');
        $query->andWhere(['!=', 'item_name', $roleUser]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'team' => $this->team,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'permission_coso', $this->permission_coso])
            ->andFilterWhere(['like', 'user_profile.phone', $this->phone])
            ->andFilterWhere(['like', 'user_profile.fullname', $this->fullname])
            ->andFilterWhere(['like', 'email', $this->email]);

        if ($this->role_name) {
            $query->andFilterWhere(['rbac_auth_assignment.item_name' => $this->role_name]);
        }
//        echo $query->createCommand()->rawSql;die;

        return $dataProvider;
    }
}
