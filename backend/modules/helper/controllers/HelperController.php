<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 26-Apr-19
 * Time: 3:59 PM
 */

namespace backend\modules\helper\controllers;

use backend\components\GapiComponent;
use backend\components\MyController;
use backend\modules\helper\models\HelperModel;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\Response;

class HelperController extends MyController
{
    public function actionIndex()
    {
        $model = new HelperModel();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionStrtotime()
    {
        if (\Yii::$app->request->isAjax) {
            $date = \Yii::$app->request->post('str');
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['date' => strtotime($date)];
        }
    }

    public function actionDatetoint()
    {
        if (\Yii::$app->request->isAjax) {
            $date = \Yii::$app->request->post('int');
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return ['int' => date('d-m-Y H:i:s', $date)];
        }
    }

    public function actionExportDatabase()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 3600);
            ini_set('default_socket_timeout', 6000);
            set_time_limit(600);

            $dsn = str_replace('mysql:', '', \Yii::$app->db->dsn);
            $temp = explode(';', $dsn);
            $data = [];
            foreach ($temp as $tmp) {
                $arr = explode('=', $tmp);
                if (count($arr) == 2) {
                    $data[$arr[0]] = $arr[1];
                }
            }

            $database = isset($data['dbname']) ? $data['dbname'] : null;
            $port = isset($data['port']) ? $data['port'] : 3306;
            $host = isset($data['host']) ? $data['host'] : null;
            $user = \Yii::$app->db->username;
            $pass = \Yii::$app->db->password;
            if (in_array(null, [$database, $host, $user, $pass])) {
                return [
                    'code' => 404,
                    'msg' => 'Không thể kết nối database!',
                ];
            }
            $alias = '@backend/web';
            $path_alias = 'local/file';
            $path = \Yii::getAlias($alias . '/' . $path_alias);
            $file = date('Y-m-d') . '-dashboard' . (CONSOLE_HOST === 1 ? '-local' : '') . '.sql';
            $dir = $path . '/' . $file;

            try {
                exec("mysqldump --user={$user} --password={$pass} --host={$host} --port={$port} {$database} --result-file={$dir} 2>&1", $output);
            } catch (Exception $ex) {
                $output = null;
            }
            if ($output === null) {
                return [
                    'code' => 400,
                    'msg' => 'Backup thất bại!'
                ];
            }
            \Yii::$app->cache->set('last-database-backup', [
                'file_url' => FRONTEND_HOST_INFO . '/' . $path_alias . '/' . $file,
                'file_name' => $file
            ]);
            return [
                'code' => 200,
                'msg' => 'Backup thành công!',
                'file_url' => FRONTEND_HOST_INFO . '/' . $path_alias . '/' . $file,
                'file_name' => $file
            ];
        }
    }
}
