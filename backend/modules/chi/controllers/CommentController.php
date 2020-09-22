<?php

namespace backend\modules\chi\controllers;

use backend\components\MyController;
use backend\modules\chi\models\Comment;
use backend\modules\chi\models\DeXuatChi;
use backend\modules\chi\models\DeXuatChiModel;
use backend\modules\general\models\Dep365Notification;
use common\helpers\MyHelper;
use yii\bootstrap\ActiveForm;
use yii\db\Query;
use yii\debug\models\search\Db;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Response;

class CommentController extends MyController
{
    public function actionIndex()
    {
        $model = new Comment(1);
        $listComments = Comment::find()->where(['id_de_xuat_chi' => 1])->all();
        return $this->render('index', [
            'model' => $model,
            'listComments' => $listComments
        ]);
    }

    public function actionValidateComment($id = null, $commentTable = null)
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Comment($id, $commentTable);
            if ($model->load(\Yii::$app->request->post())) {
                $validate = ActiveForm::validate($model);
                return $validate;
            }
        }
    }


    public function actionSubmitComment($id = null, $commentTable = null)
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new Comment($id, $commentTable);

            if (!$model->load(\Yii::$app->request->post()) || !$model->validate() || !$model->save()) {
                return [
                    'code' => 400,
                    'msg' => 'Có lỗi xảy ra',
                    'error' => array_merge($model->getErrors())
                ];
            }
            $this->notifyChangeOnComment($model->id_de_xuat_chi, $model->comment);
            return [
                'code' => 200,
                'msg' => 'Đã lưu bình luận'
            ];
        }
    }

    public function notifyChangeOnComment($id, $comment)
    {
        $model = DeXuatChiModel::findOne(['id' => $id]);

        $current_user = \Yii::$app->user->id;

        //nguoi de xuat
        $notifId = Dep365Notification::quickCreate([
            'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
            'icon' => 'ft-alert-circle',
            'description' => $comment,
            'is_new' => 1,
            'is_bg' => 3,
            'status' => 1,
            'for_who' => 'user-' . $model->created_by
        ]);
        if ($notifId !== false) {
            $notif = true;
            $data_notif = [
                'handle' => 'dep365-notification',
                'data' => json_encode([
                    'key' => 'notification-user-' . $model->created_by,
                    'data' => [
                        'urlView' => Url::toRoute(['/general/notification/view', 'id' => $notifId]),
                        'icon' => 'ft-alert-circle',
                        'bg' => 'bg-teal',
                        'tit' => 'red',
                        'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                        'description' => $comment,
                        'created_at' => MyHelper::TimeBefore(time())
                    ]
                ])
            ];
        }
        //nguoi trien khai
        if (!empty($model->nguoi_trien_khai) && $model->nguoi_trien_khai != $model->created_by && $model->nguoi_trien_khai != $current_user) {
            $notifId = Dep365Notification::quickCreate([
                'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                'icon' => 'ft-alert-circle',
                'description' => $comment,
                'is_new' => 1,
                'is_bg' => 3,
                'status' => 1,
                'for_who' => 'user-' . $model->created_by
            ]);
            if ($notifId !== false) {
                $notif = true;
                $data_notif = [
                    'handle' => 'dep365-notification',
                    'data' => json_encode([
                        'key' => 'notification-user-' . $model->created_by,
                        'data' => [
                            'urlView' => Url::toRoute(['/general/notification/view', 'id' => $notifId]),
                            'icon' => 'ft-alert-circle',
                            'bg' => 'bg-teal',
                            'tit' => 'red',
                            'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                            'description' => $comment,
                            'created_at' => MyHelper::TimeBefore(time())
                        ]
                    ])
                ];
            }
        }
        //truong phong
        if (!empty($model->chosen_one) && $model->chosen_one != $current_user) {
            $notifId = Dep365Notification::quickCreate([
                'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                'icon' => 'ft-alert-circle',
                'description' => $comment,
                'is_new' => 1,
                'is_bg' => 3,
                'status' => 1,
                'for_who' => 'user-' . $model->chosen_one
            ]);
            if ($notifId !== false) {
                $notif = true;
                $data_notif = [
                    'handle' => 'dep365-notification',
                    'data' => json_encode([
                        'key' => 'notification-user-' . $model->chosen_one,
                        'data' => [
                            'urlView' => Url::toRoute(['/general/notification/view', 'id' => $notifId]),
                            'icon' => 'ft-alert-circle',
                            'bg' => 'bg-teal',
                            'tit' => 'red',
                            'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                            'description' => $comment,
                            'created_at' => MyHelper::TimeBefore(time())
                        ]
                    ])
                ];
            }
        }
        //kế toán
        if (!empty($model->accountant_accept) && $model->leader_accept != $model->accountant_accept) {
            $notifId = Dep365Notification::quickCreate([
                'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                'icon' => 'ft-alert-circle',
                'description' => $comment,
                'is_new' => 1,
                'is_bg' => 3,
                'status' => 1,
                'for_who' => 'user-' . $model->created_by
            ]);
            if ($notifId !== false) {
                $notif = true;
                $data_notif = [
                    'handle' => 'dep365-notification',
                    'data' => json_encode([
                        'key' => 'notification-user-' . $model->created_by,
                        'data' => [
                            'urlView' => Url::toRoute(['/general/notification/view', 'id' => $notifId]),
                            'icon' => 'ft-alert-circle',
                            'bg' => 'bg-teal',
                            'tit' => 'red',
                            'name' => 'Có comment mới ở đề xuất chi: ' . Html::a('Đề xuất ' . $model->title, Url::to(['/chi/de-xuat-chi/update', 'id' => $model->title])),
                            'description' => $comment,
                            'created_at' => MyHelper::TimeBefore(time())
                        ]
                    ])
                ];
            }
        }

    }
}
