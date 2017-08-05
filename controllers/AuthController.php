<?php
/**
 * Created by PhpStorm.
 * User: erkin
 * Date: 09.05.17
 * Time: 23:42
 */

namespace app\controllers;


use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use nodge\eauth\ErrorException;
use nodge\eauth\openid\ControllerBehavior;
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
    public function behaviors()
    {
        return [
            'eauth' => [
                // required to disable csrf validation on OpenID requests
                'class' => ControllerBehavior::className(),
                'only' => array('login'),
            ],
        ];
    }

    public function actionLogin()
    {
        $serviceName = Yii::$app->getRequest()->getQueryParam('service');
        if (isset($serviceName)) {
            /** @var $eauth \nodge\eauth\ServiceBase */
            $eauth = Yii::$app->get('eauth')->getIdentity($serviceName);
            $eauth->setRedirectUrl(Yii::$app->getUser()->getReturnUrl());
            $cancelUrl = str_replace('//', '/', Yii::$app->getUrlManager()->createAbsoluteUrl('auth/login'));
            $cancelUrl = str_replace(':/', '://', $cancelUrl);
            $eauth->setCancelUrl($cancelUrl);

            try {
                if ($eauth->authenticate()) {
//                  var_dump($eauth->getIsAuthenticated(), $eauth->getAttributes()); exit;

                    $identity = User::findByEAuth($eauth);
                    Yii::$app->getUser()->login($identity);

                     if(!User::findOne(['name' => $identity->name])){
                         $user = new User();
                         $user->name = $identity->name;
                         if ($identity->profile['service'] == 'facebook') {
                             $user->facebook = true;
                         }
                         $user->create();
                     }

                    // special redirect with closing popup window
                    $eauth->redirect();
                }
                else {
                    // close popup window and redirect to cancelUrl
                    $eauth->cancel();
                }
            }
            catch (ErrorException $e) {
                // save error to show it later
                Yii::$app->getSession()->setFlash('error', 'EAuthException: '.$e->getMessage());

                // close popup window and redirect to cancelUrl
//              $eauth->cancel();
                $eauth->redirect($eauth->getCancelUrl());
            }
        }
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest() {

        $user = User::findOne(1);

        \Yii::$app->user->logout();

       // var_dump(\Yii::$app->user); die();

        echo \Yii::$app->user->isGuest ? 'Пользователь гость' : 'Пользователь авторизован';
    }

    public function actionSignup() {
        $model = new SignupForm();

        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if($model->signup()) {

                $this->redirect(['auth/login']);

            }
        }

        return $this->render('signup', [
           'model' => $model
        ]);
    }

}