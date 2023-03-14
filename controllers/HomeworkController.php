<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Homework;
use app\models\HomeworkSearch;
use app\models\Lessons;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HomeworkController implements the CRUD actions for Homework model.
 */
class HomeworkController extends Controller
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
                            'actions' => ['index', 'update'],
                            'allow' => true,
                            'roles' => ['teacher'],
                        ],
                        [
                            'actions' => ['delete'],
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
     * Lists all Homework models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $searchModel = new HomeworkSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Updates an existing Homework model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $lesson = $this->findLessonModel($model->lesson_id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');

            return $this->redirect(['index', 'id' => $lesson->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    /**
     * Deletes an existing Homework model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $lesson = $this->findLessonModel($model->lesson_id);
        $course = $this->findCourseModel($lesson->course_id);

        if ($model->student_id !== Yii::$app->user->id) {
            $this->checkUser($course->id);
        }

        unlink('files/homeworks/' . $model->file);

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['/lessons/hometask', 'id' => $lesson->id]);
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
     * Finds the Homework model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Homework the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Homework::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Lessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findLessonModel($id)
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
