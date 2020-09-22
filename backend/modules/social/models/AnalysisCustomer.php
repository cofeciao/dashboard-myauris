<?php


namespace backend\modules\social\models;

use backend\models\CustomerModel;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use yii\db\ActiveRecord;
use common\helpers\MyHelper;

class AnalysisCustomer extends ActiveRecord
{
    const STATUS = 'Trạng thái';
    const DAT_HEN = 'Đặt hẹn';
    const COME = 'Sau khi thăm khám';

    public $range = null;
    public $property = null;
    public $source = null;
    public $analysis = null;

    public function behaviors()
    {
        return [];
    }

    public function rules()
    {
        return [
            [['analysis', 'property'], 'required', 'message' => 'Vui lòng chọn ít nhất một thuộc tính'],
            [['source', 'range',], 'safe']
        ];
    }

    public static function getAnalysisCustomer()
    {
        $data = AnalysisCustomer::find()->orderBy(['created_at' => 'SORT_DESC'])->all();
        if (isset($data)) {
            return $data;
        }
    }

    public function search($params)
    {
        $data = [];
        $labels = [];
        $source = null;
        $datasets = null;
        $from = null;
        $to = null;
        $this->load($params);
        if (isset($this->source) && $this->source != '') {
            $source = $this->source;
        }
        if (isset($this->range) && $this->range != '') {
            if ($this->range == 'tw') {
                $from = strtotime(date('d-m-Y') . '-7 days');
                $to = strtotime(date('d-m-Y'));
            }
            if ($this->range == 'tm') {
                $from = strtotime(date('1-m-Y'));
                $to = strtotime(date('d-m-Y'));
            }
            if ($this->range == 'lm') {
                $from = strtotime(date('1-m-Y') . '-1 months');
                $to = strtotime(date('d-m-Y'). '-1 months');
            }
            if ($this->range == 'ly') {
                $from = strtotime(date('1-1-Y') . '-1 year');
                $to = strtotime(date('d-12-Y'). '-1 year');
            }
        }
        if (isset($this->property) && $this->property != '') {
            if (isset($this->analysis) && $this->analysis != '') {
                $analysis = $this->analysis;
                $property = $this->property;
                $datasets = self::getDataSetsAnalysis($source, $analysis, $property, $from, $to);
                foreach ($property as $key) {
                    $string = MyHelper::remove_numbers($key);
                    $id = MyHelper::get_numbers($key);
                    if ($string == MyHelper::createAlias(self::STATUS)) {
                        $labels[] = self::getOneStatusName($id);
                    }
                    if ($string == MyHelper::createAlias(self::DAT_HEN)) {
                        $labels[] = self::getOneDatHenName($id);
                    }
                    if ($string == MyHelper::createAlias(self::COME)) {
                        $labels[] = self::getOneComeName($id);
                    }
                }
            }
        }
        $data['labels'] = $labels;
        $data['datasets'] = $datasets;
        return $data;
    }

    public function getDataSetsAnalysis($source = null, $analysis, $property, $from = null, $to = null)
    {
        $datasets = [];
        $color = ['#6610f2', '#6f42c1', '#FFA87D', '#16D39A', '#00B5B8', '#e83e8c', '#FFA87D', '#ffc107', '#FF7588', '#BABFC7', '#20c997'];
        $i = 0;
        $query = CustomerModel::find();
        foreach ($analysis as $analysis_id) {
            $dat = [];
            $analysis_name = self::getOneAnalysisName($analysis_id);
            foreach ($property as $property_id) {
                $id = MyHelper::get_numbers($property_id);
                $string = MyHelper::remove_numbers($property_id);
                $field = null;
                if ($string == MyHelper::createAlias(self::STATUS)) {
                    $field = 'status';
                }
                if ($string == MyHelper::createAlias(self::DAT_HEN)) {
                    $field = 'dat_hen';
                }
                if ($string == MyHelper::createAlias(self::COME)) {
                    $field = 'customer_come_time_to';
                }
                $sub_analysis_name = self::getOneAnalysisName($analysis_id);
                $query->Where(['dep365_customer_online.' . $field => $id]);
                if ($source != null) {
                    $query->andWhere([Dep365CustomerOnline::tableName() . '.nguon_online' => $source]);
                };
                if ($from != null && $to != null) {
                    $query->andWhere(['between', Dep365CustomerOnline::tableName() . '.ngay_tao', $from, $to]);
                }
                $dat[] = $query->andWhere(['like', Dep365CustomerOnline::tableName() . '.note', $sub_analysis_name])->count();
            }
            $i++;
            $datasets[] = [
                'label' => $analysis_name,
                'data' => $dat,
                'backgroundColor' => $color[$i],
            ];
        }
        return $datasets;
    }

    public function getOneAnalysisName($id)
    {
        $analysis = self::findOne($id);
        if (isset($analysis)) {
            return $analysis->name;
        }
    }

    public function getOneStatusName($id)
    {
        $status = Dep365CustomerOnlineStatus::findOne($id);
        if (isset($status)) {
            return $status->name;
        }
    }

    public function getOneDatHenName($id)
    {
        $dat_hen = Dep365CustomerOnlineDathenStatus::findOne($id);
        if (isset($dat_hen)) {
            return $dat_hen->name;
        }
    }

    public function getOneComeName($id)
    {
        $come = Dep365CustomerOnlineCome::findOne($id);
        if (isset($come)) {
            return $come->name;
        }
    }
}
