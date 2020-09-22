<?php
$domain = ((
    (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
        (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
) ? "https" : "http") . "://" . @$_SERVER['HTTP_HOST'];
define('ISCLI', PHP_SAPI === 'cli');

require __DIR__ . '/_console.php';
/*
 * Config Time
 */
define('MINUTE_IN_SECONDS', 60);
define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);

define('API_PLACES_GOOGLE', 'AIzaSyA6eHxfEY4OeS5hiwcWMioNUeZcUp_qvpc');

/**
 * Setting path aliases
 */
Yii::setAlias('@base', realpath(__DIR__ . '/../../'));
Yii::setAlias('@common', realpath(__DIR__ . '/../../common'));
Yii::setAlias('@frontend', realpath(__DIR__ . '/../../frontend'));
Yii::setAlias('@backend', realpath(__DIR__ . '/../../backend'));
Yii::setAlias('@console', realpath(__DIR__ . '/../../console'));
Yii::setAlias('@storage', realpath(__DIR__ . '/../../storage'));
Yii::setAlias('@web', realpath(__DIR__ . '/../../web'));
Yii::setAlias('@media', realpath(__DIR__ . '/../../media'));

Yii::setAlias('@frontendUrl', $domain . '/');
Yii::setAlias('@backendUrl', $domain . '/backend/');

//Define
if (CONSOLE_HOST == 1 || CONSOLE_HOST == 2) {
    define('YII2_CACHE', false);
    define('YII2_DEBUG', false);
    define('YII2_ENV_DEV', true);
    define('YII2_MAIL', true);
    define('YII2_LOG', true);
	define('API_MYAURIS', 'http://myauris.tm');
	require __DIR__ . '/_api.php';
} elseif (CONSOLE_HOST == 3) {
    define('YII2_DEBUG', false);
    define('YII2_CACHE', true);
    define('YII2_ENV_DEV', false);
    define('YII2_MAIL', true);
    define('YII2_LOG', true);
    define('API_USER', 'user.api');
    define('API_PASS', 'QskRaZH1Lb');
    define('API_URL', 'http://khachhang.myauris.vn/backend/api/v1');
    define('API_MYAURIS', 'https://myauris.vn');
}

define('WEB_NAME', 'Dashboard');

define('LINK_ASSETS', true);

define('SITE_ADMIN', 'Dashboard');
define('SOCKET_URL', 'https://socket.myauris.vn');


define('WEB_ADMIN', 'Administrator');
define('ADMIN_EMAIL', 'mongdaovan86.wd@gmail.com');
define('ROBOT_EMAIL', 'mongdao.wd@gmail.com');
define('FRONTEND_HOST_INFO', $domain);
define('FRONTEND_BASE_URL', '/');
define('BACKEND_HOST_INFO', $domain . '/backend');
define('BACKEND_BASE_URL', '/backend');

define('RECAPTCHA_GOOGLE_SITEKEY', '6LcvUX8UAAAAAJiAz5q09iS_5nKPJcUluZW6wxpZ');
define('RECAPTCHA_GOOGLE_SECRETKEY', '6LcvUX8UAAAAAAQv025aD6luMNd2llYX_bQWtjCi');

define('WEB_COOKIE_VALIDATION_KEY', 'hXxYdJJVHPJjqDfFvY0DW4_FDYIa5RcS');
define('FRONTEND_COOKIE_VALIDATION_KEY', 'CU9aseEPkKKG4TpJN5gySlVpV7pco-2P');
define('BACKEND_COOKIE_VALIDATION_KEY', 'hXxYdJJVHPJjqDfFvY0DW4_FDYIa5RcS');
define('INFORMATION_EMAIL', 'mongdaovan86@yahoo.com || mongdaovan86.wd@gmail.com');
define('INFORMATION_PHONE', '0906.904.884');

