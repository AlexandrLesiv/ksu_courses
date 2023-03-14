<?php

use app\models\Classes;
use app\models\ClassStudents;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ClassesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Заявки';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($model->title, 0, 10) . '...',
    'url' => ['view', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
?>
<div class="courses-requests">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search-requests',
            [
                'action' => ['requests', 'id' => $model->id],
                'classes' => $classes,
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
                        $user = User::findOne(['id' => $data['student_id']]);
                        $last_name = $user->last_name;
                        $first_name = ' ' . $user->first_name;
                        $middle_name = $user->middle_name === null ? '' : ' ' . $user->middle_name;

                        $status = '';
                        if ($user->status === 99) {
                            $status = '<span class="badge bg-danger">БАН</span><br>';
                        }

                        $class = '';
                        if ($data['class_id'] !== null) {
                            $class = '<b>' . Classes::findOne(['id' => $data['class_id']])->title . '</b> ';
                        }

                        return $status . $class . $last_name . $first_name . $middle_name
                            . '<br><a href="mailto:' . $user->email . '">' . $user->email . '</a>';
                    },
                ],
                [
                    'attribute' => '',
                    'label' => 'Дія',
                    'format' => 'raw',
                    'headerOptions' => ['style' => 'width:80px;color:#3F7856;font-weight:500;'],
                    'value' => function ($data) {
                        $accept = Html::a('<i class="bx bx-check"></i>', ['/requests/accept', 'id' => $data['id']], [
                            'class' => 'status-accept',
                            'data' => [
                                'confirm' => 'Ви впевнені, що хочете підтвердити заявку?',
                                'method' => 'post',
                            ],
                        ]);
                        $cancel = Html::a('<i class="bx bx-x"></i>', ['/requests/cancel', 'id' => $data['id']], [
                            'class' => 'status-accept',
                            'data' => [
                                'confirm' => 'Ви впевнені, що хочете відхилити заявку?',
                                'method' => 'post',
                            ],
                        ]);
                        if ($data['status'] === 'Новий') {
                            $control = $accept . ' ' . $cancel;
                        } elseif ($data['status'] === 'Підтверджено') {
                            $control = $cancel;
                        } elseif ($data['status'] === 'Відхилено') {
                            $control = $accept;
                        }

                        return $control;
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data['status'] === 'Новий') {
                            $status_class = 'info';
                        } elseif ($data['status'] === 'Підтверджено') {
                            $status_class = 'success';
                        } elseif ($data['status'] === 'Відхилено') {
                            $status_class = 'danger';
                        }

                        return '<span class="badge bg-' . $status_class . '">' . $data['status'] . '</span>';
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
                    'template' => '{delete} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute(['/requests/' . $action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>