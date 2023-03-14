<?php

namespace app\controllers;

use app\models\ClassStudents;
use app\models\Courses;
use app\models\Forum;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ForumController implements the CRUD actions for Forum model.
 */
class ForumController extends Controller
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
                        [
                            'actions' => ['index'],
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
     * Lists all Forum models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $course = $this->findCourseModel($id);

        if (($course->teacher_id === Yii::$app->user->id) || (ClassStudents::findOne(['course_id' => $course->id, 'student_id' => Yii::$app->user->id, 'status' => 'Підтверджено']) !== null)) {
            $user_id = [];
            array_push($user_id, $course->teacher_id);
            $class_students = ClassStudents::find()->where(['course_id' => $course->id, 'status' => 'Підтверджено'])->all();
            foreach ($class_students as $item) {
                array_push($user_id, $item->student_id);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => Forum::find()->where(['user_id' => $user_id]),
                'pagination' => [
                    'pageSize' => 20
                ],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                    ]
                ],
            ]);

            $model = new Forum();
            $model->course_id = $id;
            $model->user_id = Yii::$app->user->id;
            if ($this->request->isPost) {
                if ($model->load($this->request->post()) && $model->save()) {
                    Yii::$app->session->setFlash('success', 'Операція успішна.');

                    return $this->redirect(['index', 'id' => $model->course_id]);
                }
            } else {
                $model->loadDefaultValues();
            }

            return $this->render('index', [
                'course' => $course,
                'dataProvider' => $dataProvider,
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    /**
     * Deletes an existing Forum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->course_id);

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
     * Finds the Forum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Forum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Forum::findOne(['id' => $id])) !== null) {
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
