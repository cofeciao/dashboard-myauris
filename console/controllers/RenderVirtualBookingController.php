<?php

namespace console\controllers;

use common\components\RenderVirtualBookingComponent;
use yii\web\Controller;

class RenderVirtualBookingController extends Controller
{
    public function actionIndex()
    {
        return false;
    }
    public function actionRender($renderNew = null)
    {
        set_time_limit(1200);
        ini_set("log_errors", 1);
        ini_set("error_log", "error.log");
        if (!in_array($renderNew, ["true", "false"])) {
            $renderNew = "false";
        }
        $renderVirtualBooking = new RenderVirtualBookingComponent();
        $renderVirtualBooking->renderVirtualBooking($renderNew === "true");
    }
}
