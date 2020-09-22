<?php

namespace backend\modules\user\models;

use backend\modules\user\models\query\RbacAuthItemQuery;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\UserProfile;

/**
 * This is the model class for table "rbac_auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property RbacAuthRule $ruleName
 * @property RbacAuthItemChild[] $rbacAuthItemChildren
 * @property RbacAuthItemChild[] $rbacAuthItemChildren0
 * @property RbacAuthItem_[] $children
 * @property RbacAuthItem_[] $parents
 */
class RbacAuthItem_ extends \yii\db\ActiveRecord
{
    const TYPE_DEV = 0;
    const TYPE_ROLE_BACKEND = 1;
    const TYPE_ROLE_FRONTEND = 2;
    const TYPE_PERMISSION_BACKEND = 3;
    const TYPE_PERMISSION_FRONTEND = 4;
    const TYPE_PERMISSION_BACKEND_CREATE = 5;
    const TYPE_PERMISSION_FRONTEND_CREATE = 6;
    const TYPE_LOGIN_TO_BACKEND = 7;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rbac_auth_item';
    }

    public static function find()
    {
        return new RbacAuthItemQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['description', 'data', 'alias'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => RbacAuthRule::class, 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('backend', 'Name'),
            'type' => Yii::t('backend', 'Type'),
            'description' => Yii::t('backend', 'Description'),
            'rule_name' => Yii::t('backend', 'Rule Name'),
            'data' => Yii::t('backend', 'Data'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'alias' => Yii::t('backend', 'Alias'),
        ];
    }

    public static function getRoleType()
    {
        return [
            self::TYPE_BACKEND => Yii::t('backend', 'Role Backend'),
            self::TYPE_FRONTEND => Yii::t('backend', 'Role Frontend'),
        ];
    }

    public static function getPermissionType()
    {
        return [
            self::TYPE_PERMISSION_BACKEND => Yii::t('backend', 'Quyền Backend'),
            self::TYPE_PERMISSION_FRONTEND => Yii::t('backend', 'Quyền Frontend'),
        ];
    }

    /*
     * Tìm tất cả role children của $roleName được truyền vào theo Type của nó
     * Trả về tất cả Role children
     */
    public static function getRoleChildrenWithType($roleName)
    {
        $auth = Yii::$app->authManager;
//        $type = $auth->getRole($roleName)->type;
//        $this->type = self::TYPE_PERMISSION_FRONTEND;
        return array_keys($auth->getRoles());
    }

    public function getUserHasMany()
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable('rbac_auth_assignment', ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(RbacAuthRule::class, ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAuthItemChildren()
    {
        return $this->hasMany(RbacAuthItemChild::class, ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbacAuthItemChildren0()
    {
        return $this->hasMany(RbacAuthItemChild::class, ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(RbacAuthItem::class, ['name' => 'child'])->viaTable('rbac_auth_item_child', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(RbacAuthItem::class, ['name' => 'parent'])->viaTable('rbac_auth_item_child', ['child' => 'name']);
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            $id = 2;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            $id = 2;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public static function getByName($name)
    {
        return self::find()->where(['name' => $name])->one();
    }

    public static function getItemForPhongBan($phong_ban_alias = null, $exclude = null)
    {
        $query = self::find()->where(['type' => 1]);
        if ($phong_ban_alias != null) {
            $query->andWhere(['OR', ['alias' => null], "alias LIKE '{$phong_ban_alias}%'"]);
        }
        if (is_string($exclude)) $exclude = [$exclude];
        if (is_array($exclude)) $query->andWhere(['NOT IN', 'name', $exclude]);
        return $query->all();
    }
}
