<?php

define('VERSION', 'V1.33');
define('LOGIN_VERSION', 'v2.4');
Yii::setAlias('@moduleBackend', realpath(__DIR__ . '/../backend/modules/'));

Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/../backend/modules/api');
