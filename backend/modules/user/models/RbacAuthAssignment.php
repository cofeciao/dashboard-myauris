<?php

namespace backend\modules\user\models;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "rbac_auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 */
class RbacAuthAssignment extends BaseObject
{
    /**
     * @var IdentityInterface
     */
    public $user;

    /**
     * @var int User id
     */
    public $userId;

    /**
     * @var \yii\rbac\ManagerInterface
     */
    protected $manager;

    /**
     * AssignmentModel constructor.
     *
     * @param IdentityInterface $user
     * @param array $config
     *
     * @throws InvalidConfigException
     */
    public function __construct(IdentityInterface $user)
    {
        $this->user = $user;
        $this->userId = $user->getId();
        $this->manager = Yii::$app->authManager;

        if ($this->userId === null) {
            throw new InvalidConfigException('The "userId" property must be set.');
        }

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('backend', 'Quyền'),
            'user_id' => Yii::t('backend', 'User ID'),
            'created_at' => Yii::t('backend', 'Created At'),
        ];
    }
}
