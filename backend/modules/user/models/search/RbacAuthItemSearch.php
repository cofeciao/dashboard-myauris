<?php

namespace backend\modules\user\models\search;

use dosamigos\arrayquery\ArrayQuery;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;

class RbacAuthItemSearch extends Model
{
    /**
     * @var string auth item name
     */
    public $name;

    /**
     * @var int auth item type
     */
    public $type;

    /**
     * @var string auth item description
     */
    public $description;

    /**
     * @var string auth item rule name
     */
    public $ruleName;

    /**
     * @var int the default page size
     */
    public $pageSize = 25;

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['name', 'ruleName'], 'trim'],
            [['type'], 'integer'],
            [['name', 'ruleName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('rbac', 'Name'),
            'type' => Yii::t('rbac', 'Type'),
            'description' => Yii::t('rbac', 'Description'),
            'rule' => Yii::t('rbac', 'Rule'),
            'data' => Yii::t('rbac', 'Data'),
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return \yii\data\ArrayDataProvider
     */
    public function search(array $params): ArrayDataProvider
    {
        $authManager = Yii::$app->getAuthManager();

        if ($this->type == Item::TYPE_ROLE) {
            $result = Yii::$app->getAuthManager()->getAssignments(Yii::$app->user->id);
            foreach ($result as $roleName) {
                $roleNames = $roleName->roleName;
            }
            $items = $authManager->getChildRoles($roleNames);
        } elseif ($this->type == Item::TYPE_PERMISSION) {
            $items = $authManager->getPermissions();
        }

        $query = new ArrayQuery($items);

        $this->load($params);

        if ($this->validate()) {
            $query->addCondition('name', $this->name ? "~{$this->name}" : null)
                ->addCondition('ruleName', $this->ruleName ? "~{$this->ruleName}" : null)
                ->addCondition('description', $this->description ? "~{$this->description}" : null);
        }

        return new ArrayDataProvider([
            'allModels' => $query->find(),
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
        ]);
    }
}
