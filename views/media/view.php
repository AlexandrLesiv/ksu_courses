<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Media $model */

$this->title = mb_substr($model->title, 0, 10) . '...';

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
    'label' => 'НМ',
    'url' => ['index', 'id' => $lesson->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="media-view">

    <h2><?= Html::encode($model->title) ?></h2>

    <p class="control-btn">
        <?= Html::a('<i class="bx bx-edit-alt"></i>Редагувати', ['update', 'id' => $model->id], ['class' => 'btn btn-action']) ?>
        <?php if (($model->type === 'Домашня робота') && ($model->file !== null)) : ?>
            <?= Html::a('<i class="bx bxs-trash"></i>Видалити файл', ['delete-file', 'id' => $model->id], [
                'class' => 'btn btn-action',
                'data' => [
                    'confirm' => 'Ви впевнені, що хочете видалити файл?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
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
            'type',
            'title',
            'description:raw',
            [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data['file'] !== null) {
                        if ($data['type'] === 'Зображення') {
                            $attachment = Html::img('@web/img/media/' . $data['file'], [
                                'style' => 'width:auto;max-width:100%;',
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
                'attribute' => 'updated_at',
                'value' => function ($data) {
                    return date('d.m.Y H:i:s', $data['updated_at']);
                },
            ],
        ],
    ]) ?>

</div>