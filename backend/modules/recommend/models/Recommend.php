<?php

namespace backend\modules\recommend\models;

use backend\modules\appmyauris\models\AppMyaurisGroupSanPham;
use backend\modules\appmyauris\models\AppMyaurisGroupSanPhamSearch;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\toothstatus\models\TinhTrangRang;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "recommend".
 *
 * @property int $id
 * @property string $gioi_tinh
 * @property string $nhom_tuoi
 * @property string $bo_cuc
 * @property string $tinh_trang_rang
 * @property string $mong_muon
 * @property string $phong_cach
 * @property string $giai_phap
 * @property string $san_pham
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $updated_at
 */
class Recommend extends ActiveRecord
{
    const GIOI_TINH_NAM = 1;
    const GIOI_TINH_NU = 0;

    public static function tableName()
    {
        return 'recommend';
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
            [['nhom_tuoi', 'bo_cuc', 'tinh_trang_rang', 'mong_muon', 'phong_cach', 'phan_loai', 'san_pham','tieu_de'], 'required'],
            [['created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['gioi_tinh', 'nhom_tuoi', 'bo_cuc', 'tinh_trang_rang', 'mong_muon', 'phong_cach', 'giai_phap', 'san_pham', 'benh_ly', 'phan_loai', 'vat_lieu', 'video','tieu_de','mo_ta'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gioi_tinh' => 'Giới Tính',
            'nhom_tuoi' => 'Nhóm Tuổi *',
            'bo_cuc' => 'Bố Cục *',
            'tinh_trang_rang' => 'Tình Trạng Răng *',
            'mong_muon' => 'Mong Muốn *',
            'phong_cach' => 'Phong Cách *',
            'giai_phap' => 'Phương Pháp *',
            'benh_ly' => 'Bệnh lý',
            'phan_loai' => 'Phân loại',
            'san_pham' => 'Sản Phẩm',
            'vat_lieu' => 'Vật Liệu',
            'tieu_de' => 'Tiêu đề',
            'mo_ta' => 'Mô tả',
            'video' => 'Video',
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

    public function getBoCuc()
    {
        $array = self::getListBoCuc();
        $list = [];
        if (!empty($this->bo_cuc) && is_array($this->bo_cuc)) {
            foreach ($this->bo_cuc as $item) {
                $list[] = $array[$item];
            }
        }
        return implode('<br>', $list);
    }

    public static function getListBoCuc()
    {
        return [
            1 => 'Còn răng',
            2 => 'Mất răng',
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

    public function getMongMuon()
    {
        $array = self::getListMongMuon();
        $list = [];
        if (!empty($this->mong_muon) && is_array($this->mong_muon)) {
            foreach ($this->mong_muon as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListMongMuon()
    {
        return [
            1 => 'Phục hồi chức năng',
            2 => 'Thẩm mỹ'
        ];
    }

    public function getBenhLy()
    {
        $array = self::getListBenhLy();
        $list = [];
        if (!empty($this->benh_ly) && is_array($this->benh_ly)) {
            foreach ($this->benh_ly as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListBenhLy()
    {
        return Yii::$app->params["tinh-trang-benh-nhan"];
    }

    public function getPhongCach()
    {
        $array = self::getListPhongCach();
        $list = [];
        if (!empty($this->phong_cach) && is_array($this->phong_cach)) {
            foreach ($this->phong_cach as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListPhongCach()
    {
        return [
            1 => 'Tự nhiên',
            2 => 'Cá tính',
            3 => 'Ấn tượng'
        ];
    }

    public function getGiaiPhap()
    {
        $array = self::getListGiaiPhap();
        $list = [];
        if (!empty($this->giai_phap) && is_array($this->giai_phap)) {
            foreach ($this->giai_phap as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : "";
            }
        }
        return implode('<br>', $list);
    }

    public static function getListGiaiPhap()
    {
        return [
            1 => 'Phục hình',
            2 => 'Chỉnh nha',
            3 => 'Phẩu thuật',
            4 => 'Hàm tháo lắp',
        ];

    }

    public function getPhanLoai()
    {
        $array = self::getListPhanLoai();
        $list = [];
        if (!empty($this->phan_loai) && is_array($this->phan_loai)) {
            foreach ($this->phan_loai as $item) {
                $list[] = isset($array[$item]) ? $array[$item] : $item ;
            }
            return implode('<br>', $list);
        }
        return isset($array[$this->phan_loai]) ? $array[$this->phan_loai] : "";
    }

    public static function getListPhanLoai()
    {
        return [
            1 => 'Classic',  //'Chi phí Tối ưu', Perfect
            2 => 'Excellent',  //'Chuyên môn Tuyệt đối',
//            3 => 'Tuyệt đối'
        ];
    }

    public function getSanPham()
    {
        $array = self::getListSanPham();
        $list = [];
        if (!empty($this->san_pham) && is_array($this->san_pham)) {
            foreach ($this->san_pham as $item) {
                $list[] = isset($array[$item]) ?$array[$item] : $item;
            }
        }
        return implode('<br>', $list);
    }

    public static function getListSanPham()
    {
        $listModel = AppMyaurisGroupSanPham::find()->where(['status' => 1])->all();
        return ArrayHelper::map($listModel, 'id', 'name');
    }

    public static function getListSanPhamOLD()
    {
        return ArrayHelper::map(PhongKhamSanPham::getSanPham(), 'id', 'name');
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

// https://database.guide/json_search-find-the-path-to-a-string-in-a-json-document-in-mysql/
    public function recommend($post)
    {
        $query = $this->createQueryRecommend($post);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

//SELECT   *   FROM `recommend`
//WHERE JSON_SEARCH(nhom_tuoi, 'all', 2) is not null
//AND (JSON_SEARCH(tinh_trang_rang, 'all', 4) is not null
//OR JSON_SEARCH(tinh_trang_rang, 'all', 1) is not null )

    public function createQueryRecommend($post)
    {
        $query = Recommend::find();

//        Yii::warning($post);
//        if($port['gioi_tinh']){
//            $query->where("JSON_SEARCH(gioi_tinh, 'all', ".$port['gioi_tinh'].") is not null");
//        }
        if (!empty($post['nhom_tuoi'])) {
            $query->where("JSON_SEARCH(nhom_tuoi, 'all', " . $post['nhom_tuoi'] . ") is not null");
        }

        if (!empty($post['bo_cuc'])) {
            $query->andwhere("JSON_SEARCH(bo_cuc, 'all', " . $post['bo_cuc'] . ") is not null");
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

        if (!empty($post['mong_muon']) && is_array($post['mong_muon'])) {
            $array_mong_muon = $post['mong_muon'];
            $aList = [];
            foreach ($array_mong_muon as $value) {
                $aList[] = "JSON_SEARCH(mong_muon, 'all', " . $value . ") is not null";
            }
            $stringQuery = " ( " . implode(" OR ", $aList) . " ) ";
            $query->andWhere($stringQuery);
        }

        // 26-5-2020
        // an ngay 5-6-2020
        // if (!empty($post['giai_phap']) && !is_array($post['giai_phap']) ) { // lable : Phuong Phap
        //     $query->andwhere("JSON_SEARCH(giai_phap, 'all', " . $post['giai_phap'] . ") is not null");
        // }

        // if (!empty($post['giai_phap']) && is_array($post['giai_phap']) ) { // lable : Phuong Phap
        //     $array_giai_phap = $post['giai_phap'];
        //     $aList = [];
        //     foreach ($array_giai_phap as $value) {
        //         $aList[] = "JSON_SEARCH(giai_phap, 'all', " . $value . ") is not null";
        //     }
        //     $stringQuery = " ( " . implode(" OR ", $aList) . " ) ";
        //     $query->andWhere($stringQuery);
        // }

        if (!empty($post['phong_cach']) && !is_array($post['phong_cach']) ) {
            $query->andwhere("JSON_SEARCH(phong_cach, 'all', " . $post['phong_cach'] . ") is not null");
        }

        if (!empty($post['phong_cach']) && is_array($post['phong_cach']) ) { // lable : Phuong Phap
            $array_phong_cach = $post['phong_cach'];
            $aList = [];
            foreach ($array_phong_cach as $value) {
                $aList[] = "JSON_SEARCH(phong_cach, 'all', " . $value . ") is not null";
            }
            $stringQuery = " ( " . implode(" OR ", $aList) . " ) ";
            $query->andWhere($stringQuery);
        }

        return $query;
    }
}
