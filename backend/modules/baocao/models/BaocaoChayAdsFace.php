<?php

namespace backend\modules\baocao\models;

use backend\models\baocao\BaocaoChayAdsFaceModel;
use common\models\User;
use Yii;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;

/**
 * This is the model class for table "baocao_chay_ads_face".
 *
 * @property int $id
 * @property int $don_vi
 * @property string $so_tien_chay
 * @property int $hien_thi
 * @property int $tiep_can
 * @property int $binh_luan
 * @property int $tin_nhan
 * @property int $page_chay
 * @property int $tuong_tac
 * @property int $so_dien_thoai
 * @property int $goi_duoc
 * @property int $lich_hen
 * @property int $khach_den
 * @property string $money_hienthi
 * @property string $money_tiepcan
 * @property string $money_binhluan
 * @property string $money_tinnhan
 * @property string $money_tuongtac
 * @property string $money_sodienthoai
 * @property string $money_goiduoc
 * @property string $money_lichhen
 * @property string $money_khachden
 * @property int $status
 * @property int $ngay_chay
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class BaocaoChayAdsFace extends BaocaoChayAdsFaceModel
{
    const CHAY_ADS = 'chay_ads';

    public $SDT;
    public $STC;
    public $TT;
    public $LL;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'baocao_chay_ads_face';
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
//            [
//                'class' => AttributeBehavior::class,
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'ngay_chay',
//                    ActiveRecord::EVENT_BEFORE_UPDATE => 'ngay_chay',
//                ],
//                'value' => function () {
//                    return strtotime($this->ngay_chay);
//                },
//            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hien_thi', 'tiep_can', 'page_chay'], 'required', 'on' => self::CHAY_ADS],
            [['location_id', 'so_tien_chay', 'binh_luan', 'tin_nhan', 'ngay_chay'], 'required'],
            [['money_hienthi', 'money_tiepcan', 'money_binhluan', 'money_tinnhan', 'money_tuongtac', 'money_sodienthoai', 'money_goiduoc', 'money_lichhen', 'money_khachden', 'location_id', 'don_vi', 'page_chay', 'tuong_tac', 'so_dien_thoai', 'goi_duoc', 'lich_hen', 'khach_den', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'san_pham'], 'integer'],
            [['so_tien_chay'], 'string', 'max' => 25],
            [['so_tien_chay', 'hien_thi', 'tiep_can', 'binh_luan', 'tin_nhan'], 'checkNumber'],
            [['ngay_chay', 'hien_thi', 'tiep_can', 'binh_luan', 'tin_nhan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'don_vi' => Yii::t('backend', 'Đơn vị'),
            'location_id' => Yii::t('backend', 'Khu vực'),
            'so_tien_chay' => Yii::t('backend', 'Số tiền chạy'),
            'hien_thi' => Yii::t('backend', 'Hiển thị'),
            'tiep_can' => Yii::t('backend', 'Tiếp cận'),
            'binh_luan' => Yii::t('backend', 'Bình luận'),
            'tin_nhan' => Yii::t('backend', 'Tin nhắn'),
            'page_chay' => Yii::t('backend', 'Page chạy'),
            'tuong_tac' => Yii::t('backend', 'Tương tác'),
            'so_dien_thoai' => Yii::t('backend', 'Số điện thoại'),
            'goi_duoc' => Yii::t('backend', 'Gọi được'),
            'lich_hen' => Yii::t('backend', 'Lịch hẹn'),
            'khach_den' => Yii::t('backend', 'Khách đến'),
            'money_hienthi' => Yii::t('backend', 'Giá hiển thị'),
            'money_tiepcan' => Yii::t('backend', 'Giá tiếp cận'),
            'money_binhluan' => Yii::t('backend', 'Giá bình luận'),
            'money_tinnhan' => Yii::t('backend', 'Giá tin nhắn'),
            'money_tuongtac' => Yii::t('backend', 'Giá tương tác'),
            'money_sodienthoai' => Yii::t('backend', 'Giá số điện thoại'),
            'money_goiduoc' => Yii::t('backend', 'Giá gọi được'),
            'money_lichhen' => Yii::t('backend', 'Giá lịch hẹn'),
            'money_khachden' => Yii::t('backend', 'Giá khách đến'),
            'status' => Yii::t('backend', 'Status'),
            'ngay_chay' => 'Ngày chạy',
            'san_pham' => 'Sản phẩm',
            'created_at' => Yii::t('backend', 'Created At'),
            'updated_at' => Yii::t('backend', 'Updated At'),
            'created_by' => Yii::t('backend', 'Created By'),
            'updated_by' => Yii::t('backend', 'Updated By'),
        ];
    }

    public function checkNumber($attribute, $params, $validator)
    {
        if (!$this->hasErrors()) {
            if (!is_numeric(str_replace([',', '.'], '', $this->$attribute))) {
                $this->addError($attribute, Yii::t('backend', $this->getAttributeLabel($attribute) . ' không đúng định dạng số'));
            }
        }
    }

    public function getSanPhamHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineDichVu::class, ['id' => 'san_pham']);
    }

    public function getLocationHasOne()
    {
        return $this->hasOne(BaocaoLocation::class, ['id' => 'location_id']);
    }

    public static function getDonViChayAdvertising()
    {
//        $cache = Yii::$app->cache;
//        $key = 'redis-get-nhan-vien-chay-advertising';
//        $data = $cache->get($key);
//
//        if ($data === false) {
        $ads = User::getNhanVienChayAdvertising();
        $data = [];
        foreach ($ads as $k => $item) {
            $data[$item->id] = $item->userProfile->fullname;
        }
//            $cache->set($key, $data);
//        }
        return $data;
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
}
