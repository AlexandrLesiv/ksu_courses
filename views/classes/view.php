<?php

use app\models\ClassStudents;
use app\models\Timetable;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Classes $model */

$this->title = $model->title;

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
    'url' => ['/courses/classes', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];


\yii\web\YiiAsset::register($this);
?>
<div class="classes-view">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bxs-user-detail"></i>Студенти', ['/class-students/index', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-trash"></i>Видалити', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-action',
            'data' => [
                'confirm' => 'Ви впевнені, що хочете видалити цей елемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'attribute' => 'students_count',
                'label' => 'К-сть. студентів',
                'format' => 'raw',
                'value' => function ($data) {
                    $min = 0;
                    $max = $data['students_count'];
                    $now = ClassStudents::find()->where(['class_id' => $data['id'], 'status' => 'Підтверджено'])->count();
                    $width = number_format($now / $max * 100, 2, '.', '');
                    return '<div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: ' . $width . '%" aria-valuenow="' . $now . '" aria-valuemin="' . $min . '" aria-valuemax="' . $max . '">' . $now . '/' . $max . '</div></div>';
                },
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['created_at']);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],
        ],
    ]) ?>

    <h3 class="mt-5">Розклад уроків</h3>
    <div class="table-responsive mt-3">
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
                        $timetable = Timetable::findOne(
                            [
                                'class_id' => Yii::$app->request->get('id'),
                                'lesson_id' => $data['id']
                            ]
                        );

                        return $timetable !== null ? $timetable->start : '-';
                    },
                ],
                [
                    'label' => 'Кінець',
                    'value' => function ($data) {
                        $timetable = Timetable::findOne(
                            [
                                'class_id' => Yii::$app->request->get('id'),
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
                        $timetable = Timetable::findOne(
                            [
                                'class_id' => Yii::$app->request->get('id'),
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
                    'template' => '{update} {status} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute(['timetable-' . $action, 'id' => Yii::$app->request->get('id') . '-' . $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
</div>