<?php

namespace app\controllers;

use app\models\Classes;
use app\models\ClassesSearch;
use app\models\ClassStudents;
use app\models\ClassStudentsSearch;
use app\models\Courses;
use app\models\CoursesSearch;
use app\models\Lessons;
use app\models\Media;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CoursesController implements the CRUD actions for Courses model.
 */
class CoursesController extends Controller
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
                    'only' => ['index', 'classes', 'view', 'create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'classes', 'view', 'create', 'update', 'delete'],
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
     * Lists all Courses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CoursesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, 'teacher');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Classes models.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClasses($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->teacher_id);

        $searchModel = new ClassesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $model->id);

        return $this->render('classes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Lists all ClassStudents models.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRequests($id)
    {
        $model = $this->findModel($id);

        $this->checkStatus($id, 'Йде набір');

        $this->checkUser($model->teacher_id);

        $classes = Classes::find()->where(['course_id' => $id])->orderBy(['title' => SORT_ASC])->all();

        $searchModel = new ClassStudentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id, null, 'all');

        return $this->render('requests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'classes' => $classes,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Courses model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model =  $this->findModel($id);

        $this->checkUser($model->teacher_id);

        $class_count = Classes::find()->where(['course_id' => $id])->count();
        $lesson_count = Lessons::find()->where(['course_id' => $id])->count();
        $req_count = ClassStudents::find()->where(['course_id' => $id, 'status' => 'Новий'])->count();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->refresh();
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('view', [
            'model' => $model,
            'class_count' => $class_count,
            'lesson_count' => $lesson_count,
            'req_count' => $req_count,
        ]);
    }

    /**
     * Creates a new Courses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Courses();
        $model->teacher_id = Yii::$app->user->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->checkCount()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
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
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->teacher_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Courses model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkUser($model->teacher_id);

        $lessons = Lessons::find()->where(['course_id' => $id])->all();
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

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }

    public function checkUser($id)
    {
        if ((int)$id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }
    }

    public function checkStatus($id, $value)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
            if ($model->status === $value) {
                return $model;
            }

            throw new NotFoundHttpException('Запитувана сторінка не існує.');
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
