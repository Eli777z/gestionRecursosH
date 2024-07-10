<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'language' => 'es',
    'sourceLanguage' => 'en',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],



    'modules' => [

        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'right-menu', // por defaults es null, cuando no deseas usar el menú 
            //'mainLayout' => '@app/views/layouts/main.php',

        ] 
    ],


    
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        
        'mpdf' => [
            'class' => 'Mpdf\Mpdf',
            'tempDir' => '@runtime/mpdf', 
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                   '@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
                ],
            ],
       ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'DIbr7s5vs_huCjsgsSfjuUYWV2Wq7FgW',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Usuario',
            'enableAutoLogin' => true,
        ],
       
        'errorHandler' => [
            'errorAction' => 'site/error',
            
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        'viewPath' => '@app/mail',
        'useFileTransport' => false, 
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'elitaev7@gmail.com',
            'password' => 'vdcj rfzv xpuh aluy',
            'port' => '587',
            'encryption' => 'tls',
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
        'db' => $db,
        
        'urlManager' => [
            'enablePrettyUrl' => true,
           // 'showScriptName' => false,
            'rules' => [
                'documento/download/<id:\d+>' => 'documento/download',
                'obtener-formulario' => 'site/obtener-formulario',
                'delete-formato' => 'empleado/delete-formato',
                'download-formato' => 'empleado/download-formato',


               
            ],
        ],
        'assetManager' => [

            'bundles' => [

                'yii\jui\JuiAsset' => [

                    'css' => ['themes/redmond/jquery-ui.css'],

                ],
                

            ]

        ],
        
    ],
    'params' => $params,
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
           // 'site/*',
           'gii/*',
            'admin/*',
            'site/login',
            'site/logout',
            'some-controller/some-action',
            'site/get-empleado-foto',
            'empleado/foto-empleado'
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ], 
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'crud' => [ // generator name
                'class' => 'yii\gii\generators\crud\Generator', // generator class
                'templates' => [ // setting for our templates
                    'yii2-adminlte3' => '@vendor/hail812/yii2-adminlte3/src/gii/generators/crud/default' // template name => path to template
                ]
            ]
        ]
    ];
}




return $config;
?>