<?php

namespace common\models;
use yii\db\ActiveRecord;
use Yii;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior; //��� ��������������� ���������� �������

/**
 * This is the model class for table "myuser".
 *
 * @property integer $iduser
 * @property string $name
 * @property string $password
 * @property string $email
 * @property string $status
 * @property string $secret_key
 * @property string $auth_key
 */

class Myuser extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */

   /* public function rules()
    {
       return
           [
               ['secret_key','unique'],
           ];
    }*/
   public function behaviors()//����� ����������� ��������� ��������� �������� ���������� ��������
    {
        return
            [
                TimestampBehavior::className(),//��������� �������
            ];
    }

    //���� Secret_key
    public static function findBySecretKey($key,$param)
    {
        //���� ����� ������ false ���������� null
        if(!($param === 1)) //���� 1 �� ��������� �������� ��������� (� �������������� ������ !1)
        if(!static::isSecretKeyExpire($key))
            return null;//������
        //����� ������ ������������ � ���������� ������
        return static::findOne(
            [
               'secret_key' => $key
            ]);
    }


    //�������� ����� � ��������� ��� �� ������ ���� � ����� ��� �� ������� (1 ���)
    public static function isSecretKeyExpire($key)
    {
        if(empty($key))
            return false;
        //������ ����� ����� �������� �����
        $expire = Yii::$app->params['secretKeyExpire'];
        //��������� ������ �� ������ � ����������� '_'
        $parts = explode('_',$key);
        //���������� ����� � ���������� timestamp (�����)
        $timestamp = (int) end($parts);
        //���������� ����� �������� ����� � ����� �������� ����� , � ���� ���������� �������� ������ ���� �����
        // �������� ������� ���������� true ����� false �.�. ���� �������� ����� �����
        return $timestamp + $expire >= time();
    }

    //����� �� ������ ������������ �� �����
    public static function findByUsername($name) //������������ ������ �� �����
    {
        return static::findOne(['name' => $name]); //������� ���� ��� � ��
    }
    //�������� �� ������ ��������� ���� ���� �� name
    public static function findByStatusName($name)
    {
        return static::findOne(['name' => $name , 'status' => self::STATUS_ACTIVE]); //������� ���� ��� � ��
    }
    //�������� �� ������ ��������� ���� ���� �� Email
    public static function findByStatusEmail($email)
    {
        return static::findOne(['email' => $email , 'status' => self::STATUS_ACTIVE]); //������� ���� ��� � ��
    }

    public static function tableName()
    {
        return 'myuser';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iduser' => 'Iduser',
            'name' => 'Name',
            'password' => 'Password',
            'email' => 'Email',
            'secret_key' => 'Key',
        ];
    }
    //������� ������ � ��������� � ��
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    //������������ ��� ��� ����� ������� ����
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    //���������� �������� ������ � ���������� �� ��
    public function validatePassword($password)
    {

        return Yii::$app->security->validatePassword($password,$this->password);
    }


    public static function findIdentity($id)
    {
        return static::findOne(['iduser' => $id , 'status' => '1']);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
       NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    //����� ������������ �� Email
    public static function findByEmail($email)
    {
       return static::findOne(
           [
              'email' => $email
           ]);
    }
    //�������� secret_key
    public function removeSecretKey()
    {
        $this->secret_key = null;
    }

    public function generateSecretKey()
    {
        return $this->secret_key = Yii::$app->security->generateRandomString() . '_' . time();
    }


}
