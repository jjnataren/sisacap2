<?php
$config = [
    'homeUrl'=>Yii::getAlias('@backendUrl'),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'=>'empresa/dashboard',
    'controllerMap'=>[
        'file-manager-elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['manager'],
            'disabledCommands' => ['netmount'],
            'roots' => [
                [
                    'baseUrl' => '@storageUrl',
                    'basePath' => '@storage',
                    'path'   => '/uploads',
                    'access' => ['read' => 'manager', 'write' => 'manager']
                ]
            ]
        ]
    ],
    'components'=>[
    		'formatter' => [
    				'dateFormat' => 'dd/MM/yyyy',
    				'nullDisplay'=> '(no establecido)'
    			//	'decimalSeparator' => ',',
    			//	'thousandSeparator' => ' ',
    			//	'currencyCode' => 'EUR',
    		],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'cookieValidationKey' => getenv('BACKEND_COOKIE_VALIDATION_KEY')
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'loginUrl'=>['sign-in/login'],
            'enableAutoLogin' => true,
        ],
        'response' => [
        'formatters' => [
        'pdf' => [
        'class' => 'robregonm\pdf\PdfResponseFormatter',
        'mode'=> '',
        //'format' => 'Letter',  // Optional but recommended. http://mpdf1.com/manual/index.php?tid=184
        //'defaultFontSize' => 0, // Optional
        //'defaultFont' => '', // Optional
        //'marginLeft' => 25, // Optional
        //'marginRight' => 25, // Optional
        //'marginTop' => 16, // Optional
        //'marginBottom' => 16, // Optional
        //'marginHeader' => 9, // Optional
        //'marginFooter' => 9, // Optional
        'orientation' => 'Landscape', // optional. This value will be ignored if format is a string value.
        'options' => [
        'useOnlyCoreFonts'=>true
        // mPDF Variables
         //'fontdata' => [
        // ... some fonts. http://mpdf1.com/manual/index.php?tid=454
        // ]
        ]
        ],
        ]
        ],

        'mail' => [
        'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
        'username' => 'sisacap1@gmail.com',
        'password' => 'sisacap123',
        'port' => '587', // Port 25 is a very common port too
        'encryption' => 'tls', // It is often used, check your provider or mail server specs
      //  'authentication' =>'plain'
        ],
        ],


    ],
    'modules'=>[
        'i18n' => [
            'class' => 'backend\modules\i18n\Module',
            'defaultRoute'=>'i18n-message/index'
        ]
    ],
    'as globalAccess'=>[
        'class'=>'\common\components\behaviors\GlobalAccessBehavior',
        'rules'=>[
            [
                'controllers'=>['sign-in'],
                'allow' => true,
                'roles' => ['?','manager','administrator','submanager','instructor'],
                'actions'=>['login','logout','account','profile','avatar-upload']
            ],

            [
            'controllers'=>['comision-mixta-cap'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['indexbycompany','createbycompany','viewbycompany', 'dashboard',
            			'updatebyuser', 'reportpdf','addestablishment', 'deletestablecimiento','uploaddocument','deletedocument',
						'select-representante','deletebyuser']
            ],

            [
            'controllers'=>['constancias'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['createbycourse','add-constancia','dashboard','delete-constancia','constanciapdf', 'uploaddocument','send-notification','constancia-comprobante-pdf','updatebyuser']
            ],


            [
                'controllers'=>['constancias'],
                'allow' => true,
                'roles' => ['submanager'],
                'actions'=>['createbycourse', 'create-sub','add-constancia','dashboard-sub','add-constancia-sub','delete-constancia-sub','constanciapdf-sub', 'uploaddocument','send-notification','constancia-comprobante-pdf','updatebyuser']
            ],

        		[
        		'controllers'=>['constancias'],
        		'allow' => true,
        		'roles' => ['instructor'],
        		'actions'=>['course-by-instructor','dashboard-by-instructor','updatebyinstructor','constancia-firmada','constancia-comprobante-pdf']
        		],

            [
            'controllers'=>['lista-plan'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['create-by-plan','dashboard', 'update-by-company', 'add-establishment',
            'report-pdf-4', 'add-constancia','report-pdf-part2', 'update-by-plan', 'report-pdf-all','delete-lista', 'send-report','update', 'uploaddocument','deletedocument','delete-constancia', 'reportdc4','reportdc4-part2' ],
            ],

            [
            'controllers'=>['curso'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['createbyplan', 'updatebyplan','uploaddocument']
            ],



            [
            'controllers'=>['empresa'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['viewbyuser', 'test-mail', 'updatebyuser','deletebyuser','indexestablishment','establishments','avatar-upload', 'createestablishment',
						'viewbystablishment' , 'updatebystableshiment', 'dashboard','getmunicipios','acces-submanager',	]
            ],

        		[
        		'controllers'=>['empresa'],
        		'allow' => true,
        		'roles' => ['instructor'],
        		'actions'=>['dashboard','dashboard-instructor','view-by-instructor','index-establishment-instructor','viewbystablishmentinstructor']
        		],

        		[
        		'controllers'=>['empresa'],
        		'allow' => true,
        		'roles' => ['submanager'],
        		'actions'=>['dashboard-submanager','dashboard','view-by-sub','update-by-sub','getmunicipios']
        		],

            [
            'controllers'=>['site'],
            'allow' => true,
            'roles' => ['manager','instructor','submanager'],
            'actions'=>['error']
            ],


            [
            'controllers'=>['indicador-comision'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['view-by-company','index-by-company','delete-by-company']
            ],


            [
            'controllers'=>['indicador-constancia'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['view-by-company','index-by-company','delete-by-company',]
            ],

        		[
        		'controllers'=>['indicador-curso'],
        		'allow' => true,
        		'roles' => ['instructor'],
        		'actions'=>['view-by-user-instructor','delete-by-instructor','index-by-instructor','view-by-instructor']
        		],

        		[
        		'controllers'=>['indicador-constancia'],
        		'allow' => true,
        		'roles' => ['instructor'],
        		'actions'=>['view-by-user-instructor','delete-by-instructor','index-by-instructor','delete-by-company','view-by-instructor','index-by-company']
        		],


            [
            'controllers'=>['indicador-plan'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['view-by-company','index-by-company','delete-by-company']
            ],



            [
            'controllers'=>['instructor'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['indexbycompany', 'createbycompany','viewbycompany','updatebycompany','deletebycompany']
            ],



        		[
        		'controllers'=>['instructor'],
        		'allow' => true,
        		'roles' => ['instructor'],
        		'actions'=>['update-by-instructor', 'createbycompany','viewbycompany','updatebycompany','deletebycompany','view-by-instructor','view-sign-pic','manage-sign-pic']
        		],



            [
            'controllers'=>['plan'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['createbycomision', 'createbycompany','viewbycompany', 'updatebyuser', 'deletebyuser','dashboard',
						'deletebycomisiones', 'addestablishment', 'deleteestablecimiento', 'reportpdf','updatebycompany', 'addpuesto',
						'deletepuesto','uploaddocument','deletedocument', 'deletecursos']
            ],


            [
            'controllers'=>['puesto-empresa'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['indexbycompany', 'createbycompany','viewbycompany', 'updatebyuser', 'deletebyuser']
            ],


        		[
        		'controllers'=>['indicador-curso'],
        		'allow' => true,
        		'roles' => ['manager'],
        		'actions'=>['view-by-company', 'index-by-company','delete-by-company'
        		]
        		],


            [
            'controllers'=>['trabajador'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['indexcompany','createworkerbycompany' ,'indexestablishment', 'deletebystablish', 'updatebystablish', 'viewbystablishment','load',
            'updatebyuser', 'viewbycompany','createworkerbyestablishment', 'create-from-const-company','createfromconst', 'deletebyuser', 'loadbyestablishment', 'saveallbyestablishment',
            'saveall'	, 'get-normas','indexallworkers','view-sign-pic'	, 'manage-sign-pic','updatebyall' , 'viewbyall' , 'deletebyall'
            ]
            ],

        		[
        		'controllers'=>['trabajador'],
        		'allow' => true,
        		'roles' => ['submanager'],
        		    'actions'=>['index-by-sub','get-normas','create-by-sub','load-by-sub','view-by-sub','delete-by-sub','save-all-by-sub','update-by-sub']
        		],

            [
                'controllers'=>['plan'],
                'allow' => true,
                'roles' => ['submanager'],
                'actions'=>['dashboard-sub']
            ],

            [
            'controllers'=>['representante-legal'],
            'allow' => true,
            'roles' => ['manager'],
            'actions'=>['viewbycompany','updatebycompany','manage-sign-pic','view-sign-pic']
            ],

        		[
        		'controllers'=>['debug/default'],
        		'allow' => true,
        		'roles' => ['manager'],
        		],

            [
                'controllers'=>['site'],
                'allow' => true,
                'roles' => ['?'],
                'actions'=>['error']
            ],
            [
                'controllers'=>['debug/default'],
                'allow' => true,
                'roles' => ['?'],
            ],
            [
                'controllers'=>['page','key-storage','file-manager','file-storage','system-information','log','cache',
                			 'empresa','representante-legal','catalogo','user','widget-carousel','widget-carousel-item'],
                'allow' => true,
                'roles' => ['administrator'],
            ],
             [
            'controllers'=>['user','page','key-storage','file-manager','file-storage','system-information','log','cache',],
            'allow' => false,
            ],


          /*  [
                'allow' => true,
                'roles' => ['manager','instructor','submanager'],
            ]*/
        ]
    ]
];

if (1) {
    $config['modules']['gii'] = [
        'class'=>'yii\gii\Module',
        'generators'=>[
            'crud'=>[
                'class'=>'yii\gii\generators\crud\Generator',
                'templates'=>[
                    'yii2-starter-kit'=>Yii::getAlias('@backend/views/_gii/templates')
                ],
                'messageCategory'=>'backend'
            ]
        ]
    ];
}

return $config;
