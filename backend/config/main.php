<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'homeUrl' => '/',
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => '/',
    'timeZone' => 'Europe/Kiev',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '',
        ],
		'user' => [
			'identityCookie' => [
				'name'     => '_frontendIdentity',
				'path'     => '/',
				'httpOnly' => true,
			],
		],
		'session' => [
			'name' => 'FRONTENDSESSID',
			'cookieParams' => [
				'httpOnly' => true,
				'path'     => '/',
			],
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/login'                            => 'user/security/login',
                '/'                                 => 'site/index',
                'registration/<mdId>'               => 'site/registration',
                'registration'                      => 'site/registration',
                'user/<id:\d+>/permit'              => 'permit/user/view',
                '<class:(regions|areas|cities)>/<controller:(location-types)>' => '<controller>/index',
                '<class:(regions|areas|cities)>/<controller:(location-types)>/create' => '<controller>/create',
                /*'location-types/<type:(areas|regions|cities)>'=> 'location-types/index',*/
				/*'<controller:(regions|areas|cities)>/<action>/<type:(\w+)>(archive)>' => '<controller>/index',*/
                'profile'                           => 'user/profile/index',
                'profile/edit'                      => 'user/settings/profile',
                'profile/account/edit'              => 'user/settings/account',
                'profile/<id:\d+>/show'             => 'user/profile/show',
                'permit/<action:(update-permission|update-role|delete|permission|role)>' => 'permit/access/<action>',
                '/language'                         => '/translatemanager/language',
                '/language/create'                  => '/translatemanager/language/create',
                '/language/scan'                    => '/translatemanager/language/scan',
                '/language/optimizer'               => '/translatemanager/language/optimizer',
                '/language/import'                  => '/translatemanager/language/import',
                '/language/export'                  => '/translatemanager/language/export',
                '/language/translate/<language_id>' => '/translatemanager/language/translate',
                '<controller>/<id:\d+>/<action:(view|update|delete|event-view)>' => '<controller>/<action>',
                '<controller>'                      => '<controller>/index',
                '<controller>/<action>'             => '<controller>/<action>',
            ],
        ],
		'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views/yii2-app',
					'@dektrium/user/views' => '@backend/modules/user/views'
                ],
            ],
        ],
    ],
	'modules' => [
		'permit' => [
			'class' => 'developeruz\db_rbac\Yii2DbRbac',
			'params' => [
				'userClass' => 'common\models\User'
			]
		],
        'gridview' => ['class' => 'kartik\grid\Module'],
	],
    'params' => $params,
];
