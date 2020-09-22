<?php


namespace backend\modules\social\controllers;

use backend\components\MyController;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use backend\modules\social\models\AnalysisCustomer;
use backend\modules\social\models\AnalysisModel;
use backend\modules\customer\models\search\Dep365CustomerOnlineNguonSearch;
use DateTime;
use common\helpers\MyHelper;
use yii\helpers\Url;
use yii\web\Response;

class AnalysisCustomerController extends MyController
{
    public function actionIndex()
    {
        $status = Dep365CustomerOnlineStatus::getStatusCustomerOnline();
        $source = Dep365CustomerOnlineNguonSearch::getNguonCustomerOnline();
        $analysis = AnalysisCustomer::getAnalysisCustomer();
        $dat_hen = Dep365CustomerOnlineDathenStatus::getDatHenStatus();
        $come = Dep365CustomerOnlineCome::getCustomerOnlineCome();
        $filter = [];
        $model = new AnalysisCustomer();
        $filter['source'] = $this->foreachData($source);
        $filter['property'][AnalysisCustomer::STATUS] = $this->foreachData($status, MyHelper::createAlias(AnalysisCustomer::STATUS));
        $filter['property'][AnalysisCustomer::DAT_HEN] = $this->foreachData($dat_hen, MyHelper::createAlias(AnalysisCustomer::DAT_HEN));
        $filter['property'][AnalysisCustomer::COME] = $this->foreachData($come, MyHelper::createAlias(AnalysisCustomer::COME));
        $filter['analysis'] = $this->foreachData($analysis);
        return $this->render(
            'index',
            [
                'filter' => $filter,
                'model' => $model,
            ]
        );
    }

    public function actionLoadData()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new AnalysisCustomer();
        $data = $model->search(\Yii::$app->request->queryParams);
        return $data;
    }
    public function actionCreate()
    {
        //lấy kết quả phân tính từ field dep365_customer_online.note;
        $model = new AnalysisModel();
        $model->deleteAll();
        foreach ($this->noteFieldAnalysis() as $item) {
            $model = new AnalysisModel();
            $model->name = ($item['name']);
            $model->save();
        }
        return $this->redirect(Url::toRoute('/social/analysis-customer'));
    }

    public function noteFieldAnalysis()
    {
        $data = \Yii::$app->db->createCommand('SELECT tbl.note as name, COUNT(*) AS count_note FROM 
            (SELECT note, REPLACE(note, \' \', \'\') AS note1 FROM `dep365_customer_online`) 
             AS tbl WHERE note !=\'\' GROUP BY tbl.note1 ORDER BY count_note DESC LIMIT 10')->queryAll();
        if (isset($data)) {
            return $data;
        }
    }

    protected function foreachData($data, $key = null)
    {
        $field = [];
        foreach ($data as $item) {
            $field[$key.$item->id] = $item->name;
        }
        return $field;
    }
    public function ageCalculator($dob)
    {
        if (!empty($dob)) {
            $birthdate = new DateTime($dob);
            $today   = new DateTime();
            $age = $birthdate->diff($today)->y;
            return $age;
        } else {
            return 0;
        }
    }
}
