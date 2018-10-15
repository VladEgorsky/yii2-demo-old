<?php
/**
 * Created by PhpStorm.
 * User: vlad
 * Date: 19.08.18
 * Time: 6:56
 */

namespace common\components\controllers;

use common\components\FlashMessages;
use common\models\forms\LoginForm;
use common\models\forms\PasswordForm;
use common\models\forms\RegistrationForm;
use common\models\User;
use common\models\UserRegistration;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class BaseSiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'login', 'register', 'confirm-email', 'forgot-password', 'reset-password',
                    'logout', 'change-password', 'error', 'captcha', 'page'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'login', 'register', 'confirm-email', 'forgot-password', 'reset-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['error', 'captcha', 'page'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'page' => [
                'class' => 'yii\web\ViewAction',
                'defaultView' => '404.php',
                'viewPrefix' => 'pages',
            ],
        ];
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $previous = Url::previous('lastPageBeforeLogin');
        if (is_null($previous)) {
            $referrer = Yii::$app->request->referrer;

            if (is_null($referrer) || $referrer == Url::current([], true)) {
                $previous = Url::home(true);
            } else {
                $previous = $referrer;
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->remove('lastPageBeforeLogin');
            return $this->redirect($previous);
        }

        Url::remember($previous, 'lastPageBeforeLogin');
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionRegister()
    {
        $model = new UserRegistration();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->populateAndValidate()) {
            } elseif (!$model->sendInstruction()) {
                $mess = Yii::t('app', 'Error while sending Email. Please try again');
                Yii::$app->session->setFlash('error', $mess);
            } elseif (!$model->save(false)) {
                $mess = Yii::t('app', 'Error while saving data. Please try again');
                Yii::$app->session->setFlash('error', $mess);
            } else {
                FlashMessages::setMessage('REGISTERED_SUCCESSFULLY');
                return $this->redirect(['/site/page', 'view' => 'message']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionConfirmEmail()
    {
        $email = Yii::$app->request->get('email');
        $token = Yii::$app->request->get('token');
        if (!$email || !$token) {
            FlashMessages::setMessage('INCORRECT_INCOMING_PARAMETERS');
            return $this->redirect(['/site/page', 'view' => 'message']);
        }

        $user = User::find()->where(['email' => $email, 'password_reset_token' => $token])->one();
        if (!$user || $user->password_reset_token != $token) {
            FlashMessages::setMessage('INCORRECT_INCOMING_PARAMETERS');
            return $this->redirect(['/site/page', 'view' => 'message']);
        }

        $user->setAttributes(['password_reset_token' => null, 'email_verified' => 1], false);
        $user->save(false, ['password_reset_token', 'email_verified']);

        FlashMessages::setMessage('EMAIL_SUCCESSFULLY_CONFIRMED');
        return $this->redirect(['/site/page', 'view' => 'message']);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionForgotPassword()
    {
        $model = new PasswordForm(['scenario' => 'forgotPassword']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if (!$model->sendEmail()) {
                $mess = Yii::t('app', 'Error while sending Email. Please try again');
                Yii::$app->session->setFlash('error', $mess);
            }

            FlashMessages::setMessage("FORGOT_PASSWORD_SENT_EMAIL");
            return $this->redirect(['/site/page', 'view' => 'message']);
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return string|\yii\web\Response
     */
    public function actionResetPassword($token)
    {
        $token = Yii::$app->request->get('token');
        if (!$token) {
            FlashMessages::setMessage('INCORRECT_INCOMING_PARAMETERS');
            return $this->redirect(['/site/page', 'view' => 'message']);
        }

        $user = User::find()->where(['password_reset_token' => $token])->one();
        if (!$user) {
            FlashMessages::setMessage('INCORRECT_INCOMING_PARAMETERS');
            return $this->redirect(['/site/page', 'view' => 'message']);
        }

        $model = new PasswordForm(['scenario' => 'resetPassword']);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->populateAndSave($user)) {
                $mess = Yii::t('app', 'Your password successfully changed. Please try to login');
                Yii::$app->session->setFlash('success', $mess);
                return $this->redirect(['/site/login']);
            }

            $mess = Yii::t('app', 'Error while saving data');
            Yii::$app->session->setFlash('error', $mess);
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    public function actionChangePassword()
    {
        $model = new PasswordForm(['scenario' => 'changePassword']);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->populateAndSave(Yii::$app->user->identity)) {
                $mess = Yii::t('app', 'Your password successfully changed');
                Yii::$app->session->setFlash('success', $mess);
                return $this->goHome();
            }

            $mess = Yii::t('app', 'Error while saving data');
            Yii::$app->session->setFlash('error', $mess);
        }

        $this->layout = 'main';
        return $this->render('password', [
            'model' => $model,
        ]);
    }
}