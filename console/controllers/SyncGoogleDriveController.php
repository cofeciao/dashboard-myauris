<?php

namespace console\controllers;

use backend\components\GapiComponent;
use backend\modules\clinic\models\CustomerImages;
use backend\modules\customer\models\Dep365CustomerOnline;
use common\helpers\MyHelper;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Exception;

class SyncGoogleDriveController extends Controller
{
    private $array_class = [
        'chup-hinh' => 'backend\modules\clinic\models\PhongKhamChupHinh',
        'chup-banh-moi' => 'backend\modules\clinic\models\PhongKhamChupBanhMoi',
        'chup-cui' => 'backend\modules\clinic\models\PhongKhamChupCui',
        'chup-ket-thuc' => 'backend\modules\clinic\models\PhongKhamChupFinal',
        'thiet-ke-nu-cuoi' => 'backend\modules\clinic\models\PhongKhamHinhTknc',
        'uom_rang_1' => 'backend\modules\clinic\models\PhongKhamUomRang1',
        'uom_rang_2' => 'backend\modules\clinic\models\PhongKhamUomRang2',
        'hinh_final' => 'backend\modules\clinic\models\PhongKhamHinhFinal',
        'dental-form' => 'backend\modules\clinic\models\PhongKhamDentalForm',
    ];
    private $array_controller = [
        'chup-hinh' => 'backend\modules\clinic\controllers\ChupHinhController',
        'chup-banh-moi' => 'backend\modules\clinic\controllers\ChupBanhMoiController',
        'chup-cui' => 'backend\modules\clinic\controllers\ChupCuiController',
        'chup-ket-thuc' => 'backend\modules\clinic\controllers\ChupFinalController',
        'thiet-ke-nu-cuoi' => 'backend\modules\clinic\controllers\TkncController',
        'uom_rang_1' => 'backend\modules\clinic\controllers\UomRang1Controller',
        'uom_rang_2' => 'backend\modules\clinic\controllers\UomRang2Controller',
        'hinh_final' => 'backend\modules\clinic\controllers\HinhFinalController',
        'dental-form' => 'backend\modules\clinic\controllers\DentalFormController',
    ];
    private $array_folder = [
        'chup-hinh' => 'Chụp hình',
        'chup-banh-moi' => 'Chụp banh môi',
        'chup-cui' => 'Chụp cùi',
        'chup-ket-thuc' => 'Chụp kết thúc',
        'thiet-ke-nu-cuoi' => 'Thiết kế nụ cười',
        'uom_rang_1' => 'Ướm răng 1',
        'uom_rang_2' => 'Ướm răng 2',
        'hinh_final' => 'Hình final',
        'dental-form' => 'Dental form',
    ];
    private $array_flip = [];

    public function init()
    {
        set_time_limit(600);
        return parent::init(); // TODO: Change the autogenerated stub
    }

    public function __construct(string $id, Module $module, array $config = [])
    {
        $this->array_flip = array_flip(\Yii::$app->params['chup-hinh-catagory']);
        parent::__construct($id, $module, $config);
    }

    public function actionCreateGoogleDriveFolder()
    {
        $listImage = CustomerImages::find()
            ->joinWith(['customerHasOne'])
            ->select([
                CustomerImages::tableName() . '.customer_id',
                CustomerImages::tableName() . '.catagory_id',
            ])
            ->where('image IS NOT NULL AND google_id IS NULL')
            ->groupBy([
                CustomerImages::tableName() . '.customer_id',
                CustomerImages::tableName() . '.catagory_id',
            ])
            ->all();
        if (is_array($listImage) && count($listImage) > 0) {
            $begin = strtotime(date('d-m-Y 00:00:00'));
            $end = $begin + 86399;
            $service = GapiComponent::getService();
            foreach ($listImage as $image) {
                if (array_key_exists($image->catagory_id, $this->array_flip)) {
                    $catagory = $this->array_flip[$image->catagory_id];
                    if (array_key_exists($catagory, $this->array_class)) {
                        $class = $this->array_class[$catagory];
                        $controller = $this->array_controller[$catagory];
                        $customer_name = $image->customerHasOne->full_name != null ? $image->customerHasOne->full_name : ($image->customerHasOne->forename != null ? $image->customerHasOne->forename : $image->customerHasOne->name);
                        $checkGDriveFolder = $class::find()
                            ->where(['customer_id' => $image->customer_id])
                            ->andWhere(['BETWEEN', 'created_at', $begin, $end
                            ])->one();
                        if ($checkGDriveFolder == null) {
                            $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer_name) . '-' . $image->customer_id, date('d-m-Y'), $controller::FOLDER);
                            $checkGDriveFolder = new $class();
                            $checkGDriveFolder->customer_id = $image->customer_id;
                            $checkGDriveFolder->folder_id = $gDriveFolder;
                            try {
                                $checkGDriveFolder->save();
                                echo 'Tạo mới folder ' . $this->array_folder[$catagory] . ' (' . $gDriveFolder . ') cho khách hàng ' . $image->customer_id . '<br/>';
                            } catch (Exception $ex) {
                            }
                        } else {
                            $getFolder = GapiComponent::getFile($service, $checkGDriveFolder->folder_id);
                            if ($getFolder == null) {
                                $gDriveFolder = GapiComponent::initSubFolderForCustomerByDate($service, MyHelper::createAlias($customer_name) . '-' . $image->customer_id, date('d-m-Y'), $controller::FOLDER);
                                $checkGDriveFolder->folder_id = $gDriveFolder;
                                try {
                                    $checkGDriveFolder->save();
//                                    echo 'Cập nhật folder ' . $this->array_folder[$catagory] . ' (' . $gDriveFolder . ') cho khách hàng ' . $image->customer_id . '<br/>';
                                } catch (Exception $ex) {
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    public function actionSyncGoogleDrive()
    {
        $listImage = CustomerImages::find()
            ->select([
                CustomerImages::tableName() . '.id',
                CustomerImages::tableName() . '.customer_id',
                Dep365CustomerOnline::tableName() . '.slug',
                CustomerImages::tableName() . '.catagory_id',
                CustomerImages::tableName() . '.google_id',
                CustomerImages::tableName() . '.image',
                CustomerImages::tableName() . '.created_at'
            ])
            ->joinWith(['customerHasOne'])
            ->where('image IS NOT NULL AND google_id IS NULL')
            ->all();
        if (is_array($listImage) && count($listImage) > 0) {
            $begin = strtotime(date('d-m-Y 00:00:00'));
            $end = $begin + 86399;
            $service = GapiComponent::getService();
            foreach ($listImage as $image) {
                if (array_key_exists($image->catagory_id, $this->array_flip) && $image->image != null) {
                    $catagory = $this->array_flip[$image->catagory_id];
                    $alias = '@backend/web/uploads/customer/' . $image->customerHasOne->slug . '-' . $image->customer_id . '/' . $this->array_flip[$image->catagory_id];
                    if (array_key_exists($catagory, $this->array_class) && file_exists(\Yii::getAlias($alias) . '/' . $image->image)) {
                        $class = $this->array_class[$catagory];
                        $checkGDriveFolder = $class::find()
                            ->where(['BETWEEN', 'created_at', $begin, $end])
                            ->andWhere(['customer_id' => $image->customer_id])
                            ->one();
                        if ($checkGDriveFolder != null) {
                            $gDriveFolder = $checkGDriveFolder->folder_id;
                            $idImage = GapiComponent::uploadImageToDrive($service, $image->image, $alias, $gDriveFolder);
                            try {
                                $image->google_id = $idImage;
                                $image->save();
//                                echo 'Cập nhật google_id ' . $idImage . ' hình ' . $this->array_folder[$catagory] . ' cho khách hàng ' . $image->customer_id . '<br/>';
                            } catch (Exception $ex) {
                            }
                        }
                    }
                }
            }
        }
        return true;
    }
}
