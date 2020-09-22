<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17-Apr-19
 * Time: 2:56 PM
 */
namespace backend\modules\helper\controllers;

use backend\modules\helper\models\search\FbSearch;
use Yii;

class LinkFaceController extends \backend\components\MyController
{
    public function actionIndex()
    {
        $searchModel = new FbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
