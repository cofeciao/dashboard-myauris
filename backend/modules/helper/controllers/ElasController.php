<?php

namespace backend\modules\helper\controllers;

use backend\components\MyController;
use backend\models\CustomerElastic;
use backend\models\CustomerModel;
use backend\modules\helper\elastic\Customer;
use backend\modules\helper\elastic\ElasticSearchConnectionTest;
use backend\modules\helper\elastic\QueryTest;
use backend\modules\location\models\District;
use backend\modules\location\models\Province;
use Yii;
use yii\elasticsearch\Connection;
use yii\elasticsearch\Exception;
use yii\helpers\ArrayHelper;


class ElasController extends MyController
{
    public function actionIndex()
    {
        set_time_limit(0);
        return $this->render('index', [

        ]);
    }

    public function actionRun()
    {
        $connection = new Connection();
        $connection->open();
        $command = $connection->createCommand();
//        $command->flushIndex();
        if (Yii::$app->request->isAjax) {
            $from_id = Yii::$app->request->post('from_id');
            $to_id = Yii::$app->request->post('to_id');
            $listModel = CustomerModel::find()
                ->where(['>=', 'id', $from_id])
                ->andWhere(['<=', 'id', $to_id])
                ->all();
            // an hanh lawm luon
            $tableName = CustomerElastic::NAME_INDEX;
            $tableType = CustomerElastic::NAME_TYPE;
            foreach ($listModel as $model) {
                if ($command->exists($tableName, $tableType, $model->primaryKey)) {
                    $command->update($tableName, $tableType, $model->primaryKey, $model->attributes);
                } else {
                    $command->insert($tableName, $tableType, $model->attributes, $model->primaryKey);
                }
            }
            $command->flushIndex();

            // code chuan nhung ko work vi thu vien bi loi
//            foreach ($listModel as $model) {
//                $primaryKey = $model->primaryKey;
//                $attributes = $model->attributes;
//                if ($customer = CustomerElastic::get($primaryKey) !== null) {
//                    $customer = CustomerElastic::findOne($primaryKey);
//                    $customer->setAttributes($attributes, false);
//                    $customer->update(false);
//                } else {
//                    $customer = new CustomerElastic();
//                    $customer->primaryKey = $primaryKey;
//                    $customer->setAttributes($attributes, false);
//                    $customer->insert(false);
//                }
//                $command->flushIndex();
//            }
            return json_encode([
                'msg' => "da up het",
            ]);
        }
    }

    public function actionCreate()
    {
        $connection = new Connection();
        $connection->open();
        $command = $connection->createCommand();
        $command->insert('customer-elastics', 'customer-elastics', []);
        $command->flushIndex();
    }

    // for cronjob
    public function actionUpdate()
    {
        $command = $this->connect();
        $time = time() - 4600;
        $listModel = CustomerModel::find()
            ->where(['>=', 'updated_at', $time])
            ->all();
        // an hanh lawm luon
        $tableName = CustomerElastic::NAME_INDEX;
        $tableType = CustomerElastic::NAME_TYPE;

        if (count($listModel) > 0) {
            if ($command !== false) {
                foreach ($listModel as $model) {
                    if ($command->exists($tableName, $tableType, $model->primaryKey)) {
                        $command->update($tableName, $tableType, $model->primaryKey, $model->attributes);
                    } else {
                        $command->insert($tableName, $tableType, $model->attributes, $model->primaryKey);
                    }
                }
                $command->flushIndex();
            }
        }
        return true;
    }

    protected function connect()
    {
        try {
            $connection = new Connection();
            $connection->open();
            $command = $connection->createCommand();
        } catch (Exception $e) {
            return false;
        }
        return $command;
    }

    // search for header

    public function actionSearch()
    {
        $province = ArrayHelper::map(Province::getProvince(), 'id', 'name');
        $district = ArrayHelper::map(District::getDistrict(), 'id', 'name');

        $result = $customers = [];
        $type_search = '';
        $command = $this->connect();
        if (Yii::$app->request->isAjax) {
            $search = Yii::$app->request->post('search');

            if ($command !== false) {
//            if (false) {
                $query = new \yii\elasticsearch\Query();
                $query->from(CustomerElastic::NAME_INDEX, CustomerElastic::NAME_TYPE);
                $query->query([
                    'bool' => [
                        'filter' => [
                            'multi_match' => [
//                                'type' => 'best_fields',
                                "type" => "phrase_prefix",
                                'query' => $search,
                                'lenient' => true
                            ],
//                            "multi_match" => [
//                                'query' => $search,
//                                'fields' => ['id','customer_code','full_name','forename','name','phone']
//                            ],
                        ],
                        'should' => [],
                        'must_not' => [],
                    ],
                ]);
                $query->limit(12);   # !!! This ok. before update work as expected for my elastic engine version
//                $query->orderBy(['id' => SORT_DESC]);
                $customers = $query->all();

                foreach ($customers as $customer) {
                    $item = isset($customer['_source']) ? $customer['_source'] : "";
                    $item['province'] = isset($province[$item['province']]) ? $province[$item['province']] : ""  ;
                    $item['district'] = isset($district[$item['district']]) ? $district[$item['district']] : ""  ;
                    $result[] = $item;
                }
                $type_search = 'Elastic';
            }

            if(count($result) == 0){
                $customers = $this->getDataOnModel($search);
                foreach ($customers as $customer) {
                    $item =  $customer->attributes;
                    $item['province'] = isset($province[$item['province']]) ? $province[$item['province']] : ""  ;
                    $item['district'] = isset($district[$item['district']]) ? $district[$item['district']] : ""  ;
                    $result[] = $item;
                }
                $type_search = 'Ajax';
            }

        }
        return json_encode([
            'data' => $result,
            'type_search' => $type_search
        ]);
    }

    public function getDataOnModel($search)
    {
        $query = CustomerModel::find();
        $query->orFilterWhere(['like', 'id', $search])
            ->orFilterWhere(['like', 'full_name', $search])
            ->orFilterWhere(['like', 'forename', $search])
            ->orFilterWhere(['like', 'name', $search])
            ->orFilterWhere(['like', 'phone', $search])
            ->orFilterWhere(['like', 'address', $search])
            ->orFilterWhere(['like', 'customer_code', $search]);
        $query->limit(12);
//            ->groupBy('id')
//            ->orderBy(['id' => SORT_DESC]);
        return $query->all();
    }


}
