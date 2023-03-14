<?php

namespace app\controllers;

use app\models\Courses;
use app\models\CatalogueSearch;
use app\models\ClassStudents;
use app\models\Favorites;
use app\models\Lessons;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use function PHPUnit\Framework\once;

/**
 * CatalogueController implements the CRUD actions for Courses model.
 */
class CatalogueController extends Controller
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
                    'only' => ['index', 'view'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view'],
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
     * Lists all Courses models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CatalogueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, 'all');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists favorites Courses models.
     *
     * @return string
     */
    public function actionFavorites()
    {
        $searchModel = new CatalogueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, 'favorites');

        return $this->render('favorites', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists requests Courses models.
     *
     * @return string
     */
    public function actionRequests()
    {
        $searchModel = new CatalogueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, 'requests');

        return $this->render('requests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists my Courses models.
     *
     * @return string
     */
    public function actionMyCourses()
    {
        $searchModel = new CatalogueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, 'my-courses');

        return $this->render('my-courses', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $user_id = Yii::$app->user->id;

        $model = $this->findModel($id);
        $favorite = Favorites::findOne(['course_id' => $id, 'student_id' => $user_id]);
        $request = ClassStudents::findOne(['course_id' => $id, 'student_id' => $user_id]);

        if (User::findOne(['id' => $model->teacher_id])->status === 99) {
            throw new NotFoundHttpException('Викладача заблоковано. Доступ до навчального курсу закритий');
        }

        $lessons = new ActiveDataProvider([
            'query' => Lessons::find()->where(['course_id' => $id]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
                ]
            ],
        ]);

        $class = ClassStudents::findOne(
            [
                'course_id' => $model->id,
                'student_id' => Yii::$app->user->id
            ]
        );

        return $this->render('view', [
            'class' => $class,
            'favorite' => $favorite,
            'lessons' => $lessons,
            'model' => $model,
            'request' => $request,
        ]);
    }

    /**
     * Creates a new ClassStudents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionRequest($id)
    {
        $user_id = Yii::$app->user->id;

        $this->findModel($id);

        if (($model = ClassStudents::findOne(['course_id' => $id, 'student_id' => $user_id])) !== null) {
            $course_status = $this->findCourse($model->course_id)->status;
            if (($course_status === 'Йде набір') && ($model->status !== 'Відхилено')) {
                $model->delete();

                Yii::$app->session->setFlash('success', 'Ви успішно відмінили заявку на проходження курсу.');
            } else {
                Yii::$app->session->setFlash('warning', 'Ви не можете відмінили заявку на проходження курсу, тепер це може зробити тільки викладач.');
            }
        } else {
            $model = new ClassStudents();
            $model->course_id = $id;
            $model->student_id = $user_id;

            if ($model->save()) {
                $student = User::findOne(['id' => $model->student_id]);
                $student_name = $student->last_name . ' ' . mb_substr($student->first_name, 0, 1) . '.';
                $course_title = $this->findCourse($model->course_id)->title;
                $subject = 'Відповідь по заявці "' . Yii::$app->name . '"';
                $body = '<p><strong>Відповідь по заявці "' . Yii::$app->name . '"</strong></p>
                        <hr>
                        <p>Шановний(а), ' . $student_name . ' </p>
                        <p>Вашу заявку на проходження навчального курсу "' . $course_title . '" успішно надіслано. Очікуйте на відповідь, вам надійде повідомлення на вашу пошту після її розгляду.</p>';

                if ($model->contact($student->email, $subject, $body)) {
                    Yii::$app->session->setFlash('success', 'Ви успішно подали заявку на проходження курсу.');
                } else {
                    Yii::$app->session->setFlash('error', 'Виникла помилка, спробуйте ще раз.');
                }
            }
        }

        return $this->redirect(['view', 'id' => $model->course_id]);
    }

    /**
     * Creates a new Favorites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionFavorite($id)
    {
        $user_id = Yii::$app->user->id;

        $this->findModel($id);

        if (($model = Favorites::findOne(['course_id' => $id, 'student_id' => $user_id])) !== null) {
            $model->delete();

            Yii::$app->session->setFlash('success', 'Курс видалено зі списку обраних.');
        } else {
            $model = new Favorites();
            $model->course_id = $id;
            $model->student_id = $user_id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Курс додано до списку обраних.');
            }
        }

        return $this->redirect(['view', 'id' => $model->course_id]);
    }

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCourse($id)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
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
    protected function findModel($id)
    {
        if (($model = Courses::find()->where(['id' => $id])->andWhere(['<>', 'status', 'Заблоковано'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запитувана сторінка не існує.');
    }
}
