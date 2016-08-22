<?php
namespace frontend\models;
use common\models\Myuser;
use Yii;
use yii\base\Model;

class SendEmailForm extends Model
{
    //��������� �������� ������� ����� �������������� � �����
    public $email;

    //� ������� ��� �������� Email

    public function rules()
    {
        return
            [
                //������ ������� �� �����
                ['email','filter','filter'=> 'trim'],
                ['email','required'],
                ['email','email'],
                ['email', 'exist',
                    'targetClass' => Myuser::className(),//��������� ���� � ������ ������
                    'filter' => [
                        'status' => Myuser::STATUS_ACTIVE
                    ],
                    'message' => 'Danni email ne zaregistrirovan',
                ], // ������ ����� ������ ���� � �������� Myuser � ������������ � ���� email ������ ���� �����������
            ];
    }

    public function attributeLabels()
    {
        return
            [
                'email' => 'Email pole',
            ];
    }
    //������� ������������ � �������� ������� � ���������� ��������� ����
    // � ���������� ������ � ������� ��� ����� ������
    public function sendEmail()
    {
        //�������� User ��� ������ ������ Myuser
        /* @var $user Myuser  */
        //����� ��������������� ������������ � ������ �����
        $user = Myuser::findOne(
            [
                'status' => Myuser::STATUS_ACTIVE,
                'email' => $this->email,
            ]
        );
        //���� ������������ �������
        if($user):
            //������ � ����������� ������������ ��������� ����
            $user->generateSecretKey();
            //��������� � ��. ���� �� ��������� ���������� ������
        if($user->save()):
            //����� ��������
            return Yii::$app->mailer->compose('resetPassword',['user' => $user])
                //��������� � �����
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'(Otpravleno robotom)'])
                //��������� ����
                ->setTo($this->email)
                //���� ������
                ->setSubject('Sbros parolya'.Yii::$app->name)
                ->send();
            endif;
        endif;
        //���� ���� �� ���������
        return false;
    }
}