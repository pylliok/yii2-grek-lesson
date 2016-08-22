<?php
namespace frontend\models;


use common\models\Myuser;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $name;
    public $password;
    public $rememberMe = true;
    public $email;

    private $user;
    private $status;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['name', 'password'], 'required'],
            //���� ������������ ������ �������� �� ���� Email � password ����������� ��� ���������
            [['email', 'password'], 'required' , 'on' => 'loginWithEmail'], //on ������ ��� ���� �������� �����������
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['loginWithEmail'] = ['password','email'];
        return $scenarios;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    //� ������� ��������� ���� �� �������� ������� ������� ����������
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();//����� ������� � ��������
            if (!$user || !$user->validatePassword($this->password)) { //�������� ��� � �������
                // ���� ��� �������� ������� ��� ����������� ������ �������� �� �������� � field �������� email ����� username
                $field = ($this->scenario === 'loginWithEmail') ? 'email' : 'name';
                //� ������ ��������� field ����� ��� ����������� �������� ����������� ������������ ������
                $this->addError($attribute, 'Incorrect ' . $field . ' or password.');
            }
            if (!$this->getStatus()) {
            $this->addError($attribute, 'Ne aktivirovanna zapis proidite na pochty');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() //������������� ���� �� 1 ���
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() //���� ����� ��� � ��
    {
        if ($this->user === null) {
            //���� ������������ �������� loginWithEmail
            if($this->scenario === 'loginWithEmail') :
                //������� ������������ �� Email
                $this->user = Myuser::findByEmail($this->email);
            else : //����� ������� �� username
             //����� ������� �� User
            $this->user = Myuser::findByUsername($this->name);
            endif;
        }

        return $this->user;
    }
    protected function getStatus()
    {
        if ($this->status === null) {
            //����� ������� �� User
            if($this->scenario === 'loginWithEmail') :
                $this->status = Myuser::findByStatusEmail($this->email);
            else :
                $this->status = Myuser::findByStatusName($this->name);
            endif;

            }
        return $this->status;
    }

    public function attributeLabels()
    {
        return
            [
                'name' => 'Name',
                'email' => 'email',
                'password' => 'password',
                'rememberMe' => 'rememberMe'

            ];
    }

}
