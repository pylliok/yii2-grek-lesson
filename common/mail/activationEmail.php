<?php

/* @var $user \common\models\Myuser */
use yii\helpers\Html;


//������ ������ � ����� ������� ->������ ��� ���������

echo 'Hi '.Html::encode($user->name).'.</br>';
echo Html::a('Dlya activacii pereidite po ssilke.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/main/main/account-activation',
            'key' => $user->secret_key,
        ]


    ));
//������ � ������ ������� �� ������� ������������ �������� � �������� ActiveAccaunt �����������
//main � ����� $_GET �������� ��������� ���� $key . �������� ������� ����