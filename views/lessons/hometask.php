<?php

use yii\bootstrap5\Modal;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Lessons $model */

$this->title = 'Домашня робота';

$this->params['breadcrumbs'][] = [
    'label' => 'Мої курси',
    'url' => ['/courses/index']
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($course->title, 0, 10) . '...',
    'url' => ['/catalogue/view', 'id' => $course->id]
];
$this->params['breadcrumbs'][] = [
    'label' => mb_substr($model->title, 0, 10) . '...',
    'url' => ['info', 'id' => $model->id]
];
$this->params['breadcrumbs'][] = [
    'label' => $this->title,
    'url' => $_SERVER['REQUEST_URI']
];
\yii\web\YiiAsset::register($this);
?>
<div class="lessons-info">

    <h2><?= Html::encode($this->title) ?></h2>

    <p class="control-btn">
        <?php
        Modal::begin([
            'title' => 'Завантаження виконаної роботи',
            'toggleButton' => [
                'label' => '<i class="bx bx-send"></i> Надіслати',
                'tag' => 'a',
                'class' => 'btn btn-action',
            ],
            'size' => 'modal-lg'
        ]);
        ?>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <span class="row">
    <div class="col-12">
        <?= $form->field($homework, 'imageFile', ['options' => ['class' => 'required']])->fileInput() ?>
    </div>

    <div class="col-12 mt-3">
        <?= $form->field($homework, 'description')->textarea() ?>
    </div>
    </span>

    <?= Html::submitButton('<i class="bx bx-save"></i> Зберегти', ['class' => 'btn btn-action mt-3']) ?>

    <?php ActiveForm::end() ?>

    <?php Modal::end(); ?>
    </p>

    <div class="lessons-info__content">
        <?php foreach ($hometask as $item) : ?>
            <?= $item->description ?>
            <?php if ($item->file !== null): ?>
                <p><?= $item->title ?>: <a href="/files/<?= $item->file ?>" download>Завантажити файл</a></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <h3 class="mt-5 mb-3">Виконані роботи</h3>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => '',
                    'label' => 'Домашня робота',
                    'format' => 'raw',
                    'value' => function ($data) {
                        $desc = '';
                        if (!(($data['description'] === '') || ($data['description'] === null))) {
                            $desc = '<p>' . $data['description'] . '</p>';
                        }

                        $comment = '';
                        if ($data['comment'] !== null) {
                            $comment = '<hr><p><small><b>Переглянуто: ' . date('d.m.Y H:i:s', $data['updated_at']) . '</b></small></p>' . $data['comment'];
                        }

                        $file = '<a href="/files/homeworks/' . $data['file'] . '" download>Завантажити файл</a>';
                        return $desc . $file . $comment;
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
                        return Url::toRoute(['/homework/' . $action, 'id' => $model->id]);
                    }
                ],
            ],
        ]); ?>
    </div>
</div>