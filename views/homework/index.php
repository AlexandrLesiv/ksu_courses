<?php

use app\models\Homework;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\HomeworkSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Домашні роботи';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['/courses/view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Групи',
    'url' => ['index', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($lesson->title, 0, 10) . '...',
    'url' => ['/lessons/view', 'id' => $lesson->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="homework-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search',
            [
                'action' => ['index', 'id' => $lesson->id],
                'model' => $searchModel,
            ]
        )
        ?>
    </div>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'student',
                    'label' => 'Студент',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $user = \app\models\User::findOne(['id' => $data['student_id']]);
                        $last_name = $user->last_name;
                        $first_name = ' ' . $user->first_name;
                        $middle_name = $user->middle_name === null ? '' : ' ' . $user->middle_name;

                        $lesson = \app\models\Lessons::findOne(['id' => $data['lesson_id']]);
                        $course = \app\models\Courses::findOne(['id' => $lesson->course_id]);
                        $class_student = \app\models\ClassStudents::findOne(['student_id' => $data['student_id'], 'course_id' => $course->id]);
                        $class = '<b>' . \app\models\Classes::findOne(['id' => $class_student->class_id])->title . '</b> ';


                        $desc = '';
                        if (!(($data['description'] === '') || ($data['description'] === null))) {
                            $desc = '<p>' . $data['description'] . '</p>';
                        }

                        $comment = '';
                        if ($data['comment'] !== null) {
                            $comment = '<hr><p><small><b>Переглянуто: ' . date('d.m.Y H:i:s', $data['updated_at']) . '</b></small></p>' . $data['comment'];
                        }

                        $file = '<a href="/files/homeworks/' . $data['file'] . '" download>Завантажити файл</a>';

                        return $class . $last_name . $first_name . $middle_name . '<hr>' . $desc . $file . $comment;
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($data) {
                        return date('d.m.Y H:i:s', $data['created_at']);
                    },
                ],

                [
                    'class' => ActionColumn::className(),
                    'template' => '{update} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute(['/homework/' . $action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>
