<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => require(dirname(__DIR__)."/config/db.php"),//подключаем наш файл где находится подключение к БД
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
