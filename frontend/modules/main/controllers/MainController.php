<?php

namespace app\modules\main\controllers;
use common\models\Myuser;
use frontend\components\Common;
use frontend\models\ContactForm;
use frontend\models\LoginForm;
use frontend\models\RegisterForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SendEmailForm;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\debug\models\search\Log;
//��� ���������
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\AccountActivation;
use yii\web\BadRequestHttpException;

class MainController extends \yii\web\Controller
{
    public $layout = 'inner';
    //public $layout = 'bootstrap'; // ��� ��� ���� ������ ������������ ������ ������
    // main ���������� ��� ������ ����� ������� ������ � ������� �����

    /*public function behaviors()
    {
        return [
            'access' => [ //��������� ���� ������� �������������
                'class' => AccessControl::className(),
                'only' => ['login','register'],//������������ ��� ������  actions
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => false, //�� ����� ��������� ��������
                        'controllers' => ['/main/main/MainController'],
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,//����� ��������� ��������
                        'controllers' => ['/main/main/MainController'],
                        'roles' => ['@'],//������ �������������� ������������
                                    //['?'] ������ �����
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }
*/

    public function actions(){ //����� ��������� ations

        return [
            'error' => [ //��� ������ ������ Found 404
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [ //����� ��� ���� �������
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
              //  'height' => 50,

            ],
        ];
    }


    public function actionLogout() //����� �� ������� (������������� Yii)
    {
        Yii::$app->user->logout();

        return $this->goHome(); //������ �������� �������
    }

    public function actionRegister()
    {
      //  $model = new RegisterForm();
        $model = Yii::$app->params['emailActivation'] ? new RegisterForm(['scenario' => 'emailActivation']) : new RegisterForm();

        if($model->load(Yii::$app->request->post()) && $model->goreg())
        {
            Yii::$app->session->setFlash('success','Registe good111');
                if($model->status === Myuser::STATUS_ACTIVE){}
            else
            {
                //������� ��������� ������ � ������
                if($model->sendActivationEmail(/*$model*/)):
                    //���� ���������� ������� ��������� �� ������
                    Yii::$app->session->setFlash('success','Pismo otpravleno na emain <strong>'.Html::encode($model->email).'</strong>');
                    else:
                        Yii::$app->session->setFlash('success','Owibka otpravki pisma');
                        endif;
            }
            return $this->refresh();
        }
        return $this->render('register',
            [
                'model' => $model
            ]);
    }
    public function actionLogin(){ //������ �����

    //    $model = new LoginForm();
        if(!Yii::$app->user->isGuest)  return $this->goHome(); //������ ������������ ���� ��� ���������

        $loginWithEmail = Yii::$app->params['loginWithEmail']; //�������� �������� �� ��������� (true)
        $model = $loginWithEmail ? new LoginForm(['scenario' => 'loginWithEmail']) : new LoginForm();

        if($model->load(\Yii::$app->request->post()) && $model->login()){

            $this->goBack(); //���������� �� �� �� �������� � ������� �� ������� �� ����� ������
        }

        return $this->render('login',[
            'model' => $model

        ]);
    }

    public function actionContact(){//����� �������� �����

        $model = new ContactForm();
        if($model->load(\Yii::$app->request->post())&& $model->validate()){
           // Yii::$app->common->sendMail($model->subject,$model->body);
            $dop = array();
            $dop[0]= $model->email;
            $dop[1] = $model->name;
            Common::sendMail($model->subject,$model->body,$dop);
            Yii::$app->session->setFlash('success','Pismo yspewno otpravleno');

            return $this->refresh();
        }
        return $this->render('contact',[

            'model' => $model
        ]);
    }

    //������ ������� ����� ������������ �������� �� ������
    //� ������ ��������� $key  � ��������� ������
    public function actionAccountActivation(/*$key*/) //���� ����� ��������� key �� ������ �� ���������� ��� ���� Key
    {
        $key = Yii::$app->request->get('key');
        try
        {
            //������ ����� ������ activateaccount , ����� ��������� ���������� ����������� � ��������� �� ������
            //���� ������ ���������� InvalidParamExeption
            $user = new AccountActivation($key);
        }
        catch(InvalidParamException $e)//���� ���� ����������
        {
            // �������� ���������� badrequest (������ ������ ��� 400) � ���������� ���������
            // InvalidParamExeption �� AccountActivation
            throw new BadRequestHttpException($e->getMessage());
        }

        //���� ������ ��� ������
        if($user->activateAccount())://���������� ������������
        Yii::$app->session->setFlash('success','Aktivaciya yspewna');
            return $this->redirect(Url::to(['/main/main/login'])); //������ ������� �� �������� �����
        else: //��������� �� ������

            Yii::$app->session->setFlash('success','Owiblka aktivacii');
            Yii::error('Owiblka aktivacii');
            endif;
        return $this->redirect(Url::to(['/main/main/login'])); //������ ������� �� �������� �����

    }

    public function actionSendEmail()
    {
        $model = new SendEmailForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                    if($model->sendEmail()):
                       // Yii::$app->getSession()->setFlash('warning','Prover email');
                        Yii::$app->session->setFlash('success','Proverte email');
                        return $this->goHome();
                        else:
                           // Yii::$app->getSession()->setFlash('error','Nelzya sbrosit parol');
                            Yii::$app->session->setFlash('success','Nelzya sbrosit parol');
                            endif;
            }
        }

        return $this->render('sendEmail', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword()
    {
        $key = Yii::$app->request->get('key');
        try{
            //��� ��� � ������ ������� ���� ���� ��������!
            $model = new ResetPasswordForm($key);
        }
        catch(InvalidParamException $e){
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())) {
            //� ������� � ���������� ��������� ������� ������ ������ ������ � �������� �����
            if ($model->validate() && $model->resetPassword()) {
                //Yii::$app->getSession()->setFlash('warning','Parol yspewno imenen');
                Yii::$app->session->setFlash('success','Parol yspewno izmenen');
                return $this->redirect('/main/main/login');
            }
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

}
