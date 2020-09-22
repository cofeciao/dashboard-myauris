<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 15-Jan-19
 * Time: 10:27 AM
 */

namespace console\controllers;

use backend\models\CustomerElastic;
use backend\models\CustomerModel;
use yii\console\Controller;
use yii\elasticsearch\Connection;
use yii\elasticsearch\Exception;

class ElasticController extends Controller
{
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
}
