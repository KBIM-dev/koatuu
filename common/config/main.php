<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'koatuu',
    'bootstrap' => ['BeforeAllActions'],
	'language' => 'uk-UA',
    'timeZone' => 'Ukraine/Kiev',
    'components' => [
        'BeforeAllActions'=>[
            'class'=>'common\components\BeforeAllActions'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
		'i18n' => [
			'translations' => [
				'*' => [
					'class' 				=> 'yii\i18n\DbMessageSource',
					'db' 					=> 'db',
					'sourceLanguage' 		=> 'en-US', // Developer language
					'sourceMessageTable' 	=> '{{%language_source}}',
					'messageTable' 			=> '{{%language_translate}}',
					'cachingDuration' 		=> 86400,
					'enableCaching' 		=> false,
				],
                'user*'=> [
                    'class' 				=> 'yii\i18n\DbMessageSource',
                    'db' 					=> 'db',
                    'sourceLanguage' 		=> 'en-US', // Developer language
                    'sourceMessageTable' 	=> '{{%language_source}}',
                    'messageTable' 			=> '{{%language_translate}}',
                    'cachingDuration' 		=> 86400,
                    'enableCaching' 		=> false,
                ],
                'db_rbac'=> [
                    'class' 				=> 'yii\i18n\DbMessageSource',
                    'db' 					=> 'db',
                    'sourceLanguage' 		=> 'en-US', // Developer language
                    'sourceMessageTable' 	=> '{{%language_source}}',
                    'messageTable' 			=> '{{%language_translate}}',
                    'cachingDuration' 		=> 86400,
                    'enableCaching' 		=> false,
                ],
			],
		],
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
    ],
	'modules' => [
		'user' => [
			'class' 		=> 'dektrium\user\Module',
			'controllerMap' => [
				'admin' 	=> 'backend\modules\user\controllers\AdminController',
				'security' 	=> 'backend\modules\user\controllers\SecurityController',
				'profile' 	=> 'backend\modules\user\controllers\ProfileController',
				'settings' 	=> 'backend\modules\user\controllers\SettingsController'
			],
			'admins' 					=> ['admin'],
			'enableFlashMessages' 		=> false,
			'enableConfirmation'  		=> false,
			'enablePasswordRecovery'  	=> false,
			'urlPrefix'				=> '',
			'emailChangeStrategy'	=> 0,
            'urlRules' => [
                'user'                          => 'admin/index',
                'user/create'                   => 'admin/create',
                '/login'                        => 'security/login',
                'user/<id:\d+>/<action:(view|update|delete|update-profile|info|block)>' => 'admin/<action>',
            ],
			'modelMap'	=> [
				'User' 			=> 'common\models\User',
				'Profile' 		=> 'common\models\Profile',
			],
		],
		'translatemanager' => [
			'class' 		=> 'lajax\translatemanager\Module',
			'layout' 		=> null,
			'roles' 		=> ['@'],
			'allowedIPs' 	=> ['*'],
			'root' => '@app',
		],
	],
];
