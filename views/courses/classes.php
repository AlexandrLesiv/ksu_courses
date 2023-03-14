<?php

use app\models\Classes;
use app\models\ClassStudents;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\ClassesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Групи';

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
<div class="courses-classes">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-plus"></i> Додати', ['/classes/create', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-filter-alt"></i> Фільтри', [''], ['class' => 'btn btn-action btn-filter']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <div class="form-filter d-none">
        <?= $this->render(
            '_search-classes',
            [
                'action' => ['classes', 'id' => $model->id],
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
                        return $now . '/' . $max . '<br><div class="progress"><div class="progress-bar bg-success" role="progressbar" style="width: ' . $width . '%" aria-valuenow="' . $now . '" aria-valuemin="' . $min . '" aria-valuemax="' . $max . '"></div></div>';
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
                    'template' => '{view} {update} {delete} {link}',
                    'urlCreator' => function ($action, $model, $key, $index, $column) {
                        return Url::toRoute(['/classes/' . $action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>