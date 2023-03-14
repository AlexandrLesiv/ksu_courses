<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Lessons;
use app\models\Media;
use app\models\MediaSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
                    'only' => ['index', 'view', 'create-file', 'create-image', 'create-hometask', 'create-text', 'create-video', 'update', 'delete', 'delete-file' , 'download'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create-file', 'create-image', 'create-hometask', 'create-text', 'create-video', 'update', 'delete', 'delete-file' , 'download'],
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
     * Lists all Media models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, $id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Displays a single Media model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $lesson = $this->findLessonModel($model->lesson_id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        return $this->render('view', [
            'model' => $model,
            'course' => $course,
            'lesson' => $lesson,
        ]);
    }

    /**
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreateFile($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $model = new Media();
        $model->lesson_id = $id;
        $model->type = 'Файл';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;

                    if ($model->save() && $model->upload()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Будь ласка, прикріпіть файл.';
                    $model->addError('imageFile', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    public function actionCreateImage($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $model = new Media();
        $model->lesson_id = $id;
        $model->type = 'Зображення';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;

                    if ($model->save() && $model->upload()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Будь ласка, прикріпіть зображення.';
                    $model->addError('imageFile', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    public function actionCreateHometask($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $model = new Media();
        $model->lesson_id = $id;
        $model->type = 'Домашня робота';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validateDesc()) {
                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    if ($model->imageFile) {
                        $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;
                        $model->upload();
                    }

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Необхідно заповнити "Текст/примітка".';
                    $model->addError('description', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    public function actionCreateText($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $model = new Media();
        $model->lesson_id = $id;
        $model->type = 'Текст';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validateDesc()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Необхідно заповнити "Текст/примітка".';
                    $model->addError('description', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    public function actionCreateVideo($id)
    {
        $lesson = $this->findLessonModel($id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        $model = new Media();
        $model->lesson_id = $id;
        $model->type = 'Відео';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validateVideo()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Невірне посилання на відео.';
                    $model->addError('description', $errorMsg);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
        ]);
    }

    /**
     * Updates an existing Media model.
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

        if ($model->type == 'Файл') {
            $tpl = 'file';
        } elseif ($model->type == 'Зображення') {
            $tpl = 'image';
        } elseif ($model->type == 'Домашня робота') {
            $tpl = 'hometask';
        } elseif ($model->type == 'Текст') {
            $tpl = 'text';
        } elseif ($model->type == 'Відео') {
            $tpl = 'video';
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->type == 'Файл') {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    unlink('files/' . $model->file);

                    $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;
                    $model->upload();
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Операція успішна.');

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            if ($model->type == 'Зображення') {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile) {
                    unlink('img/media/' . $model->file);

                    $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;
                    $model->upload();
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Операція успішна.');

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            if ($model->type == 'Домашня робота') {
                if ($model->validateDesc()) {
                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    if ($model->imageFile) {
                        unlink('files/' . $model->file);

                        $model->file = md5(microtime() . rand(0, 9999)) . '.' . $model->imageFile->extension;
                        $model->upload();
                    }

                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Необхідно заповнити "Текст/примітка".';
                    $model->addError('description', $errorMsg);
                }
            }

            if ($model->type == 'Текст') {
                if ($model->validateDesc()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Необхідно заповнити "Текст/примітка".';
                    $model->addError('description', $errorMsg);
                }
            }

            if ($model->type == 'Відео') {
                if ($model->validateVideo()) {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    $errorMsg = 'Невірне посилання на відео.';
                    $model->addError('description', $errorMsg);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'lesson' => $lesson,
            'course' => $course,
            'tpl' => $tpl,
        ]);
    }

    /**
     * Deletes an existing Media model.
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

        $this->checkUser($course->id);

        if ($model->file !== null) {
            if ($model->type === 'Зображення') {
                unlink('img/media/' . $model->file);
            } elseif (($model->type === 'Файл') || ($model->type === 'Домашня робота')) {
                unlink('files/' . $model->file);
            }
        }

        $model->delete();

        Yii::$app->session->setFlash('success', 'Операція успішна.');

        return $this->redirect(['index', 'id' => $lesson->id]);
    }

    public function actionDeleteFile($id)
    {
        $model = $this->findModel($id);

        $lesson = $this->findLessonModel($model->lesson_id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        if (($model->type === 'Домашня робота') && (($model->file !== null))) {
            unlink('files/' . $model->file);
            $model->file = null;
        }

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Операція успішна.');
        } else {
            Yii::$app->session->setFlash('warning', 'Виникла помилка, спробуйте пізніше.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);

        $lesson = $this->findLessonModel($model->lesson_id);
        $course = $this->findCourseModel($lesson->course_id);

        $this->checkUser($course->id);

        if (($model->file) !== null) {
            $file = 'files/' . $model->file;

            return Yii::$app->response->sendFile($file);
        }

        throw new NotFoundHttpException(Yii::t('main', 'Запитувана сторінка не існує.'));
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
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne(['id' => $id])) !== null) {
            return $model;
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
