<?php
namespace frontend\models;

use common\models\Myuser;
use yii\base\Model;
use yii;

class RegisterForm extends Model
{

    public $name;
    public $email;
    public $password;
    public $repassword;
    public $status;
    public $secret_key;
    public $auth_key;

    //const STATUS_ACTIVE = 1;
    //const STATUS_NOT_ACTIVE = 0 ;

    public function rules()
    {
        return [
            [['name', 'password', 'email','repassword'], 'required'],
            ['repassword','compare','compareAttribute' => 'password'],
            [['email'],'email'],
            ['email', 'unique','targetClass' => '\common\models\Myuser'], //����������� � ��� targetClass
            [['name', 'password', 'email'], 'string', 'max' => 255],
            ['name', 'unique','targetClass' => '\common\models\Myuser'], //����������� � ��� targetClass
           // ['status','default','value' => Myuser::STATUS_ACTIVE],
           // ['status','default','value' => Myuser::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iduser' => 'Iduser',
            'name' => 'Name',
            'password' => 'Password',
            'email' => 'Email',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['emailActivation'] = ['name','password','email','repassword'];
        return $scenarios;
    }



    public function goreg()
    {
        if($this->validate())
        {
            $myuser = new Myuser();
            $myuser->name=$this->name;
            $myuser->email=$this->email;
            $myuser->setPassword($this->password);
            $myuser->generateAuthKey();
            //���� ��������� �������� �� ���������� ��������� ����
            if($this->scenario === 'emailActivation') :
                //$myuser->secret_key = $this->generateSecretKey();
                $myuser->generateSecretKey();
                $myuser->status = Myuser::STATUS_NOT_ACTIVE;
            else:
                $myuser->status = Myuser::STATUS_ACTIVE;
                endif;
           return $myuser->save() ; // ��� ������� ��� ����������� ����������

        }


    }

    public function sendActivationEmail(/*$user*/)
    {
        /* @var $user Myuser */
        //�������� � User ���� ����� ������� ���������� �� Myuser

       /* $user = Myuser::findOne([
            'name' => $user->name,
            'email' => $user->email,
        ]);*/

        $user = Myuser::findOne([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        //debug::debug_k($user);
        if($user):
        //������ ������������� � �������� � ����� mail
        //activationEmail ������ �������������, � ��������� � ��� ������������� ��������� ������� ������������
            //������ ������ mailer ���� � common/mail/{�����}
        return Yii::$app->mailer->compose('activationEmail',['user' => $user])
            //�� ���� ����������
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.'(Otpravlenmo robotom)'])
            //���������� ���� (��������� email � �����)
            ->setTo($this->email)
            //���� ������
            ->setSubject('Activaciya dlya'.Yii::$app->name)
            ->send();
        else:
            return false;
            endif;
    }
}
