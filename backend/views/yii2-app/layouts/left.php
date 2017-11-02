<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/uploads/pdh/ua_flag_rec.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?php use common\models\User;

						if(isset(Yii::$app->user->identity->username)) {
                        echo Yii::$app->user->identity->username;
                    } ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i><?php Yii::t('layout', 'Online');?></a>
            </div>
        </div>

        <?=dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
					[
						'label' 	=> \Yii::t('menu', 'Your profile'),
						'icon' 		=> 'fa fa-user',
						'url' 		=> ['/user/profile/index'],
						'visible' 	=> Yii::$app->user->can('user/profile'),
					],
					[
						'label' 	=> \Yii::t('menu', 'Edit your profile'),
						'icon' 		=> 'fa fa-user',
						'url' 		=> ['/user/settings/profile'],
						'visible' 	=> Yii::$app->user->can('user/settings'),
					],
                	[
						'label' 	=> \Yii::t('menu', 'Users you invite'),
						'icon' 		=> 'fa fa-user',
						'visible' 	=> Yii::$app->user->can('personal-office/user-invited'),
						'url' 		=> ['/personal-office/user-invited'],
					],
                    [
                        'label' 	=> \Yii::t('menu', 'Administrating'),
                        'icon' 		=> 'fa fa-user-secret',
						'visible' 	=> Yii::$app->user->can('user/admin'),
                        'url' 		=> ['/user/admin/index'],
                        'items' => [
                            [
                                'label' 	=> \Yii::t('rbac', 'Users'),
                                'icon' 		=> 'fa fa-user',
                                'visible' 	=> Yii::$app->user->can('user/admin'),
                                'url' 		=> ['/user/admin/index'],
                            ],
							[
								'label' 	=> \Yii::t('rbac', 'Roles'),
								'icon' 		=> 'fa fa-credit-card',
                                'visible' 	=> Yii::$app->user->can('adminAccess'),
								'url' 		=> ['/permit/access/role'],
							],
							[
								'label' 	=> \Yii::t('rbac', 'Permissions'),
								'icon' 		=> 'fa fa-folder',
                                'visible' 	=> Yii::$app->user->can('adminAccess'),
								'url' 		=> ['/permit/access/permission'],
							],
                        ]
                    ],
					[
						'label' 	=> Yii::t('menu', 'Cities'),
						'visible' 	=> Yii::$app->user->can('regions'),
						'icon' 		=> 'fa fa-fort-awesome ',
                        'url' 	=> ['/koatuu/regions'],
                        'items' => [
                            [
                                'label' 	=> Yii::t('menu', 'Cities'),
                                'visible' 	=> Yii::$app->user->can('regions'),
                                'icon' 		=> 'fa fa-fort-awesome ',
                                'url' 	=> ['/koatuu/regions'],
                            ],
                            [
                                'label' => Yii::t('menu', 'Streets'),
                                'icon' => 'fa fa-road',
                                /*'visible' => Yii::$app->user->can('streets'),*/
                                'url' => ['/street/index'],
                            ],
							[
								'label' 	=> Yii::t('location', 'De-communization import'),
								'visible' 	=> Yii::$app->user->can('regions'),
								'icon' 		=> 'fa fa-fort-awesome ',
								'url' 	=> ['/koatuu/de-communization-import'],
							],
							[
								'label' 	=> Yii::t('location', 'Change import'),
								'visible' 	=> Yii::$app->user->can('regions'),
								'icon' 		=> 'fa fa-fort-awesome ',
								'url' 	=> ['/koatuu/change-import'],
							],
                        ],
					],
                    [
                        'label' => Yii::t('menu', 'Communication Type'),
                        'icon' => 'fa fa-comments-o',
                        'visible' => Yii::$app->user->can('communication-type'),
                        'url' => ['/communication-type/index'],
                        'items' => [
                            [
                                'label' => Yii::t('menu', 'Communication Type List'),
                                'url' => ['/communication-type/index']
                            ],
                            [
                                'label' => Yii::t('menu', 'Create New Communication Type'),
                                'url' => ['/communication-type/create']
                            ],

                        ]
                    ],
                    [
                        'label' => Yii::t('menu', 'Profession'),
                        'icon' => 'fa fa-file-text',
                        'visible' => Yii::$app->user->can('profession'),
                        'url' => ['/profession/index'],
                        'items' => [
                            [
                                'label' => Yii::t('menu', 'Profession List'),
                                'url' => ['/profession/index']
                            ],
                            [
                                'label' => Yii::t('menu', 'Add New Profession'),
                                'url' => ['/profession/create']
                            ],
                        ]
                    ],
					[
						'label' => Yii::t('menu', 'Interests'),
						'icon' => 'fa fa-file-text',
						'visible' => Yii::$app->user->can('interests'),
						'url' => ['/interests/index'],
						'items' => [
							[
								'label' => Yii::t('menu', 'Interests List'),
								'url' => ['/interests/index']
							],
							[
								'label' => Yii::t('menu', 'Add New Interest'),
								'url' => ['/interests/create']
							],
						]
					],
                    [
                        'label' => Yii::t('menu', 'Badges'),
                        'icon' => 'fa fa-sun-o',
                        'visible' => Yii::$app->user->can('badge'),
                        'url' => ['/badge/index'],
                        'items' => [
                            [
                                'label' => Yii::t('menu', 'Badge List'),
                                'url' => ['/badge/index']
                            ],
                            [
                                'label' => Yii::t('menu', 'Create new badge'),
                                'url' => ['/badge/create']
                            ],

                        ]
                    ],
                    [
                        'label' => Yii::t('language', 'Language'),
                        'icon' 	=> 'fa fa-language',
                        'visible' => Yii::$app->user->can('translatemanager'),
                        'url' 	=> ['/language'],
                        'items' => [
                            ['label' => Yii::t('language', 'List of languages'), 'icon' => 'fa fa-list',  'url' => ['/language']],
                            ['label' => Yii::t('language', 'Create'), 'icon' => 'fa fa-plus', 'url' => ['/language/create']],
                            ['label' => Yii::t('language', 'Scan'), 'icon' => 'fa fa-search', 'url' => ['/language/scan']],
                            ['label' => Yii::t('language', 'Optimize'), 'icon' => 'fa fa-clock-o', 'url' => ['/language/optimizer']],
                            ['label' => Yii::t('language', 'Language'),
                             'icon' => 'fa fa-language',
                             'url' => ['/language'],
                             'items' => [
                                 ['label' => Yii::t('language', 'Import'), 'icon' => 'fa fa-cloud-download', 'url' => ['/language/import']],
                                 ['label' => Yii::t('language', 'Export'), 'icon' => 'fa fa-cloud-upload', 'url' => ['/language/export']],
                             ],
                            ]
                        ],
                    ],
                    [
                        'label' => 'Login',
                        'url' => ['site/login'],
                        'visible' => Yii::$app->user->isGuest
                    ],
                    [
                        'label' => \Yii::t('menu', 'Some tools'),
                        'icon' => 'fa fa-share',
						'visible' => Yii::$app->user->can('admin'),
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Gii',
                                'icon' => 'fa fa-file-code-o',
                                'url' => ['gii'],
                            ],
                            [
                                'label' => 'Debug',
                                'icon' => 'fa fa-dashboard',
                                'url' => ['debug'],
                            ],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                    ],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            [
                                                'label' => 'Level Three',
                                                'icon' => 'fa fa-circle-o',
                                                'url' => '#',
                                            ],
                                            [
                                                'label' => 'Level Three',
                                                'icon' => 'fa fa-circle-o',
                                                'url' => '#',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        )?>

    </section>

</aside>
