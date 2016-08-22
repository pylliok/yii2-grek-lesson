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
    //метод для сброса пароля
    public function resetPassword()
    {
        /* @var $user Myuser */
        //в _user поместим объект пользователя у которого меняем пароль
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removeSecretKey();
        return $user->save();
    }
    //перед вызовом объекта в контроллере будем проверять key на пустоту и срока давности (1 час)
    public function __construct($param_key,$config=[])
    {

        if(empty($param_key))
            throw new InvalidParamException('Klycn ne mojet bit pystim');
        $this->_user = Myuser::findBySecretKey($param_key,'');
        //если объект ен найден
        if(!$this->_user)
            throw new InvalidParamException('Ne vernaya ssilka');
        parent::__construct($config); //эта строка и конфиг не понятны
    }
}