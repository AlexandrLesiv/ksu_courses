<?php

namespace app\controllers;

use app\models\Classes;
use app\models\ClassesSearch;
use app\models\ClassStudents;
use app\models\Courses;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequestsController implements the CRUD actions for ClassStudents model.
 */
class RequestsController extends Controller
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
                    'only' => ['new', 'accept', 'cancel', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['accept', 'cancel', 'delete'],
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
     * Updates an existing ClassStudents model.
     * If accept is successful, the browser will be redirected to the 'requests' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAccept($id)
    {

        $model = $this->findModel($id);
        $course = $this->findCourse($model->course_id);

        $this->checkUser($model->course_id);

        $current_course_count = ClassStudents::find()->where(['course_id' => $course->id, 'status' => 'Підтверджено'])->count();

        if ($course->status === 'Йде набір') {
            if (($course->students_count - $current_course_count) > 0) {
                $classes = Classes::find()->where(['course_id' => $course->id])->orderBy(['title' => SORT_ASC])->all();
                foreach ($classes as $item) {
                    if ($model->class_id === null) {
                        $current_class_count = ClassStudents::find()->where(['class_id' => $item->id, 'status' => 'Підтверджено'])->count();

                        if (($item->students_count - $current_class_count) > 0) {
                            $model->class_id = $item->id;
                            $model->status = 'Підтверджено';
                        }
                    }
                }

                if ($model->save()) {
                    $student = User::findOne(['id' => $model->student_id]);
                    $student_name = $student->last_name . ' ' . mb_substr($student->first_name, 0, 1) . '.';
                    $course_title = $course->title;
                    $subject = 'Відповідь по заявці "' . Yii::$app->name . '"';
                    $body = '<p><strong>Відповідь по заявці "' . Yii::$app->name . '"</strong></p>
                            <hr>
                            <p>Шановний(а), ' . $student_name . ' </p>
                            <p>З радістю повідомляємо вам, що вашу заявку на проходження навчального курсу "' . $course_title . '" було одобрено.</p>';

                    if ($model->contact($student->email, $subject, $body)) {
                        Yii::$app->session->setFlash('success', 'Операція успішна.');

                        return $this->redirect(['/courses/requests', 'id' => $model->course_id]);
                    } else {
                        Yii::$app->session->setFlash('error', 'Виникла помилка, спробуйте ще раз.');
                    }
                }
            } else {
                Yii::$app->session->setFlash('warning', 'Набір на курс завершено. Якщо хочете добавити нових студентів - вам потрібно відредагувати курс та збільшити кількість студентів, після чого потрібно відредагувати навчальну групу або створити нову.');

                return $this->redirect(['/courses/requests', 'id' => $model->course_id]);
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Набір на курс завершено.');

            return $this->redirect(['/courses/requests', 'id' => $model->course_id]);
        }
    }

    /**
     * Updates an existing ClassStudents model.
     * If cancel is successful, the browser will be redirected to the 'requests' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCancel($id)
    {

        $model = $this->findModel($id);

        $this->checkUser($model->course_id);

        $model->class_id = null;
        $model->status = 'Відхилено';

        if ($model->save()) {

            $student = User::findOne(['id' => $model->student_id]);
            $student_name = $student->last_name . ' ' . mb_substr($student->first_name, 0, 1) . '.';
            $course_title = $this->findCourse($model->course_id)->title;
            $subject = 'Відповідь по заявці "' . Yii::$app->name . '"';
            $body = '<p><strong>Відповідь по заявці "' . Yii::$app->name . '"</strong></p>
                    <hr>
                    <p>Шановний(а), ' . $student_name . ' </p>
                    <p>Мусимо повідомити вам, що вашу заявку на проходження навчального курсу "' . $course_title . '" було відхилено.</p>';

            if ($model->contact($student->email, $subject, $body)) {
                Yii::$app->session->setFlash('success', 'Операція успішна.');

                return $this->redirect(['/courses/requests', 'id' => $model->course_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Виникла помилка, спробуйте ще раз.');
            }
        }
    }

    /**
     * Deletes an existing ClassStudents model.
     * If deletion is successful, the browser will be redirected to the 'requests' page.
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

        return $this->redirect(['/courses/requests', 'id' => $model->course_id]);
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
    protected function findCourse($id)
    {
        if (($model = Courses::findOne(['id' => $id])) !== null) {
            return $model;
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
}
