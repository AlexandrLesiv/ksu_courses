<?php

use app\models\Classes;
use app\models\ClassStudents;
use app\models\Courses;
use app\models\Timetable;
use app\models\User;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Courses $model */

$this->title = $model->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Всі курси',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($this->title, 0, 10) . '...',
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);

$favorite_link = $favorite === null ? '<i class="bx bx-star"></i>В обране' : '<i class="bx bxs-star"></i>З обраного';
$request_link = $request === null ? '<i class="bx bx-plus"></i>Записатись' : '<i class="bx bx-x"></i>Відписатись';
?>
<div class="courses-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <?php if (($model->status === 'Новий') || $model->status === 'Йде набір') : ?>
        <p class="control-btn">
            <?php if ($model->status === 'Йде набір') : ?>
                <?= Html::a($request_link, ['request', 'id' => $model->id], [
                    'class' => 'btn btn-action',
                    'data' => [
                        'confirm' => 'Ви впевнені, що хочете записатись на цей курс?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>

            <?php if (($model->status === 'Новий') || $model->status === 'Йде набір') : ?>
                <?= Html::a($favorite_link, ['favorite', 'id' => $model->id], [
                    'class' => 'btn btn-action',
                    'data' => [
                        'confirm' => 'Ви впевнені, що хочете додати в обране цей курс?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </p>
    <?php endif; ?>

    <p class="mb-3"><?= $request === null ? 'Ви ще не подали запит на проходження цього курсу.' : 'Запит на проходження курсу подано, його статус - <b>' . $request->status . '</b>.' ?></p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'teacher_id',
                'format' => 'raw',
                'value' => function ($data) {
                    $teacher = User::findOne(['id' => $data['teacher_id']]);
                    $last_name = $teacher->last_name;
                    $first_name = ' ' . $teacher->first_name;
                    $middle_name = ' ' . $teacher->middle_name;
                    $teacher_link = Html::a($last_name . $first_name . $middle_name, ['/user/info', 'id' => $teacher->id]);
                    $status = $teacher->status === 99 ? '<i class="bx bx-lock"></i>' : '';

                    return '<b>Викладач:</b> ' . $status . ' ' . $teacher_link;
                },
            ],
            'description',
            [
                'attribute' => 'students_count',
                'label' => 'К-сть. студентів',
                'format' => 'raw',
                'value' => function ($data) {
                    $min = 0;
                    $max = $data['students_count'];
                    $now = ClassStudents::find()->where(['course_id' => $data['id'], 'status' => 'Підтверджено'])->count();
                    $width = number_format($now / $max * 100, 2, '.', '');
                    return '<div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: ' . $width . '%" aria-valuenow="' . $now . '" aria-valuemin="' . $min . '" aria-valuemax="' . $max . '">' . $now . '/' . $max . '</div></div>';
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['status'] === 'Новий') {
                        $style = 'info';
                    } elseif ($data['status'] === 'В процесі') {
                        $style = 'success';
                    } elseif ($data['status'] === 'Йде набір') {
                        $style = 'primary';
                    } elseif ($data['status'] === 'Завершено') {
                        $style = 'secondary';
                    } elseif ($data['status'] === 'Заблоковано') {
                        $style = 'danger';
                    }
                    return '<span class="badge bg-' . $style . '">' . $data['status'] . '</span>';
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['created_at']);
                },
            ],
        ],
    ]) ?>

    <p class="control-btn control-btn--inverse">
        <?= Html::a('<i class="bx bx-chat"></i>Форум', ['/forum/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
    </p>

    <h3 class="mt-5">Список уроків</h3>
    <div class="table-responsive mt-3">
        <?php if ($class !== null) : ?>
            <?= GridView::widget([
                'dataProvider' => $lessons,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'Урок',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<b>' . $data['title'] . '</b><br>' . $data['description'];
                        },
                    ],
                    [
                        'label' => 'Початок',
                        'value' => function ($data) {
                            $class_student = ClassStudents::findOne(
                                [
                                    'course_id' => $data['course_id'],
                                    'student_id' => Yii::$app->user->id
                                ]
                            );
                            $timetable = Timetable::findOne(
                                [
                                    'class_id' => $class_student->class_id,
                                    'lesson_id' => $data['id']
                                ]
                            );

                            return $timetable !== null ? $timetable->start : '-';
                        },
                    ],
                    [
                        'label' => 'Кінець',
                        'value' => function ($data) {
                            $class_student = ClassStudents::findOne(
                                [
                                    'course_id' => $data['course_id'],
                                    'student_id' => Yii::$app->user->id
                                ]
                            );
                            $timetable = Timetable::findOne(
                                [
                                    'class_id' => $class_student->class_id,
                                    'lesson_id' => $data['id']
                                ]
                            );

                            return $timetable !== null ? $timetable->end : '-';
                        },
                    ],
                    [
                        'label' => 'Статус',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $class_student = ClassStudents::findOne(
                                [
                                    'course_id' => $data['course_id'],
                                    'student_id' => Yii::$app->user->id
                                ]
                            );
                            $timetable = Timetable::findOne(
                                [
                                    'class_id' => $class_student->class_id,
                                    'lesson_id' => $data['id']
                                ]
                            );

                            $res = $timetable !== null ? $timetable->status : 'Новий';

                            if ($res === 'Новий') {
                                $style = 'info';
                            } elseif ($res === 'Завершено') {
                                $style = 'secondary';
                            }

                            return '<span class="badge bg-' . $style . '">' . $res . '</span>';
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{info} {link}',
                        'urlCreator' => function ($action, $model, $key, $index, $column) {
                            return Url::toRoute(['/lessons/' . $action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        <?php else : ?>
            <?= GridView::widget([
                'dataProvider' => $lessons,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'label' => 'Урок',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return '<b>' . $data['title'] . '</b><br>' . $data['description'];
                        },
                    ],
                    [
                        'label' => 'Статус',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if (Courses::findOne(['id' => $data['course_id']])->status !== 'Завершено') {
                                $res = 'Новий';
                                $style = 'info';
                            } else {
                                $res = 'Завершено';
                                $style = 'secondary';
                            }
                            return '<span class="badge bg-' . $style . '">' . $res . '</span>';
                        },
                    ],
                ],
            ]); ?>
        <?php endif; ?>
    </div>
</div>