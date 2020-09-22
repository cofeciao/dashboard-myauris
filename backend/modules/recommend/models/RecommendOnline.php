<?php

namespace backend\modules\recommend\models;

use backend\modules\appmyauris\models\AppMyaurisGroupSanPham;
use backend\modules\toothstatus\models\TinhTrangRang;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "recommend_online".
 *
 * @property int $id
 * @property array $gioi_tinh
 * @property array $nhom_tuoi
 * @property array $tinh_trang_rang
 * @property array $khach_quan_tam
 * @property array $san_pham
 * @property string $hinh_anh
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class RecommendOnline extends \yii\db\ActiveRecord
{
    const GIOI_TINH_NAM = 1;
    const GIOI_TINH_NU = 0;

    public static function tableName()
    {
        return 'recommend_online';
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
            ]
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gioi_tinh', 'nhom_tuoi', 'tinh_trang_rang', 'khach_quan_tam', 'san_pham', 'tin_nhan'], 'safe'],
            [['hinh_anh'], 'string'],
            [['created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gioi_tinh' => 'Giới tính',
            'nhom_tuoi' => 'Nhóm tuổi',
            'tinh_trang_rang' => 'Tình trạng răng',
            'khach_quan_tam' => 'Khách quan tâm',
            'san_pham' => 'Sản Phẩm',
            'hinh_anh' => 'Hình Ảnh',
            'tin_nhan' => 'Mẫu Tin Nhắn',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGioiTinh()
    {
        $array = self::getListGioiTinh();
        $list = [];
        if (!empty($this->gioi_tinh) && is_array($this->gioi_tinh)) {
            foreach ($this->gioi_tinh as $item) {
                $list[] = $array[$item];
            }
        }
        return implode('<br>', $list);
    }

    public static function getListGioiTinh()
    {
        return [
            self::GIOI_TINH_NAM => 'Nam',
            self::GIOI_TINH_NU => 'Nữ',
        ];
    }

    public function getNhomTuoi()
    {
        $array = self::getListNhomTuoi();
        $list = [];
        if (!empty($this->nhom_tuoi) && is_array($this->nhom_tuoi)) {
            foreach ($this->nhom_tuoi as $item) {
                $list[] = $array[$item];
            }
        }
        return implode('<br>', $list);
    }

    public static function getListNhomTuoi()
    {
        return [
            1 => '< 35 tuổi',
            2 => '>= 35 tuổi',
            3 => '>= 50 tuổi',
        ];
    }

    public function getTinhTrangRang()
    {
        $array = self::getListTinhTrangRang();
        $list = [];
        if (!empty($this->tinh_trang_rang) && is_array($this->tinh_trang_rang)) {
            foreach ($this->tinh_trang_rang as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListTinhTrangRang()
    {
        return ArrayHelper::map(TinhTrangRang::getListTinhTrangRang(), 'id', 'name');
    }

    public function getKhachQuanTam()
    {
        $array = self::getListKhachQuanTam();
        $list = [];
        if (!empty($this->khach_quan_tam) && is_array($this->khach_quan_tam)) {
            foreach ($this->khach_quan_tam as $item) {
                $list[] = $array[$item];
            }
        }
        return implode('<br>', $list);
    }

    public static function getListKhachQuanTam()
    {
        return [
            1 => 'Răng Sứ',
            2 => 'Niềng',
            3 => 'Implant',
        ];
    }

    public function getSanPham()
    {
        $array = self::getListSanPham();
        $list = [];
        if (!empty($this->san_pham) && is_array($this->san_pham)) {
            foreach ($this->san_pham as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListSanPham()
    {
        $listModel = AppMyaurisGroupSanPham::find()->where(['status' => 1])->all();
        return ArrayHelper::map($listModel, 'id', 'name');
    }

    public function getUserCreatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function getUserUpdatedBy($id)
    {
        if ($id == null)
            return null;
        $user = UserProfile::find()->where(['user_id' => $id])->one();
        return $user;
    }

    public function recommend($post)
    {
        $query = $this->createQueryRecommendOnline($post);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function createQueryRecommendOnline($post)
    {
        $query = RecommendOnline::find();

               Yii::warning($post);
        if ($post['gioi_tinh']) {
            $query->where("JSON_SEARCH(gioi_tinh, 'all', " . $post['gioi_tinh'] . ") is not null");
        }
        if (!empty($post['nhom_tuoi'])) {
            $query->where("JSON_SEARCH(nhom_tuoi, 'all', " . $post['nhom_tuoi'] . ") is not null");
        }

        if (!empty($post['tinh_trang_rang']) && is_array($post['tinh_trang_rang'])) {
            $array_tinh_trang_rang = $post['tinh_trang_rang'];
            $aList = [];
            foreach ($array_tinh_trang_rang as $value) {
                $aList[] = "JSON_SEARCH(tinh_trang_rang, 'all', " . $value . ") is not null";
            }
            $stringQuery = " ( " . implode(" OR ", $aList) . " ) ";
            $query->andWhere($stringQuery);
        }

        if (!empty($post['khach_quan_tam']) && is_array($post['khach_quan_tam'])) {
            $array_khach_quan_tam = $post['khach_quan_tam'];
            $aList = [];
            foreach ($array_khach_quan_tam as $value) {
                $aList[] = "JSON_SEARCH(khach_quan_tam, 'all', " . $value . ") is not null";
            }
            $stringQuery = " ( " . implode(" OR ", $aList) . " ) ";
            $query->andWhere($stringQuery);
        }

        return $query;
    }
}
