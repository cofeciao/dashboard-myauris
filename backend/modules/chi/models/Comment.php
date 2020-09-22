<?php

namespace backend\modules\chi\models;

use backend\modules\user\models\User;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    const COMMENT_TABLE_DE_XUAT_CHI = 'de-xuat-chi';
    const COMMENT_TABLE_ISSUE = 'issue';
    const COMMENT_TABLE = [
        self::COMMENT_TABLE_DE_XUAT_CHI => DeXuatChi::class,
    ];

    public $commentFor;
    public $commentTable;

    public function __construct($id = null, $commentTable = null)
    {
        $this->id_de_xuat_chi = $id;
        $this->table_name = $commentTable != null ? $commentTable : self::COMMENT_TABLE_DE_XUAT_CHI;
        parent::__construct();
    }

    public static function tableName()
    {
        return 'thuchi_comment';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by']
                ],
                'value' => function () {
                    return \Yii::$app->user->id;
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
                ],
                'value' => time()
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_VALIDATE => ['table_name'],
                ],
                'value' => function () {
                    $className = self::COMMENT_TABLE[$this->table_name];
                    if (!class_exists($className)) return null;
                    return $this->table_name;
                }
            ],
        ];
    }

    public function rules()
    {
        return [
            ['id_de_xuat_chi', 'exist', 'targetClass' => DeXuatChi::class, 'targetAttribute' => 'id'],
            [['id_de_xuat_chi', 'comment'], 'required'],
            [['comment_for', 'table_name'], 'string'],
            ['table_name', 'checkCommentTable'],
            ['comment_for', 'exist', 'targetClass' => self::COMMENT_TABLE[$this->table_name], 'targetAttribute' => 'id']
        ];
    }

    public function checkCommentTable()
    {
        if (!$this->hasErrors()) {
            if (!array_key_exists($this->table_name, self::COMMENT_TABLE)) {
                $this->addError('comment', 'Không được bình luận mục này');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id_de_xuat_chi' => \Yii::t('backend', 'Đề xuất chi'),
            'comment' => \Yii::t('backend', 'Comment')
        ];
    }

    public function getCreatedByHasOne()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public static function getListCommentByDeXuatChi($id)
    {
        return self::find()->where(['id_de_xuat_chi' => $id])->all();
    }
}
