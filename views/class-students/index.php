<?php

use app\models\Classes;
use app\models\ClassStudents;
use app\models\Courses;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ClassStudentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Студенти групи ' . $class->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr(Courses::findOne(['id' => $class->course_id])->title, 0, 10) . '...',
    'url' => ['/courses/view', 'id' => $class->course_id]
];
$this->params['breadcrumbs'][] = [
    'label' => 'Групи',
    'url' => ['/courses/classes', 'id' => $class->course_id]
];
$this->params['breadcrumbs'][] = [
    'label' => $class->title,
    'url' => ['/classes/view', 'id' => $class->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="class-students-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search-class-students',
            [
                'action' => ['index', 'id' => $class->id],
                'model' => $searchModel,
            ]
        )
        ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'student',
                'label' => 'Студент',
                'format' => 'raw',
                'value' => function ($data) {
                    $user = User::findOne(['id' => $data['student_id']]);
                    $last_name = $user->last_name;
                    $first_name = ' ' . $user->first_name;
                    $middle_name = $user->middle_name === null ? '' : ' ' . $user->middle_name;

                    $status = '';
                    if ($user->status === 99) {
                        $status = '<span class="badge bg-danger">БАН</span><br>';
                    }

                    return $status . $last_name . $first_name . $middle_name
                        . '<br><a href="mailto:' . $user->email . '">' . $user->email . '</a>';
                },
            ],
            [
                'attribute' => 'updated_at',
                'label' => 'Прийнято',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],

            [
                'class' => ActionColumn::className(),
                'template' => '{delete} {link}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>