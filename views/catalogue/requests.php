<?php

use app\models\ClassStudents;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CatalogueSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мої запити';

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
            '_search',
            [
                'action' => ['requests'],
                'category' => 'requests',
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
                    'attribute' => 'title',
                    'label' => 'Навчальний курс',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $teacher = User::findOne(['id' => $data['teacher_id']]);
                        $last_name = $teacher->last_name;
                        $first_name = ' ' . $teacher->first_name;
                        $middle_name = ' ' . $teacher->middle_name;
                        $teacher_link = Html::a($last_name . $first_name . $middle_name, ['/user/info', 'id' => $teacher->id]);
                        $status = $teacher->status === 99 ? '<i class="bx bx-lock"></i>' : '';
                        return '<b>' . $data['title'] . '</b>
                            <br><small><b>Викладач:</b> ' . $status . ' ' . $teacher_link . '</small>
                            <br>' . $data['description'];
                    },
                ],
                [
                    'attribute' => '',
                    'label' => 'Запит',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $req = ClassStudents::findOne(['course_id' => $data['id'], 'student_id' => Yii::$app->user->id]);
                        return 'Запит подано ' . date('d.m.Y H:i:s', $req->created_at) . ', його статус - <b>' . $req->status . '</b>';
                    },
                ],

                [
                    'class' => ActionColumn::className(),
                    'template' => '{view} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>