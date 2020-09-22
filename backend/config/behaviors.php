<?php

return [
//    'class' => common\behaviors\GlobalAccessBehavior::class,
//    'class' =>backend\components\AccessBehavior::class,
//https://dashboard.myauris.vn/debug/default/toolbar?tag=5e6afc3548e16
	'class' => 'common\filters\MyAccessControl',
	'rules' => [
		[
			'matchCallback' => function ($rule, $action) {
				$allowedControllers = [
					'debug/default',
				];
				$isAllowedController = in_array($action->controller->uniqueId, $allowedControllers);

				return $isAllowedController;
			},
			'allow' => true,
		],
		[
			'controllers' => [ 'auth' ],
			'allow'       => true,
			'actions'     => [
				'login',
				'validate-login',
				'submit-login',
				'validate-auth',
				'submit-auth',
				'resend-pin',
				'request-password-reset',
				'reset-password',
				'permission'
			],
		],
		[
			'controllers' => [ 'phong-kham-khuyen-mai' ],
			'allow'       => true,
			'roles'       => [ '?' ],
			'actions'     => [ 'get-price-khuyen-mai' ],
		],
		[
			'controllers' => [ 'auth' ],
			'allow'       => true,
			'roles'       => [ '?' ],
			'actions'     => [ 'login', 'permission' ],
		],
		[
			'controllers' => [ 'auth' ],
			'allow'       => true,
			'roles'       => [ '@' ],
			'actions'     => [ 'logout', 'reliable-equipment' ],
		],
		[
			'controllers' => [ 'list-support', 'contact-phone', 'bao-tri', 'site', 'config', 'api-docs' ],
			'allow'       => true,
			'roles'       => [ '@' ],
		],
		[
			'controllers' => [ 'site' ],
			'allow'       => true,
			'roles'       => [ 'loginToBackend' ],
			'actions'     => [ 'index' ],
		],
		[
			'allow'   => true,
			'actions' => [ 'online', 'listener', 'perpage' ],
		],
		[
			'allow' => true,
			'roles' => [ 'user_develop' ],
		],
		[
			'allow'   => true,
			'roles'   => [ '@' ],
			'actions' => [
				'get-district',
				'view-send-sms',
				'validate-sms',
				'change-sms-customer',
				'get-nguon-online',
				'list-support',
				'list-support',
				'get-customer',
				'danh-sach-san-pham',
			],
		],
		[
			'controllers' => [ 'call' ],
			'allow'       => true,
			'roles'       => [ '@' ],
			'actions'     => [ 'get-call-info' ],
		],
		[
			'controllers' => [ 'setting' ],
			'allow'       => true,
			'roles'       => [ '@' ],
			'actions'     => [ 'index' ],
		],
		[
			'controllers' => [ 'online' ],
			'allow'       => true,
			'roles'       => [ '?' ],
			'actions'     => [ 'index' ],
		],
		/*[
			'controllers' => ['default'],
			'allow' => true,
			'roles' => ['@'],
			'actions' => ['toolbar']
		],*/
	],
];
