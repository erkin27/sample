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
use Yii;
use yii\web\Controller;

class AuthController extends Controller
{
    public function actionLogin()
    {
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