<?php

namespace app\controllers;

use app\models\Classes;
use app\models\Courses;
use app\models\Lessons;
use app\models\Timetable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClassesController implements the CRUD actions for Classes model.
 */
class ClassesController extends Controller
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
                    'only' => ['view', 'create', 'timetable-status', 'timetable-update', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['view', 'create', 'timetable-status', 'timetable-update', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['teacher'],
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
     * Displays a single Classes model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $course = $this->findCourseModel($model->course_id);

        $this->checkUser($course->id);

        $lessons = new ActiveDataProvider([
            'query' => Lessons::find()->where(['course_id' => $course->id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('view', [
            'course' => $course,
            'model' => $model,
            'lessons' => $lessons,
        ]);
    }

    /**
     * Creates a new Classes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $course = $this->findCourseModel($id);

        $this->checkUser($course->id);

        $model = new Classes();
        $model->course_id = $id;

        $course_count = Courses::findOne(['id' => $id])->students_count;
        $current_count = Classes::find()->where(['course_id' => $id])->sum('students_count');

        $max_count = $course_count - $current_count;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->checkCount()) {
                    if ($model->students_count <= $max_count) {
                        if ($model->save()) {
                            Yii::$app->session->setFlash('success', 'Операція успішна.');

                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } else {
                        $errorMsg = 'Максимальна к-ть. ' . $max_count;
                        $model->addError('students_count', $errorMsg);
                    }
                } else {
                    $errorMsg = 'Кількість студентів менше 1.';
                    $model->addError('students_count', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'course' => $course,
            'max_count' => $max_count,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTimetableUpdate($id)
    {
        $params = explode('-', $id);
        $class_id = $params[0];
        $lesson_id = $params[1];

        $class = $this->findModel($class_id);

        $course = $this->findCourseModel($class->course_id);

        $this->checkUser($course->id);

        if (($model = Timetable::findOne(['class_id' => $class_id, 'lesson_id' => $lesson_id])) === null) {
            $model = new Timetable();

            $model->class_id = $class_id;
            $model->lesson_id = $lesson_id;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->compareDate()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $class_id]);
                    }
                } else {
                    $errorMsg = 'Вкажіть правильно дату та час.';
                    $model->addError('start', $errorMsg);
                }
            }
        }

        return $this->render('timetable-update', [
            'class' => $class,
            'course' => $course,
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Timetable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionTimetableStatus($id)
    {
        $params = explode('-', $id);
        $class_id = $params[0];
        $lesson_id = $params[1];

        $class = $this->findModel($class_id);

        $course = $this->findCourseModel($class->course_id);

        $this->checkUser($course->id);

        if (($model = Timetable::findOne(['class_id' => $class_id, 'lesson_id' => $lesson_id])) !== null) {
            $model->status = $model->status === 'Новий' ? 'Завершено' : 'Новий';

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->redirect(['view', 'id' => $class_id]);
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Заповніть розклад. Зараз не можна змінити статус.');

            return $this->redirect(['view', 'id' => $class_id]);
        }
    }

    /**
     * Updates an existing Classes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $students_count = $model->students_count;

        $course = $this->findCourseModel($model->course_id);

        $this->checkUser($course->id);

        $course_count = Courses::findOne(['id' => $model->course_id])->students_count;
        $current_count = Classes::find()->where(['course_id' => $model->course_id])->sum('students_count');

        $max_count = $course_count - $current_count + $students_count;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->checkCount()) {
                    if ($model->students_count <= $max_count) {
                        if ($model->save()) {
                            Yii::$app->session->setFlash('success', 'Операція успішна.');

                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } else {
                        $errorMsg = 'Максимальна к-ть. ' . $max_count;
                        $model->addError('students_count', $errorMsg);
                    }
                } else {
                    $errorMsg = 'Кількість студентів менше 1.';
                    $model->addError('students_count', $errorMsg);
                }
            }
        }

        return $this->render('update', [
            'course' => $course,
            'max_count' => $max_count,
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Classes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);

        $course = $this->findCourseModel($model->course_id);

        $this->checkUser($course->id);

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['/courses/classes', 'id' => $model->course_id]);
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

    /**
     * Finds the Classes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Classes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Classes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
