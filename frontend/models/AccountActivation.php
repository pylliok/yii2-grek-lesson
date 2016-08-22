<?php
namespace frontend\models;
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.08.2016
 * Time: 5:50
 */


use common\models\Myuser;
use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;

//� ��������� �������� $name

/* @property string $name */

class AccountActivation extends Model
{
    /* @var $user \common\models\Myuser */

    private $user;
    protected $param;
    //����������� ���������� ������ �������� �������
    public function __construct($key, $config = [])
    {
        $param = 1;
        //���� ���� ������ ��� �� �������� �������
        if( empty($key) /*|| is_string($key)*/)
            //�������� ���������� �������� ����������
            throw new InvalidParamException('Kluch ne mojet bit pystim');
        //������� ������ ������������  �� �����
        $this->user = Myuser::findBySecretKey($key,$param);
            //���� ������ �� ������
            if(!$this->user)
                throw new InvalidParamException('Nevernii klych');
        //�������� ����� �� ������������� ������
        parent::__construct($config);
    }

    //����� ������� ����� ������������ ������ ������������

    public function activateAccount()
    {
        $user = $this->user; //�������� $user ��� ������ ������������
        $user->status = Myuser::STATUS_ACTIVE; //������������� ������
        //$user->secret_key = Null;//���� Secret_key � ������������ ���� Null
        $user->removeSecretKey();
        return $user->save();//��������� � ������� ������ ��������������� ������������
    }
    //������ ������ ������� ����� ���������� name ��������������� ������������

    public function getUsername()
    {
        $user = $this->user;
        return $user->name;
    }
}