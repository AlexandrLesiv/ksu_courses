<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\Courses;
use app\models\Lessons;
use app\models\Media;
use app\models\PasswordForm;
use app\models\TeacherInfo;
use app\models\User;
use app\models\UserSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['teachers', 'students', 'banned', 'view', 'profile', 'update', 'update-profile', 'ban', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['teachers', 'students', 'banned', 'view', 'ban', 'delete'],
                            'allow' => true,
                            'roles' => ['admin'],
                        ],
                        [
                            'actions' => ['profile', 'update-profile'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['info'],
                            'allow' => true,
                            'roles' => ['student'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all teachers User models.
     *
     * @return string
     */
    public function actionTeachers()
    {
        $user_arr = [];
        $teachers = AuthAssignment::find()->where(['item_name' => 'teacher'])->all();
        foreach ($teachers as $item) {
            array_push($user_arr, $item->user_id);
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $user_arr, 10);

        return $this->render('teachers', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all students User models.
     *
     * @return string
     */
    public function actionStudents()
    {
        $user_arr = [];
        $students = AuthAssignment::find()->where(['item_name' => 'student'])->all();
        foreach ($students as $item) {
            array_push($user_arr, $item->user_id);
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $user_arr, 10);

        return $this->render('students', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all banned User models.
     *
     * @return string
     */
    public function actionBanned()
    {
        $user_arr = [];
        $users = AuthAssignment::find()->where(['<>', 'item_name', 'admin'])->all();
        foreach ($users as $item) {
            array_push($user_arr, $item->user_id);
        }

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $user_arr, 99);

        return $this->render('banned', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->checkAdmin($id);

        $model = $this->findModel($id);

        $role = AuthAssignment::findOne(['user_id' => $id])->item_name;

        return $this->render('view', [
            'model' => $model,
            'role' => $role,
        ]);
    }

    public function actionInfo($id)
    {
        $model = $this->findModel($id);
        $info = TeacherInfo::findOne(['teacher_id' => $id]);

        if ($model->status === 99) {
            throw new NotFoundHttpException('Викладача заблоковано.');
        }

        $this->checkRole($id, 'teacher');

        $courses = new ActiveDataProvider([
            'query' => Courses::find()->where(['teacher_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('info', [
            'courses' => $courses,
            'info' => $info,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single User model.
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionProfile()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);
        $password = new PasswordForm();

        $info = TeacherInfo::findOne(['teacher_id' => $id]);
        $role = AuthAssignment::findOne(['user_id' => $id])->item_name;

        if ($this->request->isPost) {
            if ($password->load($this->request->post())) {
                if ($password->checkPassword()) {
                    $model->setPassword($password->password);

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Пароль успішно змінено.');

                        return $this->refresh();
                    }
                } else {
                    Yii::$app->session->setFlash('danger', 'Помилка при обробці форми. Паролі відрізняються.');
                    $errorMsg = 'Паролі відрізняються.';
                    $password->addError('password', $errorMsg);
                }
            }
        }

        return $this->render('profile', [
            'info' => $info,
            'model' => $model,
            'password' => $password,
            'role' => $role,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $this->checkAdmin($id);

        $model = $this->findModel($id);

        $role = AuthAssignment::findOne(['user_id' => $id])->item_name;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->checkEmail()) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $errorMsg = 'E-mail вже занято.';
                $model->addError('email', $errorMsg);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'role' => $role,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'profile' page.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateProfile()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);

        $role = AuthAssignment::findOne(['user_id' => $id])->item_name;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if ($model->checkEmail()) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->redirect(['profile']);
            } else {
                $errorMsg = 'E-mail вже занято.';
                $model->addError('email', $errorMsg);
            }
        }

        return $this->render('update-profile', [
            'model' => $model,
            'role' => $role,
        ]);
    }

    /**
     * Updates an existing TeacherInfo model.
     * If update is successful, the browser will be redirected to the 'profile' page.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateInfo()
    {
        $id = Yii::$app->user->id;

        if (($model = TeacherInfo::findOne(['teacher_id' => $id])) === null) {
            $model = new TeacherInfo();
        }
        $model->teacher_id = $id;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');

            return $this->redirect(['profile']);
        }

        return $this->render('update-info', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'banned' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBan($id)
    {
        $this->checkAdmin($id);

        $model = $this->findModel($id);

        $model->status = $model->status === 99 ? 10 : 99;
        $model->generateAuthKey();
        $msg = $model->status === 99 ? 'Користувача успішно заблоковано.' : 'Користувача успішно розблоковано.';

        if ($model->save()) {
            Yii::$app->session->setFlash('success', $msg);

            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->checkAdmin($id);

        $model = $this->findModel($id);

        $courses = Courses::find()->where(['teacher_id' => $id])->all();
        foreach ($courses as $course) {
            $lessons = Lessons::find()->where(['course_id' => $course->id])->all();
            foreach ($lessons as $value) {
                $media = Media::find()->where(['lesson_id' => $value->id])->all();
                foreach ($media as $item) {
                    if ($item->file !== null) {
                        if ($item->type === 'Зображення') {
                            unlink('img/media/' . $item->file);
                        } elseif (($item->type === 'Файл') || ($item->type === 'Домашня робота')) {
                            unlink('files/' . $item->file);
                        }
                    }
                }
            }
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::find()->where(['id' => $id])->andWhere(['<>', 'status', 0])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    public function checkAdmin($id)
    {
        if ((int)$id === 1) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }
    }

    public function checkRole($id, $role)
    {
        if ((AuthAssignment::findOne(['item_name' => $role, 'user_id' => $id])) === null) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }
    }
}
