<?php

namespace app\controllers;

use app\models\AuthAssignment;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Content;
use app\models\ForgotPasswordForm;
use app\models\SignUpForm;
use app\models\User;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['get'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'content' => $this->findContent(),
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * SignUp action.
     *
     * @return Response|string
     */
    public function actionSignUp()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $link = $protocol . $_SERVER['HTTP_HOST'];
        $model = new SignUpForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->checkEmail()) {
                if ($model->checkPassword()) {
                    if ($model->term) {
                        if (($user = User::findOne(['email' => $model->email, 'status' => 0])) === null) {
                            $user = new User();
                        }

                        $user->email = $model->email;
                        $user->first_name = $model->first_name;
                        $user->middle_name = $model->middle_name == '' ? null : $model->middle_name;
                        $user->last_name = $model->last_name;
                        $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
                        $user->generateAuthKey();
                        $user->generatePasswordResetToken();



                        if ($user->save()) {
                            $user_id = User::findOne(['email' => $user->email])->id;
                            if (($add_role = AuthAssignment::findOne(['user_id' => $user_id])) === null) {
                                $add_role = new AuthAssignment();
                            }
                            $add_role->item_name = $model->role;
                            $add_role->user_id = $user_id;

                            if ($add_role->save()) {
                                $subject = 'Реєстрація на сайті "' . Yii::$app->name . '"';
                                $body = '<p><strong>Реєстрація на сайті "' . Yii::$app->name . '"</strong></p>
                                         <p>Підтвердіть реєстрацію перейшовши за посиланням нижче.</p>
                                         <hr>
                                         <p><u>Посилання</u>: <a href="' . $link . '/site/confirmation/' . $user->password_reset_token . '">' . $link . '/site/confirmation/' . $user->password_reset_token . '</a></p>';

                                if ($model->contact($subject, $body)) {
                                    Yii::$app->session->setFlash('success', 'Заявку надіслано на ваш e-mai, підтвердіть її.');

                                    return $this->redirect('sign-up');
                                }
                            } else {
                                $errorMsg = 'Виникла помилка, обновіть сторінку і спробуйте ще раз.';
                                $model->addError('role', $errorMsg);
                            }

                            return $this->redirect(['login']);
                        }
                    } else {
                        $errorMsg = 'Ознайомтесь з угодою користувача.';
                        $model->addError('term', $errorMsg);
                    }
                } else {
                    $errorMsg = 'Паролі відрізняються.';
                    $model->addError('password', $errorMsg);
                }
            } else {
                $errorMsg = 'E-mail вже занято.';
                $model->addError('email', $errorMsg);
            }
        }

        return $this->render('/site/sign-up', [
            'model' => $model,
        ]);
    }


    /**
     * SignUp confirmation action.
     *
     * @return string
     */
    public function actionConfirmation($id)
    {
        if (($model = User::findOne(['password_reset_token' => $id])) !== null) {
            $model->password_reset_token = null;
            $model->status = 10;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пошту підтверджено, тепер ви можете авторизуватись.');

                return $this->redirect(['login']);
            }
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Displays forgot-password page.
     *
     * @return string
     */
    public function actionForgotPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new ForgotPasswordForm();
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $link = $protocol . $_SERVER['HTTP_HOST'];

        if (($id = Yii::$app->request->get('id')) !== null) {
            if (($user = User::findOne(['password_reset_token' => $id])) !== null) {
                $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
                $max = 8;
                $size = strlen($chars) - 1;
                $password = null;

                while ($max--)
                    $password .= $chars[rand(0, $size)];

                $user->password_reset_token = null;
                $user->setPassword($password);

                $email = $user->email;

                if ($user->save()) {
                    $subject = 'Відновлення паролю в додатку "' . Yii::$app->name . '"';
                    $body = '<p><strong>Відновлення паролю в додатку "' . Yii::$app->name . '"</strong></p>
                            <p>Ваш новий пароль для входу в систему. <b>Нікому не передавайте його.</b></p>
                            <hr>
                            <p><u>Пароль</u>: ' . $password . '</p>';


                    Yii::$app->mailer->compose()
                        ->setTo($email)
                        ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name . ' mailer'])
                        ->setSubject($subject)
                        ->setHtmlBody($body)
                        ->send();

                    Yii::$app->session->setFlash('success', 'Новий пароль надіслано на ваш e-mail.');

                    return $this->redirect('/site/login');
                }
            }

            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->checkEmail()) {
                $user = User::findOne(['email' => $model->email]);
                $user->generatePasswordResetToken();

                if ($user->save()) {
                    $subject = 'Відновлення паролю в додатку "' . Yii::$app->name . '"';
                    $body = '<p><strong>Відновлення паролю в додатку "' . Yii::$app->name . '"</strong></p>
                            <p>Підтвердіть відновлення перейшовши за посиланням нижче.</p>
                            <hr>
                            <p><u>Посилання</u>: <a href="' . $link . '/site/forgot-password/' . $user->password_reset_token . '">' . $link . '/site/forgot-password/' . $user->password_reset_token . '</a></p>';

                    if ($model->contact($subject, $body)) {
                        Yii::$app->session->setFlash('success', 'Заявку надіслано на ваш e-mail, підтвердіть її.');

                        return $this->redirect('login');
                    }
                }
            } else {
                $errorMsg = 'Користувача з таким e-mail не знайдено.';
                $model->addError('email', $errorMsg);
            }
        }

        return $this->render('forgot-password', [
            'model' => $model,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post())) {
            $subject = 'Зворотній звʼязок додатку "' . Yii::$app->name . '"';
            $body = '<p><strong>Зворотній звʼязок додатку "' . Yii::$app->name . '"</strong></p>
                    <hr>
                    <p><b>ПІБ</b>: ' . $model->name . '</p>
                    <p><b>E-mail</b>: ' . $model->email . '</p>
                    <hr>
                    <p>' . $model->body . '</p>';

            if ($model->contact($subject, $body)) {
                Yii::$app->session->setFlash('success', 'Дякуємо за ваше звернення. Ми відповімо вам якомога швидше.');

                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Виникла помилка, спробуйте надіслати форму ще раз.');
            }
        }

        return $this->render('contact', [
            'content' => $this->findContent(),
            'model' => $model,
        ]);
    }

    /**
     * Displays policy page.
     *
     * @return string
     */
    public function actionPolicy()
    {
        return $this->render('policy', [
            'content' => $this->findContent(),
        ]);
    }

    /**
     * Finds the Content model based on its URL value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findContent()
    {
        $url = '/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

        if (($model = Content::findOne(['url' => $url])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
