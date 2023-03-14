<?php

namespace app\controllers;

use app\models\Classes;
use app\models\ClassStudents;
use app\models\ClassStudentsSearch;
use app\models\Courses;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClassStudentsController implements the CRUD actions for ClassStudents model.
 */
class ClassStudentsController extends Controller
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
                    'only' => ['index', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'delete'],
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
     * Lists all ClassStudents models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $class = $this->findClass($id);

        $searchModel = new ClassStudentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $class->course_id, $id, 'Підтверджено');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'class' => $class,
        ]);
    }

    /**
     * Deletes an existing ClassStudents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->course_id);

        $class_id = $model->class_id;
        $model->class_id = null;
        $model->status = 'Відхилено';

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');

            return $this->redirect(['index', 'id' => $class_id]);
        }
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
     * Finds the ClassStudents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ClassStudents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClassStudents::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    protected function findClass($id)
    {
        if (($model = Classes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
