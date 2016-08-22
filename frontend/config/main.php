<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'main/default/index', //делаем нашу страницу на главную котора¤ щас по адресу main

    'modules' => [
        'main' => [
            'class' => 'app\modules\main\Module',
        ],
        'cabinet' => [
            'class' => 'frontend\modules\cabinet\Module',
        ],
    ],

    'components' => [

        'mail' => [
            'class'            => 'zyx\phpmailer\Mailer',
            'viewPath'         => '@common/mail', //Ўаблонные файлы
            'useFileTransport' => false, //если стоит тру то будет сохран¤тьс¤ локальна¤ копи¤ этого письма
            'config'           => [
                'mailer'     => 'smtp',
                'host'       => 'smtp.yandex.ru',
                'port'       => '465',
                'smtpsecure' => 'ssl',
                'smtpauth'   => true,
                'username'   => 'pyiiiok1@ya.ru', //здесь свои настройки
                'password'   => 'PyIIIoK',		//здесь свои настройки
                'isHtml'     => true, //может передоватьс¤ html
                'charset'    => 'UTF-8',
            ],
        ],

        'common'=> [ //вспомогательные функции например phpmailer
          'class' => '\frontend\components\Common',
        ],

        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => ''
        ],
       'user' => [
          // 'identityClass' => 'common\models\User',
           'identityClass' => 'common\models\Myuser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],

        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'mailer' => [
          'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true, //если true Все письма будут сохраняться в runtime/mail
        ],
        'errorHandler' => [
            'errorAction' => 'main/main/error',//Not Found 404
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],



    ],
    'params' => $params,
];
