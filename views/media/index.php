<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\MediaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Навчальні матеріали';

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
<div class="media-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-plus"></i> Текст', ['create-text', 'id' => $lesson->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-plus"></i> Файл', ['create-file', 'id' => $lesson->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-plus"></i> Зображення', ['create-image', 'id' => $lesson->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-plus"></i> Відео', ['create-video', 'id' => $lesson->id], ['class' => 'btn btn-action']) ?>
        <?= Html::a('<i class="bx bx-plus"></i> Дом. роб.', ['create-hometask', 'id' => $lesson->id], ['class' => 'btn btn-action']) ?>
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
                    'attribute' => 'title',
                    'label' => 'Назва',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return '<small>' . $data['type'] . '</small><br><b>' . $data['title'] . '</b>';
                    },
                ],
                [
                    'attribute' => 'file',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data['file'] !== null) {
                            if ($data['type'] === 'Зображення') {
                                $attachment = Html::img('@web/img/media/' . $data['file'], [
                                    'style' => 'max-width:100px;',
                                    'alt' => $data['title']
                                ]);
                            } else {
                                $attachment = Html::a('<i class="bx bx-download"></i> Файл', ['download', 'id' => $data['id']]);
                            }
                        } else {
                            $attachment = '-';
                        }

                        return $attachment;
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
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>

</div>