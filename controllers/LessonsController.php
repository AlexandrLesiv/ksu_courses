<?php

namespace app\controllers;

use app\models\ClassStudents;
use app\models\Courses;
use app\models\Homework;
use app\models\Lessons;
use app\models\LessonsSearch;
use app\models\Media;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * LessonsController implements the CRUD actions for Lessons model.
 */
class LessonsController extends Controller
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
                    'only' => ['index', 'view', 'create', 'update', 'delete', 'info', 'hometask'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['teacher'],
                        ],
                        [
                            'actions' => ['info', 'hometask'],
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
     * Lists all Lessons models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $model = $this->findCourseModel($id);

        $this->checkUser($id);

        $searchModel = new LessonsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Lessons model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $course = $this->findCourseModel($model->course_id);

        $this->checkUser($course->id);

        $media_count = Media::find()->where(['lesson_id' => $id])->count();
        $homework_count = Homework::find()->where(['lesson_id' => $id, 'comment' => null])->count();

        return $this->render('view', [
            'course' => $course,
            'homework_count' => $homework_count,
            'media_count' => $media_count,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Lessons model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionInfo($id)
    {
        $model = $this->findModel($id);
        $course = $this->findCourseModel($model->course_id);

        $class_student = ClassStudents::findOne(
            [
                'course_id' => $course->id,
                'student_id' => Yii::$app->user->id,
                'status' => 'Підтверджено'
            ]
        );

        if ($class_student !== null) {
            if (($course->status === 'Новий') || ($course->status === 'Йде набір')) {
                throw new ForbiddenHttpException('Лекція недоступна. Очікуйте, коли розпочнеться курс.');
            }

            $media = Media::find()->where(['lesson_id' => $id])->andWhere(['<>', 'type', 'Домашня робота'])->all();
            $hometask = Media::find()->where(['lesson_id' => $id, 'type' => 'Домашня робота'])->all();

            return $this->render('info', [
                'class_student' => $class_student,
                'course' => $course,
                'hometask' => $hometask,
                'media' => $media,
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Displays a single Lessons model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHometask($id)
    {
        $model = $this->findModel($id);
        $course = $this->findCourseModel($model->course_id);

        $class_student = ClassStudents::findOne(
            [
                'course_id' => $course->id,
                'student_id' => Yii::$app->user->id,
                'status' => 'Підтверджено'
            ]
        );

        if ($class_student !== null) {
            if (($course->status === 'Новий') || ($course->status === 'Йде набір')) {
                throw new ForbiddenHttpException('Лекція недоступна. Очікуйте, коли розпочнеться курс.');
            }

            $hometask = Media::find()->where(['lesson_id' => $id, 'type' => 'Домашня робота'])->all();

            if (count($hometask) === 0) {
                throw new ForbiddenHttpException('Завдання для домашньої роботи не додано.');
            }

            $dataProvider = new ActiveDataProvider([
                'query' => Homework::find()->where(['lesson_id' => $id, 'student_id' => Yii::$app->user->id]),
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                    ]
                ],
            ]);

            $homework = new Homework();
            $homework->student_id = Yii::$app->user->id;
            $homework->lesson_id = $id;

            if ($this->request->isPost) {
                if ($homework->load($this->request->post())) {
                    $homework->imageFile = UploadedFile::getInstance($homework, 'imageFile');
                    if ($homework->imageFile) {
                        $homework->file = md5(microtime() . rand(0, 9999)) . '.' . $homework->imageFile->extension;

                        if ($homework->save() && $homework->upload()) {
                            Yii::$app->session->setFlash('success', 'Операція успішна.');

                            return $this->redirect(['hometask', 'id' => $id]);
                        }
                    } else {
                        $errorMsg = 'Будь ласка, прикріпіть файл.';
                        Yii::$app->session->setFlash('warning', $errorMsg);
                        $homework->addError('imageFile', $errorMsg);
                    }
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('hometask', [
                'class_student' => $class_student,
                'course' => $course,
                'dataProvider' => $dataProvider,
                'hometask' => $hometask,
                'homework' => $homework,
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Creates a new Lessons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $course = $this->findCourseModel($id);

        $this->checkUser($id);

        $model = new Lessons();
        $model->course_id = $id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'course' => $course,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lessons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $course = $this->findCourseModel($model->course_id);

        $this->checkUser($course->id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'course' => $course,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lessons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->course_id);

        $media = Media::find()->where(['lesson_id' => $id])->all();
        foreach ($media as $item) {
            if ($item->file !== null) {
                if ($item->type === 'Зображення') {
                    unlink('img/media/' . $item->file);
                } elseif (($item->type === 'Файл') || ($item->type === 'Домашня робота')) {
                    unlink('files/' . $item->file);
                }
            }
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['index', 'id' => $model->course_id]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function checkUser($id)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
            if ($model->teacher_id === Yii::$app->user->id) {
                return $model;
            }

            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Finds the Lessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Lessons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lessons::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCourseModel($id)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
