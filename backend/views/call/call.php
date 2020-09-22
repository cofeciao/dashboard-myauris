<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05-Jan-19
 * Time: 8:57 AM
 */

?>
    <div>
        <input id="callTo" type="text" name="toUsername" style="width: 200px;" placeholder="userId or number" value="">

        <button id="callBtn" onclick="testMakeCall()">Call</button>
        <button id="hangupBtn" onclick="testHangupCall()">Hangup</button>
    </div>

    <div>
        <br/>
        Logged in: <span id="loggedUserId" style="color: red">Not logged</span>
    </div>

    <div id="incoming-call-div">
        Incoming call from: <span id="incoming_call_from"></span>
        <button id="answerBtn" onclick="testAnswerCall()">Answer</button>
        <button id="rejectBtn" onclick="testRejectCall()">Reject</button>
    </div>

    <div>
        <br/>
        Call status: <span id="callStatus" style="color: red">Not started</span>
    </div>

    <div>
        <video id="remoteVideo" playsinline autoplay style="width: 350px"></video>
    </div>
<?php
//$vpbx_acc = \Yii::$app->user->identity->vpbx_acc;
//$idUser = \Yii::$app->user->id;
//$nvOnline = \common\models\UserProfile::getFullName();
//$script = <<< JS
//    var idUser = '$idUser';
//    var nvOnline = '$nvOnline';
//    var fromNumber = '$vpbx_acc';
    // var access_token = '$token';
//JS;

//$this->registerJs($script, \yii\web\View::POS_END);
