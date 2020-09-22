<?php

namespace backend\modules\chi\models;

use backend\modules\chi\models\query\DeadlineQuery;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "thuchi_deadline".
 *
 * @property int $id
 * @property int $id_tieu_chi
 * @property int $thoi_gian_bat_dau
 * @property int $thoi_gian_ket_thuc
 * @property int $created_at
 * @property int $created_by
 */
class Deadline extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'thuchi_deadline';
    }

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['thoi_gian_bat_dau'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['thoi_gian_bat_dau']
                ],
                'value' => function () {
                    if ($this->thoi_gian_bat_dau == null) {
                        return time();
                    }
                    return strtotime($this->thoi_gian_bat_dau);
                }
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['thoi_gian_ket_thuc'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['thoi_gian_ket_thuc'],
                ],
                'value' => function () {
                    if ($this->thoi_gian_ket_thuc == null) {
                        return time();
                    }
                    return strtotime($this->thoi_gian_ket_thuc);
                }
            ],
        ];
    }

    public static function find()
    {
        return new DeadlineQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tieu_chi'], 'required'],
            [['id_tieu_chi', 'created_at', 'created_by'], 'integer'],
            [['thoi_gian_bat_dau', 'thoi_gian_ket_thuc'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'id_tieu_chi' => Yii::t('backend', 'Id Tieu Chi'),
            'thoi_gian_bat_dau' => Yii::t('backend', 'Thoi Gian Bat Dau'),
            'thoi_gian_ket_thuc' => Yii::t('backend', 'Thoi Gian Ket Thuc'),
            'created_at' => Yii::t('backend', 'Created At'),
            'created_by' => Yii::t('backend', 'Created By'),
        ];
    }

    public static function getOneTimeDeadline($id)
    {
        $sql = self::find()->where(['id_tieu_chi' => $id])->orderBy(['created_at' => SORT_DESC])->limit(1);
        $time = $sql->one();
        return $time;
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null) {
            return null;
        }
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getTieuchiHasOne()
    {
        return $this->hasOne(ThuchiTieuChi::class, ['id' => 'id_tieu_chi']);
    }

    public static function getTieuChi($id){
        return ThuchiTieuChi::findOne(['id' => $id]);
    }

}
