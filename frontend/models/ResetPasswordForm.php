<?php
namespace frontend\models;

use common\models\Myuser;
use Yii;
use yii\base\InvalidParamException;
use yii\base\Model;


class ResetPasswordForm extends Model
{
    public $password;
    private $_user;

    public function rules()
    {
        return
            [
                ['password','required'],
            ];
    }
    public function attributeLabels()
    {
        return
            [
                'password' => 'Password !!',
            ];
    }
    //����� ��� ������ ������
    public function resetPassword()
    {
        /* @var $user Myuser */
        //� _user �������� ������ ������������ � �������� ������ ������
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }
    //����� ������� ������� � ����������� ����� ��������� key �� ������� � ����� �������� (1 ���)
    public function __construct($param_key,$config=[])
    {

        if(empty($param_key))
            throw new InvalidParamException('Klycn ne mojet bit pystim');
        $this->_user = Myuser::findBySecretKey($param_key,'');
        //���� ������ �� ������
        if(!$this->_user)
            throw new InvalidParamException('Ne vernaya ssilka');
        parent::__construct($config); //��� ������ � ������ �� �������
    }
}