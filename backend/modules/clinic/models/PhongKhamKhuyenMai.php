<?php

namespace backend\modules\clinic\models;

use backend\modules\clinic\models\query\PhongKhamKhuyenMaiQuery;
use common\helpers\MyHelper;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\AttributesBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "phong_kham_khuyen_mai".
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property int $type
 * @property int $date_start Ngày bắt đầu
 * @property int $date_end Ngày kết thúc
 * @property string $description
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class PhongKhamKhuyenMai extends \yii\db\ActiveRecord
{
    const STATUS_PUBLISHED = 1;
    const TYPE_CURENCY = '1';
    const TYPE_PERCENT = '2';
    const TYPE = [
        self::TYPE_CURENCY => 'đ',
        self::TYPE_PERCENT => '%'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phong_kham_khuyen_mai';
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true, //only create 1
                'ensureUnique' => true, //
                'value' => function () {
                    return MyHelper::createAlias($this->name);
                }
            ],
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
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['price'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['price'],
                ],
                'value' => function () {
                    return str_replace(['.', ','], '', $this->price);
                },
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_start'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_start'],
                ],
                'value' => function () {
                    if ($this->date_start == null) {
                        return null;
                    }
                    return strtotime($this->date_start);
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['date_end'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['date_end'],
                ],
                'value' => function () {
                    if ($this->date_end == null) {
                        return null;
                    }
                    return strtotime($this->date_end);
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['code'],
                ],
                'value' => function () {
                    if ($this->code == null) {
                        return MyHelper::randomString(10);
                    }
                    return $this->code;
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['type'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['type'],
                ],
                'value' => function () {
                    if ($this->type == null || !array_key_exists($this->type, self::TYPE)) {
                        return self::TYPE_CURENCY;
                    }
                    return $this->type;
                }
            ]
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'date_start'], 'required'],
            [['code'], 'unique'],
            ['code', 'unique',
                'targetClass' => self::class,
                'message' => 'Code đã tồn tại',
                'filter' => function ($query) {
                    $query->andWhere(['not', ['id' => $this->primaryKey]]);
                }, 'when' => function ($model) {
                    return $model->code != true;
                },
            ],
            [['type'], 'integer'],
            [['remaining'], 'integer', 'min' => 0],
            [['description'], 'string'],
            [['name', 'code'], 'string', 'max' => 255],
            [['price'], function () {
                return is_numeric(str_replace(['.', ','], '', $this->price));
            }],
            [['date_end'], 'safe'],
        ];
    }

    public static function find()
    {
        return new PhongKhamKhuyenMaiQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Name'),
            'code' => Yii::t('backend', 'Code'),
            'remaining' => Yii::t('backend', 'Số lượng'),
            'price' => Yii::t('backend', 'Giá'),
            'type' => Yii::t('backend', 'Type'),
            'date_start' => Yii::t('backend', 'Ngày bắt đầu'),
            'date_end' => Yii::t('backend', 'Ngày kết thúc'),
            'description' => Yii::t('backend', 'Description'),
            'status' => Yii::t('backend', 'Status'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
        ];
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public static function getListKhuyenMai()
    {
        $cache = Yii::$app->cache;
        $key = 'get-list-khuyen-mai';
        $data = $cache->get($key);
        if ($data === false) {
            $result = self::find()->published()->all();
            foreach ($result as $khuyenmai) {
                $data[$khuyenmai->primaryKey] = [
                    'id' => $khuyenmai->primaryKey,
                    'name' => $khuyenmai->name,
                    'price' => $khuyenmai->price,
                    'type' => $khuyenmai->type
                ];
            }
            $cache->set($key, $data);
        }
        return $data;
    }
}
